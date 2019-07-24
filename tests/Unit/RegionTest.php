<?php

namespace Tests\Unit\Inverse\TuyaClient;

use Inverse\TuyaClient\Region;
use Inverse\TuyaClient\Token;
use PHPUnit\Framework\TestCase;

class RegionTest extends TestCase
{
    /**
     * @param Token  $token
     * @param Region $expected
     * @dataProvider fromAccessTokenProvider
     */
    public function testFromAccessToken(Token $token, Region $expected): void
    {
        $this->assertEquals($expected, Region::fromAccessToken($token));
    }

    public function fromAccessTokenProvider(): array
    {
        return [
            'EU' => [
                new Token('EU123456', '', 0),
                Region::createEU(),
            ],
            'China' => [
                new Token('AY123456', '', 0),
                Region::createCN(),
            ],
            'Empty' => [
                new Token('', '', 0),
                Region::createUS(),
            ],
            'Unmapped' => [
                new Token('DJHFHD3847', '', 0),
                Region::createUS(),
            ],
        ];
    }
}
