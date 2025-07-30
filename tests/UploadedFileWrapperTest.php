<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\Http\Message;

use PhoneBurner\Tests\Http\Message\Fixture\UploadedFileWrapperFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

final class UploadedFileWrapperTest extends WrapperTestCase
{
    /**
     * @var ObjectProphecy<UploadedFileInterface>
     */
    private ObjectProphecy $mocked_wrapped;

    protected function setUp(): void
    {
        $this->mocked_wrapped = $this->prophesize(UploadedFileInterface::class);
    }

    /**
     * @return ObjectProphecy<UploadedFileInterface>
     */
    protected function mock(): ObjectProphecy
    {
        return $this->mocked_wrapped;
    }

    /**
     * @return class-string<UploadedFileInterface>
     */
    public static function wrapped(): string
    {
        return UploadedFileInterface::class;
    }

    /**
     * @return class-string<UploadedFileWrapperFixture>
     */
    public static function fixture(): string
    {
        return UploadedFileWrapperFixture::class;
    }

    #[Test]
    public function moveToRequiresWrappedClass(): void
    {
        $sut = new UploadedFileWrapperFixture();
        $this->expectException(\UnexpectedValueException::class);
        $sut->moveTo('path');
    }

    #[Test]
    public function moveToPassesTargetToWrappedClass(): void
    {
        $this->mocked_wrapped->moveTo('path')->shouldBeCalled();

        $sut = new UploadedFileWrapperFixture($this->mocked_wrapped->reveal());
        $sut->moveTo('path');
    }

    #[Test]
    #[TestWith([\InvalidArgumentException::class, \RuntimeException::class])]
    public function moveToCatchesNoExceptions(
        string $class,
        mixed $unused = null,
    ): void {
        \assert(\is_a($class, \Throwable::class, true));
        $this->mocked_wrapped->moveTo('path')->willThrow(new $class());

        $sut = new UploadedFileWrapperFixture($this->mocked_wrapped->reveal());
        $this->expectException($class);
        $sut->moveTo('path');
    }

    public static function provideAllMethods(): iterable
    {
        yield from self::provideGetterMethods();
    }

    public static function provideGetterMethods(): iterable
    {
        yield "getStream()" => ['getStream', [], self::createStub(StreamInterface::class)];

        yield "getSize() => 1" => ['getSize', [], 1];
        yield "getSize() => null" => ['getSize', [], null];

        yield "getError()" => ['getError', [], 1];

        yield "getClientFilename() => 'filename'" => ['getClientFilename', [], 'filename'];
        yield "getClientFilename() => null" => ['getClientFilename', [], null];

        yield "getClientMediaType() => 'type'" => ['getClientMediaType', [], 'type'];
        yield "getClientMediaType() => null" => ['getClientMediaType', [], null];
    }
}
