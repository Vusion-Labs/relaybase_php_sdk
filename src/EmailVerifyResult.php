<?php

namespace Relaybase;

class EmailVerifyResult
{
    public function __construct(
        public readonly string $result,
        public readonly string $status,
        public readonly int $score,
        public readonly bool $isValid,
        public readonly bool $isDisposable,
        public readonly bool $isFree,
        public readonly bool $isRoleBased,
        public readonly bool $mxValid,
        public readonly bool $syntaxValid,
        public readonly bool $catchAll,
        public readonly int $smtpCode,
        public readonly string $reason,
        public readonly string $suggestion,
        public readonly ?string $checkedAt = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            result: $data['result'],
            status: $data['status'],
            score: $data['score'],
            isValid: $data['is_valid'],
            isDisposable: $data['is_disposable'],
            isFree: $data['is_free'],
            isRoleBased: $data['is_role_based'],
            mxValid: $data['mx_valid'],
            syntaxValid: $data['syntax_valid'],
            catchAll: $data['catch_all'],
            smtpCode: $data['smtp_code'],
            reason: $data['reason'],
            suggestion: $data['suggestion'],
            checkedAt: $data['checked_at'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'result'       => $this->result,
            'status'       => $this->status,
            'score'        => $this->score,
            'is_valid'     => $this->isValid,
            'is_disposable'=> $this->isDisposable,
            'is_free'      => $this->isFree,
            'is_role_based'=> $this->isRoleBased,
            'mx_valid'     => $this->mxValid,
            'syntax_valid' => $this->syntaxValid,
            'catch_all'    => $this->catchAll,
            'smtp_code'    => $this->smtpCode,
            'reason'       => $this->reason,
            'suggestion'   => $this->suggestion,
            'checked_at'   => $this->checkedAt,
        ];
    }
}
