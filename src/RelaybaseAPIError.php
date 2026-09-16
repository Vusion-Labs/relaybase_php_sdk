<?php

namespace Relaybase;

class RelaybaseAPIError extends \Exception
{
    private int $statusCode;
    private string $errorMessage;

    public function __construct(int $statusCode, string $errorMessage)
    {
        $this->statusCode = $statusCode;
        $this->errorMessage = $errorMessage;
        parent::__construct("Relaybase API Error [{$statusCode}]: {$errorMessage}");
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
