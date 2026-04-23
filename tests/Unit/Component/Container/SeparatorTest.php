<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Component\Container;

use Alxarafe\ResourceController\Component\Container\Separator;
use PHPUnit\Framework\TestCase;

class SeparatorTest extends TestCase
{
    public function testGetContainerType(): void
    {
        $sep = new Separator();
        $this->assertSame('separator', $sep->getContainerType());
    }

    public function testFieldIsSeparator(): void
    {
        $sep = new Separator();
        $this->assertSame('separator', $sep->getField());
    }

    public function testLabelDefaultsToEmpty(): void
    {
        $sep = new Separator();
        $this->assertSame('', $sep->getLabel());
    }

    public function testLabelCanBeSet(): void
    {
        $sep = new Separator('Divider');
        $this->assertSame('Divider', $sep->getLabel());
    }

    public function testDefaultColClass(): void
    {
        $sep = new Separator();
        $this->assertSame('col-12', $sep->getColClass());
    }

    public function testFieldsAreEmpty(): void
    {
        $sep = new Separator();
        $this->assertSame([], $sep->getFields());
    }
}
