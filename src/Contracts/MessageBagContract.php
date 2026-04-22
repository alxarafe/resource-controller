<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Contracts;

/**
 * MessageBagContract — Flash message / notification abstraction.
 *
 * Wraps the host application's message/notification system
 * (session flash, toast notifications, etc.).
 */
interface MessageBagContract
{
    public function success(string $message): void;

    public function error(string $message): void;

    public function warning(string $message): void;

    /**
     * Retrieve all pending messages and clear the queue.
     *
     * @return array<int, array{type: string, text: string}>
     */
    public function getMessages(): array;
}
