<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Result;

use Alxarafe\ResourceController\Result\PaginatedResult;
use PHPUnit\Framework\TestCase;

class PaginatedResultTest extends TestCase
{
    public function testConstructorSetsAllProperties(): void
    {
        $items = [
            ['id' => 1, 'name' => 'Product A'],
            ['id' => 2, 'name' => 'Product B'],
        ];
        $result = new PaginatedResult($items, 50, 10, 0);

        $this->assertSame($items, $result->items);
        $this->assertSame(50, $result->total);
        $this->assertSame(10, $result->limit);
        $this->assertSame(0, $result->offset);
    }

    public function testEmptyResult(): void
    {
        $result = new PaginatedResult([], 0, 25, 0);

        $this->assertSame([], $result->items);
        $this->assertSame(0, $result->total);
    }

    public function testPropertiesAreReadonly(): void
    {
        $result = new PaginatedResult([['id' => 1]], 1, 10, 0);

        // Ensure readonly properties exist and are accessible
        $this->assertIsArray($result->items);
        $this->assertIsInt($result->total);
        $this->assertIsInt($result->limit);
        $this->assertIsInt($result->offset);
    }

    public function testWithOffset(): void
    {
        $result = new PaginatedResult([['id' => 3]], 100, 10, 20);

        $this->assertSame(20, $result->offset);
        $this->assertSame(10, $result->limit);
    }
}
