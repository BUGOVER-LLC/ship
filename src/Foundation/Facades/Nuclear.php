<?php

declare(strict_types=1);

namespace Nucleus\Foundation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getShipFoldersNames()
 * @method static array getShipPath()
 * @method static array getSectionContainerNames(string $sectionName)
 * @method static mixed getClassObjectFromFile($filePathName)
 * @method static string getClassFullNameFromFile($filePathName)
 * @method static array getSectionPaths()
 * @method static mixed getClassType($className)
 * @method static array getAllContainerNames()
 * @method static array getAllContainerPaths()
 * @method static array getSectionNames()
 * @method static array getSectionContainerPaths(string $sectionName)
 * @method static void verifyClassExist(string $className)
 * @const SHIP_NAME
 * @const VERSION
 *
 * @see \Nucleus\Foundation\Nuclear
 */
class Nuclear extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Nuclear';
    }
}
