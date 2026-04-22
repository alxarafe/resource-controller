<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Contracts;

/**
 * HookContract — Event/hook system abstraction.
 *
 * Allows plugins and modules to inject behavior at specific
 * lifecycle points (before save, after delete, form fields, etc.).
 */
interface HookContract
{
    /**
     * Execute all registered callbacks for a hook.
     *
     * @param string $hookName Hook identifier.
     * @param mixed  ...$args  Arguments passed to callbacks.
     * @return array Results from all callbacks.
     */
    public function execute(string $hookName, mixed ...$args): array;

    /**
     * Filter a value through all registered callbacks (pipeline).
     *
     * Each callback receives the current value and returns a modified value.
     *
     * @param string $hookName Hook identifier.
     * @param mixed  $value    The value to filter.
     * @param mixed  ...$args  Additional context arguments.
     * @return mixed The filtered value.
     */
    public function filter(string $hookName, mixed $value, mixed ...$args): mixed;
}
