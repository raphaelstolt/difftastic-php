<?php

declare(strict_types=1);

namespace Stolt\Tests;

use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Ticket;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Stolt\Difftastic;

class DifftasticTest extends TestCase
{
    #[Test]
    public function getsExpectedDifftasticBinary(): void
    {
        $difftastic = new \ReflectionClass('Stolt\Difftastic');
        $getDifftasticBinaryByOsMethod = $difftastic->getMethod('getDifftasticBinaryByOs');
        $getDifftasticBinaryByOsMethod->setAccessible(true);

        $difftastic = new Difftastic();

        $expectedBinary = 'difftastic';

        if (\strtolower(PHP_OS_FAMILY) === 'darwin' || \strtolower(PHP_OS_FAMILY) === 'windows') {
            $expectedBinary = 'difft';
        }

        $actualBinary = $getDifftasticBinaryByOsMethod->invokeArgs($difftastic, []);

        $this->assertEquals($expectedBinary, $actualBinary);
    }

    #[Test]
    public function throwsExpectedExceptionOnMisconfiguration(): void
    {
        $this->expectException(RuntimeException::class);

        new Difftastic(background: 'green');
    }

    #[Test]
    #[Ticket('https://github.com/Wilfred/difftastic/issues/809')]
    #[RunInSeparateProcess]
    public function returnsExpectedDiff(): void
    {
        $difftastic = new Difftastic();
        $diff = $difftastic->diff('[1, 2, 3]', '[3, 2, 1]');

        $expectedDifftasticOutput = <<<DIFF
No such file: /tmp/a
No such file: /tmp/b
DIFF;

        if (\strtolower(PHP_OS_FAMILY) === 'darwin' || \strtolower(PHP_OS_FAMILY) === 'windows') {
            $expectedDifftasticOutput = <<<DIFF_MAC_WINDOWS_OS
            /var/folders/mz/34zz0rcx2tq3m7jcqbd4wrkr0000gn/T/a --- Text
1 [1, 2, 3]                   1 [3, 2, 1]

DIFF_MAC_WINDOWS_OS;
        }

        $this->assertEquals($this->removeFirstOutputLine($diff), $this->removeFirstOutputLine($expectedDifftasticOutput));
    }

    private function removeFirstOutputLine(string $output): string
    {
        $parts = \explode(PHP_EOL, $output);

        \array_shift($parts);

        return \implode(PHP_EOL, $parts);
    }
}
