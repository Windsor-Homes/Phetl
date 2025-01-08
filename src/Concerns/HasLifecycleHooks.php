<?php

namespace Windsor\Phetl\Concerns;

trait HasLifecycleHooks
{
    protected array $hooks = [];

    protected function addHook(string $event, callable $callback)
    {
        if (! $callback instanceof \Closure) {
            $callback = \Closure::fromCallable($callback);
        }

        $this->hooks[$event][] = $callback;
    }

    protected function removeHook(string $event, callable $callback)
    {
        if (!isset($this->hooks[$event])) {
            return;
        }

        $this->hooks[$event] = array_filter(
            $this->hooks[$event],
            fn ($item) => $item !== $callback
        );
    }

    public function clearHooks(string $hook)
    {
        unset($this->hooks[$hook]);
    }

    public function flushHooks()
    {
        $this->hooks = [];
    }

    public function hasHooks(string $event): bool
    {
        return isset($this->hooks[$event]);
    }

    public function doesntHaveHooks(string $event): bool
    {
        return ! $this->hasHooks($event);
    }

    public function getHooks(string $event): array
    {
        return $this->hooks[$event] ?? [];
    }

    public function getAllHooks(): array
    {
        return $this->hooks;
    }

    protected function runHooks(string $event, ...$args)
    {
        if (! $this->hasHooks($event)) {
            return;
        }

        foreach ($this->hooks[$event] as $callback) {
            call_user_func($callback, ...$args);
        }
    }
}