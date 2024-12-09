<?php

namespace Windsor\Phetl;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Windsor\Phetl\Commands\PhetlCommand;

class PhetlServiceProvider extends PackageServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->bind(Phetl::class, function () {
            return new Phetl;
        });

        $this->app->bind(ExtractorBuilder::class, function () {
            return new ExtractorBuilder;
        });

        $this->app->bind(TransformationPipeline::class, function () {
            return new TransformationPipeline;
        });

        $this->app->bind(LoaderBuilder::class, function () {
            return new LoaderBuilder;
        });

    }

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
