<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Null;

use Alxarafe\ResourceController\Contracts\TranslatorContract;

/**
 * NullTranslator — Passthrough implementation that returns the key as-is.
 */
final class NullTranslator implements TranslatorContract
{
    public function translate(string $key, array $params = []): string
    {
        $result = $key;
        foreach ($params as $name => $value) {
            $result = str_replace('%' . $name . '%', (string) $value, $result);
        }
        return $result;
    }
}
