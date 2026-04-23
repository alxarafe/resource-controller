<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Component\Container;

use Alxarafe\ResourceController\Component\Container\Panel;
use PHPUnit\Framework\TestCase;

class AbstractContainerTest extends TestCase
{
    /**
     * We test AbstractContainer through Panel (a concrete implementation).
     */
    public function testFilterChildren(): void
    {
        $panel = new Panel('Test', ['keep', 'remove', 'keep_too']);
        $panel->filterChildren(fn($child) => str_starts_with($child, 'keep'));

        $this->assertSame(['keep', 'keep_too'], $panel->getFields());
    }

    public function testJsonSerializeStructure(): void
    {
        $panel = new Panel('Test', ['f1'], ['col' => 'col-4']);
        $json = $panel->jsonSerialize();

        $this->assertArrayHasKey('container', $json);
        $this->assertArrayHasKey('field', $json);
        $this->assertArrayHasKey('label', $json);
        $this->assertArrayHasKey('fields', $json);
        $this->assertArrayHasKey('options', $json);
    }

    public function testGetOptionsReturnsAllOptions(): void
    {
        $panel = new Panel('Test', [], ['col' => 'col-6', 'extra' => true]);
        $options = $panel->getOptions();

        $this->assertSame('col-6', $options['col']);
        $this->assertTrue($options['extra']);
    }

    public function testDefaultColClassFromAbstract(): void
    {
        // Panel overrides getColClass to 'col-md-6', so we test
        // that the options mechanism works
        $panel = new Panel('Test', [], ['col' => 'col-lg-8']);
        $this->assertSame('col-lg-8', $panel->getColClass());
    }
}
