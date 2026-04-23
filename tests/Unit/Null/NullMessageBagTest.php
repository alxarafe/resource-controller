<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Null;

use Alxarafe\ResourceController\Contracts\MessageBagContract;
use Alxarafe\ResourceController\Null\NullMessageBag;
use PHPUnit\Framework\TestCase;

class NullMessageBagTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $bag = new NullMessageBag();
        $this->assertInstanceOf(MessageBagContract::class, $bag);
    }

    public function testSuccessMessage(): void
    {
        $bag = new NullMessageBag();
        $bag->success('Record saved');

        $messages = $bag->getMessages();
        $this->assertCount(1, $messages);
        $this->assertSame('success', $messages[0]['type']);
        $this->assertSame('Record saved', $messages[0]['text']);
    }

    public function testErrorMessage(): void
    {
        $bag = new NullMessageBag();
        $bag->error('Something failed');

        $messages = $bag->getMessages();
        $this->assertCount(1, $messages);
        $this->assertSame('danger', $messages[0]['type']);
        $this->assertSame('Something failed', $messages[0]['text']);
    }

    public function testWarningMessage(): void
    {
        $bag = new NullMessageBag();
        $bag->warning('Be careful');

        $messages = $bag->getMessages();
        $this->assertCount(1, $messages);
        $this->assertSame('warning', $messages[0]['type']);
    }

    public function testGetMessagesClearsQueue(): void
    {
        $bag = new NullMessageBag();
        $bag->success('First');
        $bag->error('Second');

        $first = $bag->getMessages();
        $this->assertCount(2, $first);

        $second = $bag->getMessages();
        $this->assertCount(0, $second);
    }

    public function testMultipleMessages(): void
    {
        $bag = new NullMessageBag();
        $bag->success('OK');
        $bag->warning('Watch out');
        $bag->error('Fail');

        $messages = $bag->getMessages();
        $this->assertCount(3, $messages);
        $this->assertSame('success', $messages[0]['type']);
        $this->assertSame('warning', $messages[1]['type']);
        $this->assertSame('danger', $messages[2]['type']);
    }
}
