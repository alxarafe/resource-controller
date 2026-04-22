<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController;

use Alxarafe\ResourceController\Contracts\HookContract;
use Alxarafe\ResourceController\Contracts\MessageBagContract;
use Alxarafe\ResourceController\Contracts\RepositoryContract;
use Alxarafe\ResourceController\Contracts\TransactionContract;
use Alxarafe\ResourceController\Contracts\TranslatorContract;
use Alxarafe\ResourceController\Null\NullHookService;
use Alxarafe\ResourceController\Null\NullMessageBag;
use Alxarafe\ResourceController\Null\NullTransaction;
use Alxarafe\ResourceController\Null\NullTranslator;
use Alxarafe\ResourceController\Trait\ResourceTrait;

/**
 * AbstractResourceController — Convenience base class.
 *
 * Provides dependency wiring so concrete controllers only need to
 * define getRepository() and the resource configuration methods.
 *
 * For frameworks that prefer composition over inheritance (e.g. Laravel),
 * use ResourceTrait directly in your own controller base class.
 */
abstract class AbstractResourceController implements ResourceInterface
{
    use ResourceTrait;

    private TranslatorContract $translator;
    private MessageBagContract $messages;
    private HookContract $hooks;
    private TransactionContract $transaction;

    public function __construct(
        ?TranslatorContract $translator = null,
        ?MessageBagContract $messages = null,
        ?HookContract $hooks = null,
        ?TransactionContract $transaction = null,
    ) {
        $this->translator = $translator ?? new NullTranslator();
        $this->messages = $messages ?? new NullMessageBag();
        $this->hooks = $hooks ?? new NullHookService();
        $this->transaction = $transaction ?? new NullTransaction();
    }

    protected function getTranslator(): TranslatorContract
    {
        return $this->translator;
    }

    protected function getMessages(): MessageBagContract
    {
        return $this->messages;
    }

    protected function getHooks(): HookContract
    {
        return $this->hooks;
    }

    protected function getTransaction(): TransactionContract
    {
        return $this->transaction;
    }

    /**
     * Main entry point — call from your router/dispatcher.
     */
    public function index(): void
    {
        $this->privateCore();
    }
}
