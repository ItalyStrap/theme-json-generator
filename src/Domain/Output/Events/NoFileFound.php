<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Output\Events;

/**
 * @psalm-api
 */
class NoFileFound
{
    /**
     * @var string
     */
    public const M_NO_FILE_FOUND = 'No file found';

    public function __construct()
    {
    }
}
