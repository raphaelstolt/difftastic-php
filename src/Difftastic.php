<?php

declare(strict_types=1);

namespace Stolt;

use RuntimeException;

final class Difftastic
{
    private string $difftasticBinaryCommand;

    private string $background = 'dark';

    private string $color = 'auto';

    /**
     * @throws RuntimeException
     */
    public function __construct(string $background = 'dark', string $color = 'auto')
    {
        if (!\in_array($background, ['dark', 'light'])) {
            throw new RuntimeException('Background must be one of \'dark\', \'light\'.');
        }

        if (!\in_array($color, ['always', 'auto', 'never'])) {
            throw new RuntimeException('Color must be one of \'always\', \'auto\', \'never\'.');
        }

        if ($this->isDifftasticCommandAvailable() === false) {
            throw new RuntimeException('Difftastic CLI not available');
        }
    }

    private function isDifftasticCommandAvailable(): bool
    {
        $this->difftasticBinaryCommand = $this->getDifftasticBinaryByOs();

        \exec('where ' . $this->difftasticBinaryCommand . ' 2>&1', $output, $returnValue);
        if ($this->isWindows() === false) {
            \exec('which ' . $this->difftasticBinaryCommand . ' 2>&1', $output, $returnValue);
        }

        return $returnValue === 0;
    }

    private function getDifftasticBinaryByOs(): string
    {
        return match (\strtolower(PHP_OS_FAMILY)) {
            'darwin' => 'difft',
            'linux' => 'difftastic',
            'windows' => 'difft',
            default => throw new RuntimeException(
                'Unsupported operating system ' . PHP_OS_FAMILY
            )
        };
    }

    private function getDifftasticOptions(): string
    {
        $options = '';

        if ($this->background !== 'dark') {
            $options .= ' --background ' . $this->background;
        }

        if ($this->color !== 'auto') {
            $options .= ' --color ' . $this->color;
        }

        return $options;
    }

    private function createTemporaryFile(string $name, string $content): string
    {
        $file = DIRECTORY_SEPARATOR . \trim(\sys_get_temp_dir(), DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR . \ltrim($name, DIRECTORY_SEPARATOR);

        if ($this->isWindows()) {
            $file = \trim(\sys_get_temp_dir(), DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR . \ltrim($name, DIRECTORY_SEPARATOR);
        }

        file_put_contents($file, $content);

        \register_shutdown_function(function () use ($file) {
            \unlink($file);
        });

        return $file;
    }

    private function isWindows(string $os = PHP_OS): bool
    {
        if (\strtoupper(\substr($os, 0, 3)) !== 'WIN') {
            return false;
        }

        return true;
    }

    public function diff(string $a, string $b): string
    {
        $aFile = $this->createTemporaryFile('a', $a);
        $bFile = $this->createTemporaryFile('b', $b);

        $command = $this->difftasticBinaryCommand . $this->getDifftasticOptions() . ' ' . $aFile . ' ' . $bFile . ' 2>&1';

        \exec($command, $output, $returnValue);

        return \implode(PHP_EOL, $output);
    }
}
