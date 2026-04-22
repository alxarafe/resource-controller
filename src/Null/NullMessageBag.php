<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Null;

use Alxarafe\ResourceController\Contracts\MessageBagContract;

/**
 * NullMessageBag — Stores messages in memory (no session, no persistence).
 *
 * Useful for API-only controllers or testing.
 */
final class NullMessageBag implements MessageBagContract
{
    /** @var array<int, array{type: string, text: string}> */
    private array $messages = [];

    public function success(string $message): void
    {
        $this->messages[] = ['type' => 'success', 'text' => $message];
    }

    public function error(string $message): void
    {
        $this->messages[] = ['type' => 'danger', 'text' => $message];
    }

    public function warning(string $message): void
    {
        $this->messages[] = ['type' => 'warning', 'text' => $message];
    }

    public function getMessages(): array
    {
        $messages = $this->messages;
        $this->messages = [];
        return $messages;
    }
}
