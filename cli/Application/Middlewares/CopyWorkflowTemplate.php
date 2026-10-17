<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Message;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem\WorkflowTemplate;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Output\ConsoleOutput;

final readonly class CopyWorkflowTemplate implements MiddlewareInterface
{
    public function __construct(private WorkflowTemplate $template)
    {
    }

    /** @phpstan-param Message $message */
    public function process(object $message, HandlerInterface $handler): mixed
    {
        $destination = $this->template->copyTo($message->getRootFolder());
        $output = new ConsoleOutput();
        $output->writeln('<info>Created ' . OutputFormatter::escape($destination) . '</info>');
        $output->writeln('Set THEME_JSON_VERSION and customize the workflow before committing it.');

        return $handler->handle($message);
    }
}
