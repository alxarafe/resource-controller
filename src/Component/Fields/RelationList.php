<?php
declare(strict_types=1);
namespace Alxarafe\ResourceController\Component\Fields;
use Alxarafe\ResourceController\Component\AbstractField;

class RelationList extends AbstractField
{
    protected string $component = 'relation_list';
    public function getType(): string { return 'relation_list'; }
}
