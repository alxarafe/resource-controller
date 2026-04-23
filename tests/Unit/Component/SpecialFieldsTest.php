<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Component;

use Alxarafe\ResourceController\Component\Fields\Select;
use Alxarafe\ResourceController\Component\Fields\Select2;
use Alxarafe\ResourceController\Component\Fields\Image;
use Alxarafe\ResourceController\Component\Fields\StaticText;
use Alxarafe\ResourceController\Component\Fields\RelationList;
use PHPUnit\Framework\TestCase;

/**
 * Tests for field types with custom constructors.
 */
class SpecialFieldsTest extends TestCase
{
    // ── Select ────────────────────────────────────────────────

    public function testSelectStoresChoicesInOptions(): void
    {
        $choices = ['active' => 'Active', 'inactive' => 'Inactive'];
        $field = new Select('status', 'Status', $choices);

        $options = $field->getOptions();
        $this->assertSame($choices, $options['options']['values']);
    }

    public function testSelectGetType(): void
    {
        $field = new Select('status', 'Status');
        $this->assertSame('select', $field->getType());
    }

    // ── Select2 ───────────────────────────────────────────────

    public function testSelect2StoresChoicesInOptions(): void
    {
        $choices = [1 => 'Option A', 2 => 'Option B'];
        $field = new Select2('category', 'Category', $choices);

        $options = $field->getOptions();
        $this->assertSame($choices, $options['options']['values']);
    }

    public function testSelect2AddsSelect2CssClass(): void
    {
        $field = new Select2('category', 'Category');
        $options = $field->getOptions();
        $this->assertSame('select2', $options['options']['class']);
    }

    public function testSelect2AppendsToExistingCssClass(): void
    {
        $field = new Select2('category', 'Category', [], ['class' => 'form-select']);
        $options = $field->getOptions();
        $this->assertSame('form-select select2', $options['options']['class']);
    }

    public function testSelect2GetType(): void
    {
        $field = new Select2('category', 'Category');
        $this->assertSame('select2', $field->getType());
    }

    // ── Image ─────────────────────────────────────────────────

    public function testImageStoresUrlAndSrc(): void
    {
        $field = new Image('/img/photo.jpg', 'Photo');
        $options = $field->getOptions();

        $this->assertSame('/img/photo.jpg', $options['options']['url']);
        $this->assertSame('/img/photo.jpg', $options['options']['src']);
    }

    public function testImageFieldStartsWithImgPrefix(): void
    {
        $field = new Image('/img/photo.jpg');
        $this->assertStringStartsWith('img_', $field->getField());
    }

    public function testImageGetType(): void
    {
        $field = new Image('/img/photo.jpg');
        $this->assertSame('image', $field->getType());
    }

    // ── StaticText ────────────────────────────────────────────

    public function testStaticTextFieldStartsWithStaticPrefix(): void
    {
        $field = new StaticText('Some information');
        $this->assertStringStartsWith('static_', $field->getField());
    }

    public function testStaticTextLabelIsTheText(): void
    {
        $field = new StaticText('Read-only content');
        $this->assertSame('Read-only content', $field->getLabel());
    }

    public function testStaticTextGetType(): void
    {
        $field = new StaticText('Info');
        $this->assertSame('static_text', $field->getType());
    }

    // ── RelationList ──────────────────────────────────────────

    public function testRelationListGetType(): void
    {
        $field = new RelationList('lines', 'Invoice Lines');
        $this->assertSame('relation_list', $field->getType());
    }
}
