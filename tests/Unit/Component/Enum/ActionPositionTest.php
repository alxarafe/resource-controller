<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Component\Enum;

use Alxarafe\ResourceController\Component\Enum\ActionPosition;
use PHPUnit\Framework\TestCase;

class ActionPositionTest extends TestCase
{
    public function testLeftValue(): void
    {
        $this->assertSame('left', ActionPosition::Left->value);
    }

    public function testRightValue(): void
    {
        $this->assertSame('right', ActionPosition::Right->value);
    }

    public function testFromString(): void
    {
        $this->assertSame(ActionPosition::Left, ActionPosition::from('left'));
        $this->assertSame(ActionPosition::Right, ActionPosition::from('right'));
    }

    public function testCasesCount(): void
    {
        $this->assertCount(2, ActionPosition::cases());
    }
}
