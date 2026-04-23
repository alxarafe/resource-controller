<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit\Null;

use Alxarafe\ResourceController\Contracts\HookContract;
use Alxarafe\ResourceController\Null\NullHookService;
use PHPUnit\Framework\TestCase;

class NullHookServiceTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $hooks = new NullHookService();
        $this->assertInstanceOf(HookContract::class, $hooks);
    }

    public function testExecuteReturnsEmptyArray(): void
    {
        $hooks = new NullHookService();
        $result = $hooks->execute('before_save', ['data' => 'value']);
        $this->assertSame([], $result);
    }

    public function testFilterReturnsValueUnchanged(): void
    {
        $hooks = new NullHookService();
        $result = $hooks->filter('modify_fields', ['field_a', 'field_b']);
        $this->assertSame(['field_a', 'field_b'], $result);
    }

    public function testFilterPassthroughWithDifferentTypes(): void
    {
        $hooks = new NullHookService();

        $this->assertSame(42, $hooks->filter('number', 42));
        $this->assertSame('hello', $hooks->filter('string', 'hello'));
        $this->assertNull($hooks->filter('null', null));
    }
}
