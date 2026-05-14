<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Middlewares;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Application\ValidateMessage;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\ValidatedFails;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\ValidatingFile;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\ValidFile;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use JsonSchema\Validator;
use Psr\EventDispatcher\EventDispatcherInterface;
use ScssPhp\ScssPhp\Compiler;

class Validate implements MiddlewareInterface
{
    use DataFromJsonTrait;

    private Validator $validator;

    private FilesFinder $filesFinder;

    private EventDispatcherInterface $dispatcher;

    private Compiler $compiler;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        Validator $validator,
        Compiler $compiler,
        FilesFinder $filesFinder
    ) {
        $this->validator = $validator;
        $this->filesFinder = $filesFinder;
        $this->dispatcher = $dispatcher;
        $this->compiler = $compiler;
    }

    public function process(object $message, HandlerInterface $handler): mixed
    {
        $this->needsRefactoringAddSubscriber($this->dispatcher);

        /** @var ValidateMessage $message */
        foreach ($this->filesFinder->find($message->getRootFolder(), 'json') as $file) {
            $this->dispatcher->dispatch(new ValidatingFile($file));
            $this->validateJsonFile($file, $message->getSchemaPath());
            $this->validator->reset();
            /**
             * @todo Implementing scss validation
             */
            $this->compiler->compileString('');
        }

        return (int)$handler->handle($message);
    }

    private function validateJsonFile(
        \SplFileInfo $file,
        string $schemaPath
    ): void {
        $data = $this->objectFromPath((string)$file);
        $this->validator->validate($data, (object)['$ref' => 'file://' . \realpath($schemaPath)]);

        if (!$this->validator->isValid()) {
            $this->dispatcher->dispatch(new ValidatedFails($file, (array)$this->validator->getErrors()));
            return;
        }

        $this->dispatcher->dispatch(new ValidFile($file));
    }

    private function needsRefactoringAddSubscriber($subscriber): void
    {
        /**
         * OutputInterface $output
         */
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();

        $subscriber->addListener(
            ValidatingFile::class,
            static function (ValidatingFile $event) use ($output): void {
                $output->writeln('========================');
                $output->writeln(\sprintf(
                    'Validating <info>%s</info>',
                    $event->getFile()->getFilename()
                ));
            }
        );

        $subscriber->addListener(
            ValidFile::class,
            static function (ValidFile $event) use ($output): void {
                $output->writeln(\sprintf(
                    '<info>%s</info> is valid',
                    $event->getFile()->getFilename()
                ));
            }
        );

        $subscriber->addListener(
            ValidatedFails::class,
            static function (ValidatedFails $event) use ($output): void {
                $output->writeln('<error># ' . $event->getFile()->getFilename() . ' file errors</error>');
                /**
                 * @var array<string, string> $error
                 */
                foreach ($event->getErrors() as $error) {
                    $output->writeln(\sprintf(
                        '- <error>[%s]</error> is not valid. %s',
                        $error['property'] ?? '',
                        $error['message'] ?? ''
                    ));
                }
            }
        );
    }
}