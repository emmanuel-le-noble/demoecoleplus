<?php
declare(strict_types=1);

class SmsService
{
    private bool $enabled;
    private string $provider;
    private string $apiUrl;
    private string $apiKey;
    private string $apiSecret;
    private string $sender;

    public function __construct()
    {
        $this->enabled = filter_var(getenv('SMS_ENABLED') ?: 'false', FILTER_VALIDATE_BOOLEAN);
        $this->provider = getenv('SMS_PROVIDER') ?: '';
        $this->apiUrl = getenv('SMS_API_URL') ?: '';
        $this->apiKey = getenv('SMS_API_KEY') ?: '';
        $this->apiSecret = getenv('SMS_API_SECRET') ?: '';
        $this->sender = getenv('SMS_SENDER') ?: '';
    }

    public function isEnabled(): bool
    {
        return $this->enabled && $this->apiUrl !== '' && $this->apiKey !== '';
    }

    public function sendOtpSms(string $telephone, string $code, string $type): array
    {
        if (!$this->isEnabled()) {
            return [
                'sent' => false,
                'provider' => 'none',
                'message' => 'Service SMS non configuré.',
            ];
        }

        $typeLabel = match ($type) {
            'login' => 'connexion',
            'registration' => 'inscription',
            default => 'sécurité',
        };

        $appName = getenv('APP_NAME') ?: 'Ecole Plus';
        $message = "$appName: Votre code de {$typeLabel} est {$code}. Valable 5 min. Ne partagez pas ce code.";

        $result = match ($this->provider) {
            'bestcom' => $this->sendBestCom($telephone, $message),
            'twilio' => $this->sendTwilio($telephone, $message),
            default => $this->sendGeneric($telephone, $message),
        };

        return $result;
    }

    private function cleanPhone(string $phone): string
    {
        $phone = preg_replace('/[\s\-\(\)\.]/', '', trim($phone));
        if (str_starts_with($phone, '00')) {
            $phone = '+' . substr($phone, 2);
        }
        if (!str_starts_with($phone, '+') && strlen($phone) === 8) {
            $phone = '228' . $phone;
        }
        if (str_starts_with($phone, '+')) {
            $phone = substr($phone, 1);
        }
        return $phone;
    }

    private function sendBestCom(string $to, string $message): array
    {
        $to = $this->cleanPhone($to);

        $data = [
            'Api_Key'    => $this->apiKey,
            'Api_secret' => $this->apiSecret,
            'Contact'    => $to,
            'Titre'      => $this->sender,
            'Message'    => $message,
        ];

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            error_log("[SmsService] BestCom cURL error: $curlError");
            return ['sent' => false, 'provider' => 'bestcom', 'error' => $curlError];
        }

        $sent = $httpCode >= 200 && $httpCode < 300;
        if (!$sent) {
            error_log("[SmsService] BestCom HTTP $httpCode: $response");
        }

        return [
            'sent' => $sent,
            'provider' => 'bestcom',
            'http_code' => $httpCode,
            'response' => $response,
        ];
    }

    private function sendTwilio(string $to, string $message): array
    {
        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->apiKey}/Messages.json";
        $data = http_build_query([
            'From' => $this->sender,
            'To' => $to,
            'Body' => $message,
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_USERPWD => $this->apiKey . ':' . $this->apiSecret,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'sent' => $httpCode >= 200 && $httpCode < 300,
            'provider' => 'twilio',
            'http_code' => $httpCode,
        ];
    }

    private function sendGeneric(string $to, string $message): array
    {
        $data = json_encode([
            'to' => $to,
            'from' => $this->sender,
            'message' => $message,
        ]);

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey,
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'sent' => $httpCode >= 200 && $httpCode < 300,
            'provider' => $this->provider ?: 'generic',
            'http_code' => $httpCode,
        ];
    }
}
