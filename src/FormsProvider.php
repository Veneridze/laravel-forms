<?php
namespace Veneridze\LaravelForms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;

class FormsProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-forms')
            //->hasConfigFile()
            ->publishesServiceProvider('FormsProvider')
            ->hasInstallCommand(function(InstallCommand $command) {
                //$command
                    //->publishConfigFile()
                    //->publishMigrations()
                    //->copyAndRegisterServiceProviderInApp();
            });
    }

    public function packageBooted(): void
    {

    }

    public function packageRegistered(): void
    {
        
    }
}
