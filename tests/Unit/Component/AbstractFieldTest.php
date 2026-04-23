<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Component;

use Alxarafe\ResourceController\Component\Enum\ActionPosition;
use Alxarafe\ResourceController\Component\Fields\Text;
use Alxarafe\ResourceController\Component\Fields\Boolean;
use Alxarafe\ResourceController\Component\Fields\Date;
use Alxarafe\ResourceController\Component\Fields\DateTime;
use Alxarafe\ResourceController\Component\Fields\Decimal;
use Alxarafe\ResourceController\Component\Fields\Hidden;
use Alxarafe\ResourceController\Component\Fields\Icon;
use Alxarafe\ResourceController\Component\Fields\Integer;
use Alxarafe\ResourceController\Component\Fields\Textarea;
use Alxarafe\ResourceController\Component\Fields\Time;
use PHPUnit\Framework\TestCase;

/**
 * Tests for AbstractField behavior through concrete simple field types.
 */
class AbstractFieldTest extends TestCase
{
    public function testGetField(): void
    {
        $field = new Text('name', 'Name');
        $this->assertSame('name', $field->getField());
    }

    public function testGetLabel(): void
    {
        $field = new Text('name', 'Full Name');
        $this->assertSame('Full Name', $field->getLabel());
    }

    public function testGetComponent(): void
    {
        $field = new Text('name', 'Name');
        $this->assertSame('text', $field->getComponent());
    }

    public function testDefaultColClass(): void
    {
        $field = new Text('name', 'Name');
        $this->assertSame('col-12', $field->getColClass());
    }

    public function testColClassFromNestedOptions(): void
    {
        $field = new Text('name', 'Name', ['col' => 'col-6']);
        $this->assertSame('col-6', $field->getColClass());
    }

    public function testOptionsWrappedUnderOptionsKey(): void
    {
        $field = new Text('name', 'Name', ['required' => true]);
        $options = $field->getOptions();

        // When options don't contain an 'options' key, they get wrapped
        $this->assertArrayHasKey('options', $options);
        $this->assertTrue($options['options']['required']);
    }

    public function testOptionsWithExplicitOptionsKey(): void
    {
        $field = new Text('name', 'Name', ['options' => ['required' => true]]);
        $options = $field->getOptions();
        $this->assertTrue($options['options']['required']);
    }

    public function testVisibilityDefaultsToTrue(): void
    {
        $field = new Text('name', 'Name');
        $this->assertTrue($field->isVisible());
    }

    public function testSetVisibilityCallback(): void
    {
        $field = new Text('name', 'Name');
        $field->setVisibility(fn() => false);
        $this->assertFalse($field->isVisible());
    }

    public function testSetVisibilityReturnsSelf(): void
    {
        $field = new Text('name', 'Name');
        $result = $field->setVisibility(fn() => true);
        $this->assertSame($field, $result);
    }

    public function testAddAction(): void
    {
        $field = new Text('name', 'Name');
        $result = $field->addAction('fas fa-search', "alert('hi')", 'Search');

        $this->assertSame($field, $result);
        $actions = $field->getActions();
        $this->assertCount(1, $actions);
        $this->assertSame('fas fa-search', $actions[0]['icon']);
        $this->assertSame("alert('hi')", $actions[0]['onclick']);
        $this->assertSame('Search', $actions[0]['title']);
        $this->assertSame('btn-outline-secondary', $actions[0]['class']);
        $this->assertSame('left', $actions[0]['position']);
    }

    public function testAddActionWithRightPosition(): void
    {
        $field = new Text('name', 'Name');
        $field->addAction('fas fa-eye', 'view()', 'View', 'btn-primary', ActionPosition::Right);

        $actions = $field->getActions();
        $this->assertSame('right', $actions[0]['position']);
        $this->assertSame('btn-primary', $actions[0]['class']);
    }

    public function testClearActions(): void
    {
        $field = new Text('name', 'Name');
        $field->addAction('fas fa-search', 'search()');
        $field->addAction('fas fa-eye', 'view()');
        $this->assertCount(2, $field->getActions());

        $result = $field->clearActions();
        $this->assertSame($field, $result);
        $this->assertCount(0, $field->getActions());
    }

    public function testMergeOptions(): void
    {
        $field = new Text('name', 'Name', ['required' => true]);
        $field->mergeOptions(['placeholder' => 'Enter name']);

        $options = $field->getOptions();
        $this->assertTrue($options['options']['required']);
        $this->assertSame('Enter name', $options['options']['placeholder']);
    }

    public function testJsonSerializeStructure(): void
    {
        $field = new Text('name', 'Name');
        $json = $field->jsonSerialize();

        $this->assertSame('name', $json['field']);
        $this->assertSame('name', $json['name']);
        $this->assertSame('Name', $json['label']);
        $this->assertSame('text', $json['component']);
        $this->assertSame('text', $json['type']);
        $this->assertSame([], $json['actions']);
    }

    /**
     * @dataProvider fieldTypeProvider
     */
    public function testFieldTypesReturnCorrectType(string $class, string $expectedType): void
    {
        $field = new $class('test', 'Test');
        $this->assertSame($expectedType, $field->getType());
        $this->assertSame($expectedType, $field->getComponent());
    }

    /**
     * @return array<string, array{0: class-string, 1: string}>
     */
    public static function fieldTypeProvider(): array
    {
        return [
            'text'     => [Text::class, 'text'],
            'boolean'  => [Boolean::class, 'boolean'],
            'date'     => [Date::class, 'date'],
            'datetime' => [DateTime::class, 'datetime'],
            'decimal'  => [Decimal::class, 'decimal'],
            'hidden'   => [Hidden::class, 'hidden'],
            'icon'     => [Icon::class, 'icon'],
            'integer'  => [Integer::class, 'integer'],
            'textarea' => [Textarea::class, 'textarea'],
            'time'     => [Time::class, 'time'],
        ];
    }
}
