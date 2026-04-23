<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Component\Container;

use Alxarafe\ResourceController\Component\Container\TabGroup;
use Alxarafe\ResourceController\Component\Container\Tab;
use PHPUnit\Framework\TestCase;

class TabGroupTest extends TestCase
{
    public function testGetContainerType(): void
    {
        $group = new TabGroup([]);
        $this->assertSame('tab_group', $group->getContainerType());
    }

    public function testFieldIsTabGroup(): void
    {
        $group = new TabGroup([]);
        $this->assertSame('tab_group', $group->getField());
    }

    public function testTabsAreStoredAsFields(): void
    {
        $tab1 = new Tab('one', 'One');
        $tab2 = new Tab('two', 'Two');
        $group = new TabGroup([$tab1, $tab2]);

        $fields = $group->getFields();
        $this->assertCount(2, $fields);
        $this->assertSame($tab1, $fields[0]);
        $this->assertSame($tab2, $fields[1]);
    }

    public function testOptionsAreStored(): void
    {
        $group = new TabGroup([], ['id' => 'main-tabs']);
        $this->assertSame(['id' => 'main-tabs'], $group->getOptions());
    }

    public function testJsonSerialize(): void
    {
        $tab = new Tab('info', 'Info');
        $group = new TabGroup([$tab]);
        $json = $group->jsonSerialize();

        $this->assertSame('tab_group', $json['container']);
        $this->assertCount(1, $json['fields']);
    }
}
