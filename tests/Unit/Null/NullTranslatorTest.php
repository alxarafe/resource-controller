<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Null;

use Alxarafe\ResourceController\Contracts\TranslatorContract;
use Alxarafe\ResourceController\Null\NullTranslator;
use PHPUnit\Framework\TestCase;

class NullTranslatorTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $translator = new NullTranslator();
        $this->assertInstanceOf(TranslatorContract::class, $translator);
    }

    public function testTranslateReturnsKeyAsIs(): void
    {
        $translator = new NullTranslator();
        $this->assertSame('hello_world', $translator->translate('hello_world'));
    }

    public function testTranslateReplacesParams(): void
    {
        $translator = new NullTranslator();
        $result = $translator->translate('Hello %name%!', ['name' => 'World']);
        $this->assertSame('Hello World!', $result);
    }

    public function testTranslateReplacesMultipleParams(): void
    {
        $translator = new NullTranslator();
        $result = $translator->translate('%greeting% %name%!', ['greeting' => 'Hi', 'name' => 'Rafael']);
        $this->assertSame('Hi Rafael!', $result);
    }

    public function testTranslateWithNoMatchingParams(): void
    {
        $translator = new NullTranslator();
        $result = $translator->translate('No params here', ['unused' => 'value']);
        $this->assertSame('No params here', $result);
    }
}
