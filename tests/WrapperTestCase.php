<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\Http\Message;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

abstract class WrapperTestCase extends TestCase
{
    use ProphecyTrait;

    /**
     * @return class-string
     */
    abstract public static function wrapped(): string;

    /**
     * @return class-string
     */
    abstract public static function fixture(): string;

    /**
     * @return ObjectProphecy<MessageInterface|RequestInterface|ServerRequestInterface|ResponseInterface|UriInterface|StreamInterface|UploadedFileInterface>
     */
    abstract protected function mock(): ObjectProphecy;

    abstract public static function provideAllMethods(): iterable;

    abstract public static function provideGetterMethods(): iterable;

    #[Test]
    #[DataProvider('provideAllMethods')]
    public function proxiedMethodsRequireWrappedClass(
        string $method,
        array $args,
        mixed $unused0 = null,
        mixed $unused1 = null,
    ): void {
        $sut = new (static::fixture())();
        $this->expectException(\UnexpectedValueException::class);
        $sut->$method(...$args);
    }

    #[Test]
    #[DataProvider('provideGetterMethods')]
    public function getterMethodsAreProxied(
        string $method,
        array $args,
        mixed $return,
        array|null $expected = null,
    ): void {
        // allow expected args to differ from the args we pass the wrapper
        // but if they're not defined, they are the same as what is passed to
        // the wrapper
        $expected ??= $args;

        $this->mock()->$method(...$expected)->willReturn($return);
        $sut = new (static::fixture())(
            wrapped: $this->mock()->reveal(),
        );
        self::assertSame($return, $sut->$method(...$args));
    }

    #[Test]
    public function setWrappedFactoryAcceptsCallable(): void
    {
        $one_time = function () {
            static $called = false;
            if ($called) {
                throw new \RuntimeException('Factory callable can only be called once.');
            }

            $called = true;
            return $this->mock()->reveal();
        };

        $sut = new (static::fixture())(
            factory: $one_time,
        );

        // @phpstan-ignore-next-line
        self::assertSame($this->mock()->reveal(), $sut->getWrapped());
        // @phpstan-ignore-next-line
        self::assertSame($this->mock()->reveal(), $sut->getWrapped());
    }
}
