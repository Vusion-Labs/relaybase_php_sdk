# Relaybase PHP SDK

Official PHP SDK for the [Relaybase](https://tryrelaybase.com) email validation API.

## Requirements

- PHP >= 8.1
- cURL extension enabled

## Installation

```bash
composer require relaybase/relaybase-php
```

## Usage

### Vanilla PHP

```php
<?php

require 'vendor/autoload.php';

use Relaybase\Relaybase;
use Relaybase\EmailMode;
use Relaybase\RelaybaseAPIError;

$client = new Relaybase('rb_key_your_api_key_here');

try {
    $result = $client->verifySingle('test@example.com', EmailMode::Fast);

    echo $result->status;      // valid, invalid, risky
    echo $result->score;       // 0-100
    echo $result->reason;      // human readable reason
    echo $result->suggestion;  // suggested action
} catch (RelaybaseAPIError $e) {
    echo $e->getMessage();
    echo $e->getStatusCode();
}
```

### Laravel

```php
<?php

use Relaybase\Relaybase;
use Relaybase\EmailMode;
use Relaybase\RelaybaseAPIError;

$client = new Relaybase(config('services.relaybase.key'));

try {
    $result = $client->verifySingle($request->email, EmailMode::Deep);

    return response()->json($result->toArray());
} catch (RelaybaseAPIError $e) {
    return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
}
```

## Modes

| Mode | Description |
|------|-------------|
| `EmailMode::Fast` | Quick syntax and domain check (default) |
| `EmailMode::Medium` | Includes MX record validation |
| `EmailMode::Deep` | Full SMTP verification |

## Response

| Field | Type | Description |
|-------|------|-------------|
| `status` | string | `valid`, `invalid`, or `risky` |
| `score` | int | Confidence score 0–100 |
| `isDisposable` | bool | Whether the address is disposable |
| `isFree` | bool | Whether it uses a free provider |
| `isRoleBased` | bool | Whether it is a role address |
| `mxValid` | bool | Whether the domain has valid MX records |
| `syntaxValid` | bool | Whether the email syntax is correct |
| `catchAll` | bool | Whether the domain accepts all addresses |
| `smtpCode` | int | SMTP response code |
| `reason` | string | Reason for the result |
| `suggestion` | string | Suggested action |
| `checkedAt` | string | Timestamp of the check |

## License

MIT
