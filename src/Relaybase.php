<?php

namespace Relaybase;

class Relaybase
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct(string $apiKey, string $baseUrl = 'https://api.tryrelaybase.com/v1')
    {
        if (empty($apiKey)) {
            throw new \InvalidArgumentException('API key cannot be empty');
        }

        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function verifySingle(string $email, EmailMode $mode = EmailMode::Fast): EmailVerifyResult
    {
        if (empty($email)) {
            throw new \InvalidArgumentException('Email cannot be empty');
        }

        $url = "{$this->baseUrl}/email/single-validate";

        $body = json_encode([
            'email' => $email,
            'mode'  => $mode->value,
        ]);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => [
                'X-RB-Key: ' . $this->apiKey,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new RelaybaseAPIError(0, "Request failed: {$curlError}");
        }

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RelaybaseAPIError($statusCode, 'Invalid JSON response from API');
        }

        if ($statusCode >= 400 || !($decoded['success'] ?? false)) {
            $message = $decoded['message'] ?? 'Unknown error';
            throw new RelaybaseAPIError($statusCode, $message);
        }

        return EmailVerifyResult::fromArray($decoded['data']);
    }
}
