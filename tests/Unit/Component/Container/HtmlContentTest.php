<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Component\Container;

use Alxarafe\ResourceController\Component\Container\HtmlContent;
use PHPUnit\Framework\TestCase;

class HtmlContentTest extends TestCase
{
    public function testGetContainerType(): void
    {
        $html = new HtmlContent('<p>Hello</p>');
        $this->assertSame('html_content', $html->getContainerType());
    }

    public function testGetHtml(): void
    {
        $html = new HtmlContent('<strong>Bold</strong>');
        $this->assertSame('<strong>Bold</strong>', $html->getHtml());
    }

    public function testFieldStartsWithHtmlPrefix(): void
    {
        $html = new HtmlContent('<p>Test</p>');
        $this->assertStringStartsWith('html_', $html->getField());
    }

    public function testTitleIsStoredAsLabel(): void
    {
        $html = new HtmlContent('<p>Test</p>', 'My Title');
        $this->assertSame('My Title', $html->getLabel());
    }

    public function testDefaultColClass(): void
    {
        $html = new HtmlContent('<p>Test</p>');
        $this->assertSame('col-12', $html->getColClass());
    }

    public function testCustomColClass(): void
    {
        $html = new HtmlContent('<p>Test</p>', '', ['col' => 'col-6']);
        $this->assertSame('col-6', $html->getColClass());
    }

    public function testJsonSerializeIncludesHtml(): void
    {
        $html = new HtmlContent('<p>Content</p>', 'Title');
        $json = $html->jsonSerialize();

        $this->assertSame('html_content', $json['container']);
        $this->assertSame('<p>Content</p>', $json['html']);
        $this->assertSame('Title', $json['label']);
    }
}
