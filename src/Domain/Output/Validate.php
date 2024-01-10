<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Output;

use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\ValidatedFails;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\ValidatingFile;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\ValidFile;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use JsonSchema\Validator;
use Psr\EventDispatcher\EventDispatcherInterface;

class Validate
{
    use DataFromJsonTrait;

    private Validator $validator;
    private FilesFinder $filesFinder;
    private EventDispatcherInterface $dispatcher;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        Validator $validator,
        FilesFinder $filesFinder
    ) {
        $this->validator = $validator;
        $this->filesFinder = $filesFinder;
        $this->dispatcher = $dispatcher;
    }

    public function handle(object $command): void
    {
        foreach ($this->filesFinder->find($command->getRootFolder(), 'json') as $file) {
            $this->dispatcher->dispatch(new ValidatingFile($file));
            $this->validateJsonFile($file, $command->getSchemaPath());
            $this->validator->reset();
        }
    }

    private function validateJsonFile(
        \SplFileInfo $file,
        string $schemaPath
    ): void {

        $data = $this->objectFromPath((string)$file);
        $this->validator->validate($data, (object)['$ref' => 'file://' . \realpath($schemaPath)]);

        if (!$this->validator->isValid()) {
            $this->dispatcher->dispatch(new ValidatedFails($file, $this->validator->getErrors()));
            return;
        }

        $this->dispatcher->dispatch(new ValidFile($file));
    }
}
