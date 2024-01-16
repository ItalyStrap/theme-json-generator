<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Output\Events;

/**
 * @psalm-api
 */
class EntryPointCanNotBeCreated
{
    private string $file;
    private \Throwable $exception;

    public function __construct(string $file, \Throwable $exception)
    {
        $this->file = $file;
        $this->exception = $exception;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getException(): \Throwable
    {
        return $this->exception;
    }
}
