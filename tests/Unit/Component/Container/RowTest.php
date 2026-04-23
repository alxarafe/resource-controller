<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Component\Container;

use Alxarafe\ResourceController\Component\Container\Row;
use PHPUnit\Framework\TestCase;

class RowTest extends TestCase
{
    public function testGetContainerType(): void
    {
        $row = new Row();
        $this->assertSame('row', $row->getContainerType());
    }

    public function testFieldIsRow(): void
    {
        $row = new Row();
        $this->assertSame('row', $row->getField());
    }

    public function testLabelIsEmpty(): void
    {
        $row = new Row();
        $this->assertSame('', $row->getLabel());
    }

    public function testDefaultColClass(): void
    {
        $row = new Row();
        $this->assertSame('col-12', $row->getColClass());
    }

    public function testCustomColClass(): void
    {
        $row = new Row([], ['col' => 'col-6']);
        $this->assertSame('col-6', $row->getColClass());
    }

    public function testFieldsAreStored(): void
    {
        $row = new Row(['a', 'b', 'c']);
        $this->assertSame(['a', 'b', 'c'], $row->getFields());
    }
}
