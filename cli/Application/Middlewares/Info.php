<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Message;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem\FilesFinder;
use Symfony\Component\Console\Command\Command;

/**
 * @todo Implement the logic
 */
final readonly class Info implements MiddlewareInterface
{
    public function __construct(
        private FilesFinder $filesFinder
    ) {
    }

    /**
     * @phpstan-param Message $message
     */
    public function process(object $message, HandlerInterface $handler): int
    {
        foreach ($this->filesFinder->find($message->getRootFolder(), 'json') as $file) {
            echo $file->getBasename() . PHP_EOL;
        }

        return Command::SUCCESS;
    }
}
