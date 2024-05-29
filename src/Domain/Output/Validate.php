<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Output;

use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Application\ValidateMessage;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\ValidatedFails;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\ValidatingFile;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\ValidFile;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use JsonSchema\Validator;
use Psr\EventDispatcher\EventDispatcherInterface;
use ScssPhp\ScssPhp\Compiler;

/**
 * @psalm-api
 */
class Validate implements \ItalyStrap\Bus\HandlerInterface
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

    public function handle(object $message): int
    {
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

        return 0;
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
}
