<?php
/**
 * DatabaseSessionHandler — Implémentation SessionHandlerInterface (v1.2.0)
 * 
 * Stocke les sessions PHP en base de données avec chiffrement AES-256-GCM.
 * Compatible avec le sérialiseur PHP natif (format "clé|sérialisé;").
 * 
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

class DatabaseSessionHandler implements SessionHandlerInterface
{
    private \PDO $pdo;
    private string $encryptionKey;
    private bool $useEncryption;
    private int $maxLifetime = 86400;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $envKey = getenv('ECOLEPLUS_SESSION_KEY');
        $this->encryptionKey = !empty($envKey) ? hash('sha256', $envKey, true) : '';
        $this->useEncryption = !empty($this->encryptionKey)
            && function_exists('openssl_encrypt')
            && defined('OPENSSL_RAW_DATA')
            && in_array('aes-256-gcm', openssl_get_cipher_methods(), true);
    }

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT payload, last_activity 
                 FROM sessions_parents 
                 WHERE id = ? 
                 LIMIT 1"
            );
            $stmt->execute([$id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('[EcolePlus] Erreur read session: ' . $e->getMessage());
            return '';
        }

        if (!$row) {
            return '';
        }

        if ((int)$row['last_activity'] < time() - $this->maxLifetime) {
            $this->destroy($id);
            return '';
        }

        $payload = (string)($row['payload'] ?? '');
        if ($this->useEncryption && !empty($payload)) {
            $decrypted = $this->decrypt($payload);
            if ($decrypted === false) {
                error_log('[EcolePlus] Échec déchiffrement session id=' . substr($id, 0, 8) . '...');
                return '';
            }
            $payload = $decrypted;
        }

        return $payload;
    }

    public function write(string $id, string $data): bool
    {
        try {
            if (!$this->isPdoAlive()) {
                error_log('[EcolePlus] Connexion PDO perdue avant write session id=' . substr($id, 0, 8) . '...');
                return false;
            }

            $storedData = $data;
            if ($this->useEncryption) {
                $encrypted = $this->encrypt($data);
                if ($encrypted === false) {
                    error_log('[EcolePlus] Échec chiffrement session id=' . substr($id, 0, 8) . '...');
                    return false;
                }
                $storedData = $encrypted;
            }

            $parentId = $this->extractParentId($data);
            $userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500);

            $stmt = $this->pdo->prepare(
                "INSERT INTO sessions_parents (id, parent_id, ip_address, user_agent, payload, last_activity)
                 VALUES (?, ?, ?, ?, ?, UNIX_TIMESTAMP())
                 ON DUPLICATE KEY UPDATE 
                    payload = VALUES(payload),
                    last_activity = VALUES(last_activity),
                    ip_address = VALUES(ip_address),
                    user_agent = VALUES(user_agent)"
            );

            return $stmt->execute([
                $id,
                $parentId,
                $_SERVER['REMOTE_ADDR'] ?? '',
                $userAgent,
                $storedData,
            ]);
        } catch (\PDOException $e) {
            error_log('[EcolePlus] Erreur write session: ' . $e->getMessage());
            return false;
        }
    }

    private function isPdoAlive(): bool
    {
        try {
            return $this->pdo instanceof \PDO && $this->pdo->query('SELECT 1') !== false;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function destroy(string $id): bool
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM sessions_parents WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            error_log('[EcolePlus] Erreur destroy session: ' . $e->getMessage());
            return false;
        }
    }

    public function gc(int $max_lifetime): int|false
    {
        try {
            $cutoff = time() - $max_lifetime;
            $stmt = $this->pdo->prepare("DELETE FROM sessions_parents WHERE last_activity < ?");
            $stmt->execute([$cutoff]);
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            error_log('[EcolePlus] Erreur gc sessions: ' . $e->getMessage());
            return false;
        }
    }

    public function setMaxLifetime(int $seconds): void
    {
        $this->maxLifetime = $seconds;
    }

    private function extractParentId(string $data): ?int
    {
        if (preg_match('/s:9:"id_parent";i:(\d+)/', $data, $matches)) {
            return (int)$matches[1];
        }
        if (preg_match('/s:9:"parent_id";i:(\d+)/', $data, $matches)) {
            return (int)$matches[1];
        }
        if (preg_match('/id_parent\|i:(\d+)/', $data, $matches)) {
            return (int)$matches[1];
        }
        if (preg_match('/parent_id\|i:(\d+)/', $data, $matches)) {
            return (int)$matches[1];
        }
        return null;
    }

    private function encrypt(string $data): string|false
    {
        try {
            $iv = random_bytes(12);
            $tag = '';
            $encrypted = openssl_encrypt(
                $data,
                'aes-256-gcm',
                $this->encryptionKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag,
                '',
                16
            );

            if ($encrypted === false || strlen($tag) !== 16) {
                return false;
            }

            return base64_encode($iv . $tag . $encrypted);
        } catch (\Exception $e) {
            return false;
        }
    }

    private function decrypt(string $encoded): string|false
    {
        try {
            $raw = base64_decode($encoded, true);
            if ($raw === false || strlen($raw) < 29) {
                return false;
            }

            $iv = substr($raw, 0, 12);
            $tag = substr($raw, 12, 16);
            $encrypted = substr($raw, 28);

            return openssl_decrypt(
                $encrypted,
                'aes-256-gcm',
                $this->encryptionKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );
        } catch (\Exception $e) {
            return false;
        }
    }
}