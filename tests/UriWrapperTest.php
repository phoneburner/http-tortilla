<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\Http\Message;

use PhoneBurner\Tests\Http\Message\Fixture\UriWrapperFixture;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\UriInterface;

final class UriWrapperTest extends EvolvingWrapperTestCase
{
    /**
     * @var ObjectProphecy<UriInterface>
     */
    private ObjectProphecy $mocked_wrapped;

    protected function setUp(): void
    {
        $this->mocked_wrapped = $this->prophesize(UriInterface::class);
    }

    /**
     * @return ObjectProphecy<UriInterface>
     */
    protected function mock(): ObjectProphecy
    {
        return $this->mocked_wrapped;
    }

    /**
     * @return class-string<UriInterface>
     */
    public static function wrapped(): string
    {
        return UriInterface::class;
    }

    /**
     * @return class-string<UriWrapperFixture>
     */
    public static function fixture(): string
    {
        return UriWrapperFixture::class;
    }

    public static function provideAllMethods(): iterable
    {
        yield from self::provideWithMethods();
        yield from self::provideGetterMethods();
    }

    public static function provideGetterMethods(): iterable
    {
        yield "getScheme" => ['getScheme', [], 'http'];
        yield "getAuthority (none)" => ['getAuthority', [], ''];
        yield "getAuthority" => ['getAuthority', [], 'user:info@host:80'];
        yield "getUserInfo" => ['getUserInfo', [], 'user:info'];
        yield "getHost" => ['getHost', [], 'host'];
        yield "getPort" => ['getPort', [], 80];
        yield "getPort (null)" => ['getPort', [], null];
        yield "getPath" => ['getPath', [], '/path'];
        yield "getQuery" => ['getQuery', [], 'query'];
        yield "getFragment" => ['getFragment', [], 'fragment'];
        yield "__toString" => ['__toString', [], 'http://example.com/test'];
    }

    public static function provideWithMethods(): iterable
    {
        yield "withScheme" => ['withScheme', ['https']];
        yield "withUserInfo (user only)" => ['withUserInfo', ['nopass', null]];
        yield "withUserInfo (user and password)" => ['withUserInfo', ['with', 'pass']];
        yield "withHost" => ['withHost', ['example.com']];
        yield "withPort" => ['withPort', [8080]];
        yield "withPort (null)" => ['withPort', [null]];
        yield "withPath" => ['withPath', ['path']];
        yield "withQuery" => ['withQuery', ['query']];
        yield "withFragment" => ['withFragment', ['fragment']];
    }
}
