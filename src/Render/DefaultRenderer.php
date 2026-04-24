<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Render;

use Alxarafe\ResourceController\Contracts\RendererContract;
use RuntimeException;

/**
 * DefaultRenderer — Built-in implementation of RendererContract.
 *
 * Uses pure PHP templates (.phtml, .php, .html) with `include` and `extract()`.
 */
class DefaultRenderer implements RendererContract
{
    /** @var string[] */
    private array $paths;

    /**
     * @param string|string[] $templatePaths Path(s) where templates are located.
     */
    public function __construct(string|array $templatePaths = [])
    {
        $this->paths = is_array($templatePaths) ? $templatePaths : [$templatePaths];
    }

    /**
     * @inheritDoc
     */
    public function render(string $template, array $data = []): string
    {
        $file = $this->findTemplate(str_replace('.', '/', $template));
        if (!$file) {
            throw new RuntimeException("Template not found: {$template}");
        }
        extract($data, EXTR_SKIP);
        ob_start();
        include $file;
        return (string) ob_get_clean();
    }

    /**
     * @inheritDoc
     */
    public function addTemplatePath(string $path): void
    {
        // Insert at the beginning so newer paths take precedence
        array_unshift($this->paths, $path);
    }

    /**
     * Finds the absolute path to a template file.
     */
    private function findTemplate(string $template): ?string
    {
        foreach (['.phtml', '.php', '.html'] as $ext) {
            foreach ($this->paths as $path) {
                $file = rtrim($path, '/') . '/' . ltrim($template, '/') . $ext;
                if (is_file($file)) {
                    return $file;
                }
            }
        }
        return null;
    }
}
