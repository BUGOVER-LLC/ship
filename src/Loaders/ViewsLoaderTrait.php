<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait ViewsLoaderTrait
{
    public function loadViewsFromContainers($containerPath): void
    {
        $containerViewDirectory = $containerPath . '/UI/WEB/Views/';
        $containerMailTemplatesDirectory = $containerPath . '/Mails/Templates/';

        $containerName = basename($containerPath);
        $pathParts = explode(DIRECTORY_SEPARATOR, $containerPath);
        $sectionName = $pathParts[count($pathParts) - 2];

        $this->loadViews($containerViewDirectory, $containerName, $sectionName);
        $this->loadViews($containerMailTemplatesDirectory, $containerName, $sectionName);
    }

    private function loadViews($directory, $containerName, $sectionName = null): void
    {
        if (File::isDirectory($directory)) {
            $this->loadViewsFrom($directory, $this->buildViewNamespace($sectionName, $containerName));
        }
    }

    private function buildViewNamespace(?string $sectionName, string $containerName): string
    {
        return $sectionName ? (Str::camel($sectionName) . '@' . Str::camel($containerName)) : Str::camel(
            $containerName
        );
    }

    /**
     * @return void
     */
    public function loadViewsFromShip(): void
    {
        $shipMailTemplatesDirectory = config(
                'app.path'
            ) . 'Ship' . DIRECTORY_SEPARATOR . 'Mails' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR;
        $this->loadViews($shipMailTemplatesDirectory, 'ship'); // Ship views accessible via `ship::`.
    }
}