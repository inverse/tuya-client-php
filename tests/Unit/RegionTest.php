<?php

namespace Tests\Unit\Inverse\TuyaClient;

use Inverse\TuyaClient\Region;
use Inverse\TuyaClient\Token;
use PHPUnit\Framework\TestCase;

class RegionTest extends TestCase
{
    /**
     * @param Token $token
     * @dataProvider fromAccessTokenProvider
     */
    public function testFromAccessToken(Token $token)
    {
        $region = Region::fromAccessToken($token);
        $this->assertInstanceOf(Region::class, $region);
    }

    public function fromAccessTokenProvider(): array
    {
        return [
            'EU' => [
                new Token('EU123456', '', 0),
            ],
            'China' => [
                new Token('AY123456', '', 0),
            ],
        ];
    }

    /**
     * @param Token $token
     * @dataProvider fromAccessTokenProviderInvalid
     */
    public function testFromAccessTokenInvalid(Token $token)
    {
        $this->expectException(\InvalidArgumentException::class);
        Region::fromAccessToken($token);
    }

    public function fromAccessTokenProviderInvalid(): array
    {
        return [
            'Empty' => [
                new Token('', '', 0),
            ],
            'Unmapped' => [
                new Token('DJHFHD3847', '', 0),
            ],
        ];
    }
}
