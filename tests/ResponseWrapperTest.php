<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\Http\Message;

use PhoneBurner\Tests\Http\Message\DataProvider\ResponseDataProvider;
use PhoneBurner\Tests\Http\Message\Fixture\ResponseWrapperFixture;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;

final class ResponseWrapperTest extends EvolvingWrapperTestCase
{
    use ResponseDataProvider;

    /**
     * @var ObjectProphecy<ResponseInterface>
     */
    private ObjectProphecy $mocked_wrapped;

    protected function setUp(): void
    {
        $this->mocked_wrapped = $this->prophesize(ResponseInterface::class);
    }

    /**
     * @return ObjectProphecy<ResponseInterface>
     */
    protected function mock(): ObjectProphecy
    {
        return $this->mocked_wrapped;
    }

    /**
     * @return class-string<ResponseInterface>
     */
    public static function wrapped(): string
    {
        return ResponseInterface::class;
    }

    /**
     * @return class-string<ResponseWrapperFixture>
     */
    public static function fixture(): string
    {
        return ResponseWrapperFixture::class;
    }
}
