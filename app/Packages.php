<?php

namespace App;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\BufferIO;
use Composer\Package\Link;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * The packages model provides an index of installed packages via composer which provide modules.
 *
 * It is registered into the DI container by PackageServiceProvider
 */
class Packages
{
    public function __construct()
    {
        $io = new BufferIO();

        // When invoked from the web application, Composer will assume public/ contains a composer.json
        // So, we need to hint the actual location of composer.json
        $composer = Factory::create($io);

        $this->composer = $composer;
        $this->io = $io;
    }

    /**
     * Get a list of all Composer packages installed.
     *
     * @return array|null
     */
    public function index()
    {
        $mr = $this->composer->getPackage();

        if ($mr === null) {
            Log::alert('composer returned a null package, maybe you have removed composer.json/composer.lock or it is not accessible by the webserver');
            Log::info($this->io->getOutput());

            return null;
        }

        $packages = [];

        $localRepo = $this->composer->getRepositoryManager()->getLocalRepository();

        foreach ($this->composer->getPackage()->getRequires() as $required) {
            if ($required instanceof Link)
            {
                $p = $localRepo->findPackage($required->getTarget(), $required->getConstraint());
                if ($p === null) continue;
                $packages[] = $p->getPrettyString();
            }
        }

        return $packages;
    }

    /**
     * Get a list of Composer packages installed which are MunkiReport PHP modules, either:
     *
     * - Name starts `munkireport/`
     * - composer.json extras contains `munkireport` key
     *
     * @return array|null
     */
    public function modules()
    {
        $mr = $this->composer->getPackage();

        if ($mr === null) {
            Log::alert('composer returned a null package, maybe you have removed composer.json/composer.lock or it is not accessible by the webserver');
            Log::info($this->io->getOutput());

            return null;
        }

        $packages = [];
        $localRepo = $this->composer->getRepositoryManager()->getLocalRepository();
        foreach ($this->composer->getPackage()->getRequires() as $required) {
            if ($required instanceof Link) {
                $p = $localRepo->findPackage($required->getTarget(), $required->getConstraint());
                if ($p === null) continue;
                if (Arr::has($p->getExtra(), 'munkireport')) {
                    $packages[] = $p;
                } else {
                    if (!Str::startsWith($p->getName(), 'munkireport/')) continue;
                    $packages[] = $p;
                }
            }
        }

        return $packages;
    }
}
