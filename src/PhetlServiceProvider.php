<?php

namespace Windsor\Phetl;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Windsor\Phetl\Commands\PhetlCommand;

class PhetlServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('phetl')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_phetl_table')
            ->hasCommand(PhetlCommand::class);
    }
}
