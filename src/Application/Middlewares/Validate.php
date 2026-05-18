<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Middlewares;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Application\ValidateMessage;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Handler\ConsoleHandler;
use JsonSchema\Validator;
use ScssPhp\ScssPhp\Compiler;
use Symfony\Component\Console\Output\OutputInterface;

class Validate implements MiddlewareInterface
{
    use DataFromJsonTrait;

    private Validator $validator;

    private FilesFinder $filesFinder;

    private Compiler $compiler;

    public function __construct(
        Validator $validator,
        Compiler $compiler,
        FilesFinder $filesFinder
    ) {
        $this->validator = $validator;
        $this->filesFinder = $filesFinder;
        $this->compiler = $compiler;
    }

    /**
     * @phpstan-param ValidateMessage $message
     * @phpstan-param ConsoleHandler $handler
     */
    public function process(object $message, HandlerInterface $handler): mixed
    {
        /**
         * OutputInterface $output
         */
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();

        foreach ($this->filesFinder->find($message->getRootFolder(), 'json') as $file) {
            $output->writeln('========================');
            $output->writeln(\sprintf(
                'Validating <info>%s</info>',
                $file->getFilename()
            ));

            $this->validateJsonFile($output, $file, $message->getSchemaPath());
            $this->validator->reset();
            /**
             * @todo Implementing scss validation
             */
            $this->compiler->compileString('');
        }

        return $handler->handle($message);
    }

    private function validateJsonFile(
        OutputInterface $output,
        \SplFileInfo $file,
        string $schemaPath
    ): void {
        $data = $this->objectFromPath((string)$file);
        $this->validator->validate($data, (object)['$ref' => 'file://' . \realpath($schemaPath)]);

        if (!$this->validator->isValid()) {
            $output->writeln('<error># ' . $file->getFilename() . ' file errors</error>');
            /**
             * @var array<string, string> $error
             */
            foreach ((array)$this->validator->getErrors() as $error) {
                $output->writeln(\sprintf(
                    '- <error>[%s]</error> is not valid. %s',
                    $error['property'] ?? '',
                    $error['message'] ?? ''
                ));
            }

            return;
        }

        $output->writeln(\sprintf(
            '<info>%s</info> is valid',
            $file->getFilename()
        ));
    }
}
