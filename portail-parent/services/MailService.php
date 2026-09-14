<?php
declare(strict_types=1);

class MailService
{
    private string $from;
    private string $appName;
    private string $smtpHost;
    private int $smtpPort;
    private string $smtpUser;
    private string $smtpPass;
    private string $smtpEncryption;

    public function __construct()
    {
        $this->from = getenv('MAIL_FROM') ?: 'noreply@ecoleplus.tg';
        $this->appName = getenv('APP_NAME') ?: 'Ecole Plus';
        $this->smtpHost = getenv('MAIL_SMTP_HOST') ?: 'smtp.gmail.com';
        $this->smtpPort = (int)(getenv('MAIL_SMTP_PORT') ?: 587);
        $this->smtpUser = getenv('MAIL_SMTP_USER') ?: $this->from;
        $this->smtpPass = getenv('MAIL_SMTP_PASS') ?: '';
        $this->smtpEncryption = getenv('MAIL_SMTP_ENCRYPTION') ?: 'tls';
    }

    public function sendOtpEmail(string $to, string $code, string $type): bool
    {
        $subject = match ($type) {
            'login' => "[$this->appName] Code de connexion",
            'registration' => "[$this->appName] Code de confirmation d'inscription",
            default => "[$this->appName] Votre code de sécurité",
        };

        $body = $this->buildOtpBody($code, $type);
        return $this->send($to, $subject, $body);
    }

    public function sendInvitationEmail(string $to, string $parentName, string $childName, string $token): bool
    {
        $url = (getenv('APP_URL') ?: 'http://localhost/demoecoleplus/portail-parent')
            . '/auth/inscription.php?token=' . $token;

        $subject = "[$this->appName] Invitation à rejoindre le portail parent";
        $body = $this->buildInvitationBody($parentName, $childName, $url);
        return $this->send($to, $subject, $body);
    }

    private function send(string $to, string $subject, string $htmlBody): bool
    {
        if ($this->smtpHost === '' || $this->smtpPass === '') {
            error_log('[MailService] SMTP non configuré (MAIL_SMTP_HOST ou MAIL_SMTP_PASS manquant)');
            return false;
        }

        try {
            $errno = 0;
            $errstr = '';
            $fp = fsockopen(
                $this->smtpHost,
                $this->smtpPort,
                $errno,
                $errstr,
                30
            );

            if (!$fp) {
                error_log("[MailService] Connexion SMTP échouée: [$errno] $errstr");
                return false;
            }

            $response = $this->smtpRead($fp);
            $this->smtpCommand($fp, 'EHLO ' . php_uname('n'));

            if ($this->smtpEncryption === 'tls') {
                $this->smtpCommand($fp, 'STARTTLS');
                stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT);
                $this->smtpCommand($fp, 'EHLO ' . php_uname('n'));
            }

            $this->smtpCommand($fp, 'AUTH LOGIN');
            $this->smtpCommand($fp, base64_encode($this->smtpUser));
            $this->smtpCommand($fp, base64_encode($this->smtpPass));

            $this->smtpCommand($fp, "MAIL FROM:<{$this->from}>");
            $this->smtpCommand($fp, "RCPT TO:<{$to}>");
            $this->smtpCommand($fp, 'DATA');

            $headers = "From: {$this->appName} <{$this->from}>\r\n";
            $headers .= "Reply-To: {$this->from}\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "Date: " . date('r') . "\r\n";
            $headers .= "\r\n";

            $this->smtpWrite($fp, $headers . $htmlBody);
            $this->smtpCommand($fp, '.');

            $this->smtpCommand($fp, 'QUIT');
            fclose($fp);

            return true;
        } catch (\Throwable $e) {
            error_log('[MailService] Erreur SMTP: ' . $e->getMessage());
            return false;
        }
    }

    private function smtpRead($fp): string
    {
        $response = '';
        while (true) {
            $line = fgets($fp, 512);
            if ($line === false) break;
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') break;
        }
        return $response;
    }

    private function smtpCommand($fp, string $command): string
    {
        fwrite($fp, $command . "\r\n");
        $response = $this->smtpRead($fp);
        if (isset($response[0]) && $response[0] === '5') {
            error_log("[MailService] SMTP error on '$command': $response");
        }
        return $response;
    }

    private function smtpWrite($fp, string $data): void
    {
        $lines = explode("\r\n", $data);
        foreach ($lines as $line) {
            fwrite($fp, $line . "\r\n");
        }
    }

    private function buildOtpBody(string $code, string $type): string
    {
        $typeLabel = match ($type) {
            'login' => 'connexion',
            'registration' => 'inscription',
            default => 'sécurité',
        };

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">
<div style="max-width:520px;margin:30px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);">
  <div style="background:linear-gradient(135deg,#0f766e,#0e9388);padding:28px 24px;text-align:center;">
    <h1 style="color:#fff;margin:0;font-size:22px;">{$this->appName}</h1>
  </div>
  <div style="padding:32px 24px;text-align:center;">
    <p style="color:#475569;font-size:15px;margin:0 0 8px;">Code de {$typeLabel}</p>
    <div style="background:#f0fdfa;border:2px dashed #0d9488;border-radius:10px;padding:18px;margin:16px 0;">
      <span style="font-size:36px;font-weight:700;letter-spacing:8px;color:#0f766e;">{$code}</span>
    </div>
    <p style="color:#94a3b8;font-size:13px;margin:16px 0 0;">Ce code expire dans 5 minutes.<br>Ne partagez ce code avec personne.</p>
  </div>
</div>
</body>
</html>
HTML;
    }

    private function buildInvitationBody(string $parentName, string $childName, string $url): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">
<div style="max-width:520px;margin:30px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);">
  <div style="background:linear-gradient(135deg,#0f766e,#0e9388);padding:28px 24px;text-align:center;">
    <h1 style="color:#fff;margin:0;font-size:22px;">{$this->appName}</h1>
  </div>
  <div style="padding:32px 24px;">
    <h2 style="color:#17313d;font-size:18px;margin:0 0 16px;">Vous êtes invité(e) !</h2>
    <p style="color:#475569;font-size:14px;line-height:1.6;">
      Bonjour <strong>{$parentName}</strong>,<br><br>
      L'administration de l'établissement vous invite à rejoindre le portail parent
      pour suivre la scolarité de <strong>{$childName}</strong>.
    </p>
    <div style="text-align:center;margin:28px 0;">
      <a href="{$url}" style="display:inline-block;padding:14px 32px;background:linear-gradient(135deg,#0f766e,#0e9388);color:#fff;text-decoration:none;border-radius:8px;font-weight:600;font-size:15px;">Créer mon compte parent</a>
    </div>
    <p style="color:#94a3b8;font-size:12px;margin:0;">Ce lien expire dans 7 jours. Si vous n'avez pas demandé cette inscription, ignorez cet email.</p>
  </div>
</div>
</body>
</html>
HTML;
    }
}
