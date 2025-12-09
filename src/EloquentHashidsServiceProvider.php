<?php

namespace DialloIbrahima\EloquentHashids;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class EloquentHashidsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('eloquent-hashids')
            ->hasConfigFile();
    }
}
