<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Null;

use Alxarafe\ResourceController\Contracts\HookContract;

/**
 * NullHookService — No-op implementation for when hooks are not needed.
 */
final class NullHookService implements HookContract
{
    public function execute(string $hookName, mixed ...$args): array
    {
        return [];
    }

    public function filter(string $hookName, mixed $value, mixed ...$args): mixed
    {
        return $value;
    }
}
