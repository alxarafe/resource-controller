<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController;

/**
 * ResourceInterface — Defines the operational modes for a Resource Controller.
 */
interface ResourceInterface
{
    public const MODE_LIST = 'list';
    public const MODE_EDIT = 'edit';
}
