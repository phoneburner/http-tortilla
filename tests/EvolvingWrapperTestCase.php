<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\Http\Message;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

abstract class EvolvingWrapperTestCase extends WrapperTestCase
{
    abstract public static function provideWithMethods(): iterable;

    #[Test]
    #[DataProvider('provideWithMethods')]
    public function withMethodsAreProxied(
        string $method,
        array $args,
        array|null $expected = null,
    ): void {
        // allow expected args to differ from the args we pass the wrapper
        // but if they're not defined, they are the same as what is passed to
        // the wrapper
        if ($expected === null) {
            $expected = $args;
        }

        // with methods return a new instance, so stub one
        $return = self::createStub(static::wrapped());

        // the initial wrapped instance should be called, and return the new
        // stub as it evolves the message
        $this->mock()->$method(...$expected)->willReturn($return)->shouldBeCalled();

        // create a wrapper of the initial instance
        $sut = new (static::fixture())($this->mock()->reveal());

        // evolve the message
        $evolved = $sut->$method(...$args);

        // the returned wrapper should be wrapping the new object
        self::assertSame($return, $evolved->getWrapped());

        // all of our fixtures preserve a new instance of the wrapper as well
        self::assertNotSame($sut, $evolved);
        // and the original wrapper should still wrap the initial instance
        // @phpstan-ignore-next-line
        self::assertSame($this->mock()->reveal(), $sut->getWrapped());
    }
}
