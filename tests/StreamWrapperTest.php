<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\Http\Message;

use PhoneBurner\Tests\Http\Message\Fixture\StreamWrapperFixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\StreamInterface;

final class StreamWrapperTest extends WrapperTestCase
{
    /**
     * @var ObjectProphecy<StreamInterface>
     */
    private ObjectProphecy $mocked_wrapped;

    protected function setUp(): void
    {
        $this->mocked_wrapped = $this->prophesize(StreamInterface::class);
    }

    /**
     * @return ObjectProphecy<StreamInterface>
     */
    protected function mock(): ObjectProphecy
    {
        return $this->mocked_wrapped;
    }

    /**
     * @return class-string<StreamInterface>
     */
    public static function wrapped(): string
    {
        return StreamInterface::class;
    }

    /**
     * @return class-string<StreamWrapperFixture>
     */
    public static function fixture(): string
    {
        return StreamWrapperFixture::class;
    }

    #[Test]
    public function closeIsProxied(): void
    {
        $this->mocked_wrapped->close()->shouldBeCalled();
        $sut = new StreamWrapperFixture($this->mocked_wrapped->reveal());
        $sut->close();
    }

    #[Test]
    public function detachIsProxied(): void
    {
        $this->mocked_wrapped->detach()->shouldBeCalled()->willReturn(null);
        $sut = new StreamWrapperFixture($this->mocked_wrapped->reveal());
        self::assertNull($sut->detach());

        $resource = \fopen('php://temp', 'r');
        self::assertIsResource($resource);
        $this->mocked_wrapped->detach()->shouldBeCalled()->willReturn($resource);
        $sut = new StreamWrapperFixture($this->mocked_wrapped->reveal());
        self::assertSame($resource, $sut->detach());
        \fclose($resource);
    }

    #[Test]
    #[DataProvider('provideSeekArgs')]
    public function seekIsProxied(int $offset, int $whence): void
    {
        $this->mocked_wrapped->seek($offset, $whence)->shouldBeCalled();
        $sut = new StreamWrapperFixture($this->mocked_wrapped->reveal());
        $sut->seek($offset, $whence);
    }

    public static function provideSeekArgs(): iterable
    {
        yield [10, \SEEK_CUR];
        yield [10, \SEEK_END];
        yield [10, \SEEK_SET];
    }

    #[Test]
    public function rewindIsProxied(): void
    {
        $this->mocked_wrapped->rewind()->shouldBeCalled();
        $sut = new StreamWrapperFixture($this->mocked_wrapped->reveal());
        $sut->rewind();
    }

    #[Test]
    public function writeIsProxied(): void
    {
        $this->mocked_wrapped->write('test')->willReturn(10);
        $sut = new StreamWrapperFixture($this->mocked_wrapped->reveal());
        self::assertSame(10, $sut->write('test'));
    }

    public static function provideAllMethods(): iterable
    {
        yield from self::provideGetterMethods();
    }

    public static function provideGetterMethods(): iterable
    {
        yield "getSize (null)" => ['getSize', [], null];
        yield "getSize" => ['getSize', [], 100];
        yield "tell" => ['tell', [], 10];
        yield "eof (true)" => ['eof', [], true];
        yield "eof (false)" => ['eof', [], false];
        yield "isSeekable (false)" => ['isSeekable', [], false];
        yield "isSeekable (true)" => ['isSeekable', [], true];
        yield "isWritable (false)" => ['isWritable', [], false];
        yield "isWritable (true)" => ['isWritable', [], true];
        yield "isReadable (false)" => ['isReadable', [], false];
        yield "isReadable (true)" => ['isReadable', [], true];
        yield "read" => ['read', [10], 'test'];
        yield "getContents" => ['getContents', [], 'content'];
        yield "getMetadata (null)" => ['getMetadata', [null], ['key' => 'value']];
        yield "getMetadata (key)" => ['getMetadata', ['key'], 'value'];
        yield "getMetadata (invalid key)" => ['getMetadata', ['not_key'], null];
        yield "__toString" => ['__toString', [], 'content'];
    }
}
