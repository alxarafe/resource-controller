<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Component\Container;

use Alxarafe\ResourceController\Component\Container\Tab;
use PHPUnit\Framework\TestCase;

class TabTest extends TestCase
{
    public function testConstructorSetsIdWithPrefix(): void
    {
        $tab = new Tab('general', 'General');
        $this->assertSame('tab_general', $tab->getTabId());
        $this->assertSame('tab_general', $tab->getField());
    }

    public function testConstructorSetsLabel(): void
    {
        $tab = new Tab('info', 'Information');
        $this->assertSame('Information', $tab->getLabel());
    }

    public function testConstructorStoresIconInOptions(): void
    {
        $tab = new Tab('info', 'Info', 'fas fa-info');
        $this->assertSame('fas fa-info', $tab->getIcon());
    }

    public function testIconDefaultsToEmptyString(): void
    {
        $tab = new Tab('info', 'Info');
        $this->assertSame('', $tab->getIcon());
    }

    public function testOptionsAreMergedWithIcon(): void
    {
        $tab = new Tab('orders', 'Orders', 'fas fa-list', [], ['url' => '/orders']);
        $options = $tab->getOptions();

        $this->assertSame('/orders', $options['url']);
        $this->assertSame('fas fa-list', $options['icon']);
    }

    public function testGetUrlReturnsOptionValue(): void
    {
        $tab = new Tab('orders', 'Orders', '', [], ['url' => '/orders']);
        $this->assertSame('/orders', $tab->getUrl());
    }

    public function testGetUrlReturnsEmptyStringWhenMissing(): void
    {
        $tab = new Tab('orders', 'Orders');
        $this->assertSame('', $tab->getUrl());
    }

    public function testGetContainerType(): void
    {
        $tab = new Tab('test', 'Test');
        $this->assertSame('tab', $tab->getContainerType());
    }

    public function testBadgeCountDefaultsToNull(): void
    {
        $tab = new Tab('test', 'Test');
        $this->assertNull($tab->getBadgeCount());
    }

    public function testSetAndGetBadgeCount(): void
    {
        $tab = new Tab('test', 'Test');
        $tab->setBadgeCount(5);
        $this->assertSame(5, $tab->getBadgeCount());
    }

    public function testBadgeCountCanBeResetToNull(): void
    {
        $tab = new Tab('test', 'Test');
        $tab->setBadgeCount(3);
        $tab->setBadgeCount(null);
        $this->assertNull($tab->getBadgeCount());
    }

    public function testGetBadgeClassDefault(): void
    {
        $tab = new Tab('test', 'Test');
        $this->assertSame('badge bg-danger ms-2', $tab->getBadgeClass());
    }

    public function testGetBadgeClassFromOptions(): void
    {
        $tab = new Tab('test', 'Test', '', [], ['badge_class' => 'badge bg-primary']);
        $this->assertSame('badge bg-primary', $tab->getBadgeClass());
    }

    public function testJsonSerializeIncludesBadge(): void
    {
        $tab = new Tab('info', 'Information', 'fas fa-info');
        $tab->setBadgeCount(10);
        $json = $tab->jsonSerialize();

        $this->assertSame('tab', $json['container']);
        $this->assertSame('tab_info', $json['field']);
        $this->assertSame('Information', $json['label']);
        $this->assertSame(10, $json['badge']);
    }

    public function testJsonSerializeBadgeIsNullWhenNotSet(): void
    {
        $tab = new Tab('info', 'Info');
        $json = $tab->jsonSerialize();
        $this->assertNull($json['badge']);
    }

    public function testFieldsArePassedThrough(): void
    {
        $fields = ['field_a', 'field_b'];
        $tab = new Tab('test', 'Test', '', $fields);
        $this->assertSame($fields, $tab->getFields());
    }

    public function testIconOverridesOptionIcon(): void
    {
        // The constructor always sets 'icon' after spreading options,
        // so the explicit icon parameter wins.
        $tab = new Tab('test', 'Test', 'fas fa-home', [], ['icon' => 'fas fa-star']);
        $this->assertSame('fas fa-home', $tab->getIcon());
    }
}
