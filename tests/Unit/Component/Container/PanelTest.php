<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Component\Container;

use Alxarafe\ResourceController\Component\Container\Panel;
use PHPUnit\Framework\TestCase;

class PanelTest extends TestCase
{
    public function testConstructorGeneratesFieldFromLabel(): void
    {
        $panel = new Panel('General Info');
        $this->assertSame('panel_general_info', $panel->getField());
    }

    public function testGetContainerType(): void
    {
        $panel = new Panel('Test');
        $this->assertSame('panel', $panel->getContainerType());
    }

    public function testGetLabel(): void
    {
        $panel = new Panel('My Panel');
        $this->assertSame('My Panel', $panel->getLabel());
    }

    public function testDefaultColClass(): void
    {
        $panel = new Panel('Test');
        $this->assertSame('col-md-6', $panel->getColClass());
    }

    public function testCustomColClass(): void
    {
        $panel = new Panel('Test', [], ['col' => 'col-lg-4']);
        $this->assertSame('col-lg-4', $panel->getColClass());
    }

    public function testFieldsAreStored(): void
    {
        $fields = ['field_a', 'field_b'];
        $panel = new Panel('Test', $fields);
        $this->assertSame($fields, $panel->getFields());
    }

    public function testJsonSerialize(): void
    {
        $panel = new Panel('Details', ['f1']);
        $json = $panel->jsonSerialize();

        $this->assertSame('panel', $json['container']);
        $this->assertSame('Details', $json['label']);
        $this->assertSame(['f1'], $json['fields']);
    }

    public function testSpecialCharactersInLabelBecomeUnderscores(): void
    {
        $panel = new Panel('Hello (World)!');
        $this->assertSame('panel_hello__world__', $panel->getField());
    }
}
