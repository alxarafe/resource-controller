<?php
declare(strict_types=1);
namespace Alxarafe\ResourceController\Component\Container;

class HtmlContent extends AbstractContainer
{
    private string $html;
    public function __construct(string $html)
    {
        $this->html = $html;
        parent::__construct('html_content', '', []);
    }
    public function getContainerType(): string { return 'html_content'; }
    public function getHtml(): string { return $this->html; }

    #[\Override]
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), ['html' => $this->html]);
    }
}
