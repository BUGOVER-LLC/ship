<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Macro;

use Nucleus\Contract\MacrosContract;

abstract class Macro implements MacrosContract
{
    final public static function bind(): void
    {
        app(static::class)->register();
    }
}
