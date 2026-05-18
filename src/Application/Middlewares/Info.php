<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Middlewares;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Message;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use Symfony\Component\Console\Command\Command;

/**
 * @todo Implement the logic
 */
class Info implements MiddlewareInterface
{
    private FilesFinder $filesFinder;

    public function __construct(
        FilesFinder $filesFinder
    ) {
        $this->filesFinder = $filesFinder;
    }

    public function process(object $message, HandlerInterface $handler): int
    {
        /** @var Message $message */
        foreach ($this->filesFinder->find($message->getRootFolder(), 'json') as $file) {
            echo $file->getBasename() . PHP_EOL;
        }

        return Command::SUCCESS;
    }
}
