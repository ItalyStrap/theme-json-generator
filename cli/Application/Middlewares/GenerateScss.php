<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\ScssMessage;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Scss\ScssExporter;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Scss\ScssOutputResolver;

final readonly class GenerateScss implements MiddlewareInterface
{
    public function __construct(private ScssExporter $exporter, private ScssOutputResolver $resolver)
    {
    }

    /** @phpstan-param ScssMessage $message */
    public function process(object $message, HandlerInterface $handler): mixed
    {
        $input = $this->resolver->resolve($message->input, (string) \getcwd());
        $data = $this->read($input);
        $destination = $message->output ?? \dirname($input) . '/_' . \basename($input, '.json') . '.scss';
        $path = $this->resolver->resolve($destination, (string) \getcwd());
        $status = $this->exporter->export(
            $path,
            $this->exporter->content($data),
            $message->dryRun,
            $message->check
        );
        if ($status !== 0) {
            return $status;
        }

        return $handler->handle($message);
    }

    /** @return array<array-key, mixed> */
    private function read(string $path): array
    {
        if (!\str_ends_with($path, '.json') || !\is_file($path) || !\is_readable($path)) {
            throw new \InvalidArgumentException('Input must be a readable .json file: ' . $path);
        }

        $json = \file_get_contents($path);
        if ($json === false) {
            throw new \RuntimeException('Cannot read JSON input: ' . $path);
        }

        try {
            $object = \json_decode($json, false, 512, JSON_THROW_ON_ERROR);
            $data = \json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new \InvalidArgumentException(
                'Invalid JSON in ' . $path . ': ' . $exception->getMessage(),
                0,
                $exception
            );
        }

        if (!$object instanceof \stdClass || !\is_array($data)) {
            throw new \InvalidArgumentException('JSON input must contain an object: ' . $path);
        }

        return $data;
    }
}
