<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Contracts;

/**
 * RendererContract — View/template rendering abstraction.
 *
 * Wraps the host application's template engine (Blade, Twig, PHP templates, etc.).
 */
interface RendererContract
{
    /**
     * Render a template with the given data.
     *
     * @param string               $template Template name or path.
     * @param array<string, mixed> $data     Variables to pass to the template.
     * @return string Rendered HTML output.
     */
    public function render(string $template, array $data = []): string;

    /**
     * Register an additional path where templates can be found.
     */
    public function addTemplatePath(string $path): void;
}
