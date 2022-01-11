<?php

namespace Tests\Unit;

use App\Packages;
use Composer\Factory;
use Composer\IO\BufferIO;
use PHPUnit\Framework\TestCase;

class PackagesTest extends TestCase
{

    public function testIndex()
    {
        $io = new BufferIO();
        $composer = Factory::create($io);

        $sut = new Packages($composer, $io);
        $packages = $sut->index();

        $this->assertIsArray($packages);
    }

    public function testModules()
    {
        $io = new BufferIO();
        $composer = Factory::create($io);

        $sut = new Packages($composer, $io);
        $modules = $sut->modules();

        $this->assertIsArray($modules);
    }
}
