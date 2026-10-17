<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares;

use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\DumpMessage;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\ThemeJsonContainerFactoryInterface;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Autoload\EntrypointLoader;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem\FilesFinder;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem\JsonFileWriter;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem\ScssFileWriter;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Scss\ScssExporter;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Scss\ScssOutputResolver;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @phpstan-type PreparedDump array{
 *     name: string, file: \SplFileInfo, config: ConfigInterface<array-key, mixed>,
 *     scss: ?string, content: string
 * }
 */
final readonly class Dump implements MiddlewareInterface
{
    /**
     * @var string
     */
    public const M_NO_FILE_FOUND = 'No file found';

    public const JSON_FILE_SUFFIX = '.json';

    public function __construct(
        private FilesFinder $filesFinder,
        private ThemeJsonContainerFactoryInterface $containerFactory,
        private EntrypointLoader $entrypointLoader,
        private ScssExporter $scssExporter,
        private ScssOutputResolver $scssResolver
    ) {
    }

    /**
     * @phpstan-param DumpMessage $message
     */
    public function process(object $message, HandlerInterface $handler): int
    {
        /**
         * OutputInterface $output
         */
        $output = new ConsoleOutput();

        $entries = $this->prepare($message);
        foreach ($entries as $entry) {
            if ($message->isDryRun()) {
                $output->writeln(\sprintf(
                    '<comment>Dry run mode enabled, skipping file generation for %s</comment>',
                    $entry['name']
                ));
            }

            if (!$message->isDryRun()) {
                $this->generateJsonFile($output, $message, $entry['name'], $entry['file'], $entry['config']);
            }

            if ($entry['scss'] !== null) {
                $this->scssExporter->export($entry['scss'], $entry['content'], $message->isDryRun());
            }
        }

        if ($entries === []) {
            $output->writeln(self::M_NO_FILE_FOUND);
        }

        $result = $handler->handle($message);

        if (!\is_int($result)) {
            throw new \RuntimeException('Expected middleware handler to return an integer exit code.');
        }

        return $result;
    }

    /** @return list<PreparedDump> */
    private function prepare(DumpMessage $message): array
    {
        $prepared = [];
        $destinations = [];
        $entries = $this->entrypointLoader->entries(
            $this->filesFinder->find($message->getRootFolder(), 'php'),
            $message->getRootFolder(),
            $message->getAutoload(),
            $message->getFile()
        );
        foreach ($entries as $fileName => [$file, $entrypoint]) {
            $path = $message->noScss ? null : $this->scssResolver->forEntrypoint(
                $entrypoint,
                $file,
                $message->scssDirectory
            );
            if ($path !== null) {
                new ScssFileWriter($path);
                if (\array_key_exists($path, $destinations)) {
                    throw new \InvalidArgumentException('Multiple entrypoints target the same SCSS file: ' . $path);
                }

                $destinations[$path] = true;
            }

            // Execute while the entrypoint loader's project autoloader is registered.
            $result = $this->containerFactory->execute($entrypoint);
            $prepared[] = [
                'name' => $fileName,
                'file' => $file,
                'config' => $result->config,
                'scss' => $path,
                'content' => $path !== null ? $this->scssExporter->content($result->config->toArray()) : '',
            ];
        }

        return $prepared;
    }

    /**
     * @param ConfigInterface<array-key, mixed> $config
     */
    private function generateJsonFile(
        OutputInterface $output,
        DumpMessage $message,
        string $fileName,
        \SplFileInfo $file,
        ConfigInterface $config
    ): void {

        $output->writeln(\sprintf(
            '<info>Generating %s file</info>',
            $fileName . self::JSON_FILE_SUFFIX
        ));

        (new JsonFileWriter($this->filesFinder->resolveJsonFile($file, $message->getRootFolder())))
            ->write($config);

        $output->writeln(\sprintf(
            '<info>Generated %s file</info>',
            $fileName . self::JSON_FILE_SUFFIX
        ));
        $output->writeln('========================');
    }
}
