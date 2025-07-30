<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\Http\Message;

use PhoneBurner\Tests\Http\Message\DataProvider\MessageDataProvider;
use PhoneBurner\Tests\Http\Message\Fixture\MessageWrapperFixture;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\MessageInterface;

final class MessageWrapperTest extends EvolvingWrapperTestCase
{
    use MessageDataProvider;

    /**
     * @var ObjectProphecy<MessageInterface>
     */
    private ObjectProphecy $mocked_wrapped;

    protected function setUp(): void
    {
        $this->mocked_wrapped = $this->prophesize(MessageInterface::class);
    }

    /**
     * @return ObjectProphecy<MessageInterface>
     */
    protected function mock(): ObjectProphecy
    {
        return $this->mocked_wrapped;
    }

    /**
     * @return class-string<MessageInterface>
     */
    public static function wrapped(): string
    {
        return MessageInterface::class;
    }

    /**
     * @return class-string<MessageWrapperFixture>
     */
    public static function fixture(): string
    {
        return MessageWrapperFixture::class;
    }
}
