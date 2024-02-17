<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Output;

use ItalyStrap\ThemeJsonGenerator\Application\Commands\InfoMessage;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;

/**
 * @psalm-api
 * @todo Implement the logic
 */
class Info implements \ItalyStrap\Bus\HandlerInterface
{
    private FilesFinder $filesFinder;

    public function __construct(
        FilesFinder $filesFinder
    ) {
        $this->filesFinder = $filesFinder;
    }

    public function handle(object $message): int
    {
        /** @var InfoMessage $message */
        foreach ($this->filesFinder->find($message->getRootFolder(), 'json') as $file) {
            echo $file->getBasename() . PHP_EOL;
        }

        return 0;
    }
}
