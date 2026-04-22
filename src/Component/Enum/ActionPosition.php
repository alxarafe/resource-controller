<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Component\Enum;

/**
 * ActionPosition — Position of action buttons relative to a field.
 */
enum ActionPosition: string
{
    case Left = 'left';
    case Right = 'right';
}
