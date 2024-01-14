<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Output\Events;

/**
 * @psalm-api
 */
class ValidatedFails
{
    private \SplFileInfo $file;

    private array $errors;

    public function __construct(\SplFileInfo $file, array $errors)
    {
        $this->file = $file;
        $this->errors = $errors;
    }

    public function getFile(): \SplFileInfo
    {
        return $this->file;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
