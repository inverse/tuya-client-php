<?php

namespace Inverse\TuyaClient;

class TokenManager
{
    /**
     * @var Token
     */
    private $token;

    public function getToken(): Token
    {
        return $this->token;
    }

    public function setToken(Token $token): void
    {
        $this->token = $token;
    }

    public function hasToken(): bool
    {
        return $this->token instanceof Token;
    }

    public function isValidToken(): bool
    {
        return time() + $this->token->getExpireTime() > time();
    }
}
