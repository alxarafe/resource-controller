<?php
declare(strict_types=1);
namespace Alxarafe\ResourceController\Component\Container;

class HtmlContent extends AbstractContainer
{
    private string $html;
    public function __construct(string $html, string $title = '', array $options = [])
    {
        $this->html = $html;
        parent::__construct(uniqid('html_'), $title, $options);
    }
    public function getContainerType(): string { return 'html_content'; }
    public function getHtml(): string { return $this->html; }

    public function getColClass(): string
    {
        return $this->options['col'] ?? 'col-12';
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), ['html' => $this->html]);
    }
}
