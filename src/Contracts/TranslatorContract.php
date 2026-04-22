<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Contracts;

/**
 * TranslatorContract — Internationalization abstraction.
 *
 * Wraps whatever translation system the host application uses
 * (Symfony Translator, Laravel trans(), gettext, etc.).
 */
interface TranslatorContract
{
    /**
     * Translate a message key.
     *
     * @param string               $key    Translation key.
     * @param array<string, mixed> $params Replacement parameters.
     * @return string Translated string, or the key itself if no translation found.
     */
    public function translate(string $key, array $params = []): string;
}
