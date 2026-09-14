<?php
declare(strict_types=1);

class OtpService
{
    private PDO $pdo;
    private int $length;
    private int $expirySeconds;
    private int $maxAttempts;
    private int $resendDelay;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->length = (int)(getenv('OTP_LENGTH') ?: 6);
        $this->expirySeconds = (int)(getenv('OTP_EXPIRATION') ?: 300);
        $this->maxAttempts = (int)(getenv('OTP_MAX_ATTEMPTS') ?: 5);
        $this->resendDelay = (int)(getenv('OTP_RESEND_DELAY') ?: 60);
    }

    public function generate(int $parentId, string $type, ?string $email = null, ?string $telephone = null): array
    {
        $this->invalidatePending($parentId, $type);

        $code = $this->generateCode();
        $hash = hash('sha256', $code);
        $expiresAt = date('Y-m-d H:i:s', time() + $this->expirySeconds);
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $stmt = $this->pdo->prepare(
            'INSERT INTO otp_codes (parent_id, type, code_hash, email, telephone, expires_at, ip_address)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$parentId, $type, $hash, $email, $telephone, $expiresAt, $ip]);

        return [
            'otp_id' => (int)$this->pdo->lastInsertId(),
            'code' => $code,
            'expires_at' => $expiresAt,
        ];
    }

    public function verify(int $parentId, string $type, string $code): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, code_hash, expires_at, attempts, used_at
             FROM otp_codes
             WHERE parent_id = ? AND type = ? AND used_at IS NULL
             ORDER BY id DESC LIMIT 1'
        );
        $stmt->execute([$parentId, $type]);
        $otp = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$otp) {
            return ['ok' => false, 'error' => 'Aucun code actif trouvé.'];
        }

        if ($otp['used_at'] !== null) {
            return ['ok' => false, 'error' => 'Ce code a déjà été utilisé.'];
        }

        if (strtotime($otp['expires_at']) < time()) {
            return ['ok' => false, 'error' => 'Ce code a expiré.'];
        }

        if ((int)$otp['attempts'] >= $this->maxAttempts) {
            $this->markUsed((int)$otp['id']);
            return ['ok' => false, 'error' => 'Trop de tentatives. Demandez un nouveau code.'];
        }

        $this->pdo->prepare('UPDATE otp_codes SET attempts = attempts + 1 WHERE id = ?')
            ->execute([(int)$otp['id']]);

        if (!hash_equals($otp['code_hash'], hash('sha256', $code))) {
            $remaining = $this->maxAttempts - (int)$otp['attempts'] - 1;
            return [
                'ok' => false,
                'error' => 'Code incorrect.',
                'attempts_remaining' => max(0, $remaining),
            ];
        }

        $this->markUsed((int)$otp['id']);
        return ['ok' => true];
    }

    public function canResend(int $parentId, string $type): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, created_at FROM otp_codes
             WHERE parent_id = ? AND type = ? AND used_at IS NULL
             ORDER BY id DESC LIMIT 1'
        );
        $stmt->execute([$parentId, $type]);
        $last = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$last) {
            return ['allowed' => true, 'wait_seconds' => 0];
        }

        $elapsed = time() - strtotime($last['created_at']);
        if ($elapsed >= $this->resendDelay) {
            return ['allowed' => true, 'wait_seconds' => 0];
        }

        return ['allowed' => false, 'wait_seconds' => $this->resendDelay - $elapsed];
    }

    public function getCodeHash(int $otpId): ?string
    {
        $stmt = $this->pdo->prepare('SELECT code_hash FROM otp_codes WHERE id = ?');
        $stmt->execute([$otpId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['code_hash'] : null;
    }

    private function invalidatePending(int $parentId, string $type): void
    {
        $this->pdo->prepare(
            'UPDATE otp_codes SET used_at = NOW() WHERE parent_id = ? AND type = ? AND used_at IS NULL'
        )->execute([$parentId, $type]);
    }

    private function markUsed(int $otpId): void
    {
        $this->pdo->prepare('UPDATE otp_codes SET used_at = NOW() WHERE id = ?')
            ->execute([$otpId]);
    }

    private function generateCode(): string
    {
        $min = (int)str_repeat('1', $this->length);
        $max = (int)str_repeat('9', $this->length);
        return (string)random_int($min, $max);
    }
}
