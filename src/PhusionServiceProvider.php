<?php

namespace Thermiteplasma\Phusion;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Thermiteplasma\Phusion\Commands\PhusionCommand;

class PhusionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('phusion')
            ->hasConfigFile();
        
        $this->app->bind('phusion', function ($app) {
            return new ReportBuilder();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'phusion',
        ];
    }
}
