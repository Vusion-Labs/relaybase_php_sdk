<?php

namespace Relaybase\Tests;

use PHPUnit\Framework\TestCase;
use Relaybase\Relaybase;
use Relaybase\EmailMode;
use Relaybase\EmailVerifyResult;
use Relaybase\RelaybaseAPIError;

class RelaybaseTest extends TestCase
{
    public function testConstructorThrowsOnEmptyApiKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Relaybase('');
    }

    public function testEmailModeValues(): void
    {
        $this->assertEquals('fast', EmailMode::Fast->value);
        $this->assertEquals('medium', EmailMode::Medium->value);
        $this->assertEquals('deep', EmailMode::Deep->value);
    }

    public function testRelaybaseAPIErrorHasStatusCode(): void
    {
        $error = new RelaybaseAPIError(401, 'invalid api key');
        $this->assertEquals(401, $error->getStatusCode());
        $this->assertEquals('invalid api key', $error->getErrorMessage());
    }

    public function testEmailVerifyResultFromArray(): void
    {
        $data = [
            'result'        => 'test@gmail.com',
            'status'        => 'valid',
            'score'         => 85,
            'is_valid'      => true,
            'is_disposable' => false,
            'is_free'       => true,
            'is_role_based' => false,
            'mx_valid'      => true,
            'syntax_valid'  => true,
            'catch_all'     => false,
            'smtp_code'     => 250,
            'reason'        => 'Mailbox verified via SMTP',
            'suggestion'    => 'Email appears fully deliverable',
            'checked_at'    => null,
        ];

        $result = EmailVerifyResult::fromArray($data);

        $this->assertEquals('test@gmail.com', $result->result);
        $this->assertEquals('valid', $result->status);
        $this->assertEquals(85, $result->score);
        $this->assertTrue($result->isValid);
        $this->assertFalse($result->isDisposable);
        $this->assertTrue($result->isFree);
        $this->assertTrue($result->mxValid);
        $this->assertTrue($result->syntaxValid);
    }

    public function testEmailVerifyResultToArray(): void
    {
        $data = [
            'result'        => 'test@gmail.com',
            'status'        => 'valid',
            'score'         => 85,
            'is_valid'      => true,
            'is_disposable' => false,
            'is_free'       => true,
            'is_role_based' => false,
            'mx_valid'      => true,
            'syntax_valid'  => true,
            'catch_all'     => false,
            'smtp_code'     => 250,
            'reason'        => 'Mailbox verified via SMTP',
            'suggestion'    => 'Email appears fully deliverable',
            'checked_at'    => null,
        ];

        $result = EmailVerifyResult::fromArray($data);
        $array = $result->toArray();

        $this->assertEquals('valid', $array['status']);
        $this->assertEquals(85, $array['score']);
        $this->assertTrue($array['is_valid']);
    }

    public function testVerifySingleThrowsOnEmptyEmail(): void
    {
        $client = new Relaybase('test_key', 'https://api.tryrelaybase.com/v1');
        $this->expectException(\InvalidArgumentException::class);
        $client->verifySingle('');
    }
}
