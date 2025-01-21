<?php

declare(strict_types=1);

namespace Stolt\Tests;

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

        if (\strtolower(PHP_OS_FAMILY) === 'darwin') {
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
    public function returnsExpectedDiff(): void
    {
        $difftastic = new Difftastic();
        $diff = $difftastic->diff('[1, 2, 3]', '[3, 2, 1]');

        $expectedDifftasticOutput = <<<DIFF
No such file: /tmp/a
No such file: /tmp/b
DIFF;

        $this->assertEquals($diff, $expectedDifftasticOutput);
    }
}
