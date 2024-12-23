<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use Exception;
use Ramsey\Uuid\Uuid as RamseyUuid;

use function in_array;

/**
 * Trait UUID
 *
 * @package Src\Core\Traits
 * @property string $uniqueKey
 */
trait ModelUUID
{
    /**
     * The "booting" method of the model.
     *
     * @throws Exception
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function (self $model): void {
            if (in_array('uuid', $model->getFillable(), true)) {
                $model->setAttribute('uuid', $model->generateUuid());
            }
        });
    }

    /**
     * @return string
     */
    protected function generateUuid(): string
    {
        return match ($this->uuidVersion()) {
            1 => RamseyUuid::uuid1()->toString(),
            default => RamseyUuid::uuid4()->toString(),
        };
    }

    /**
     * The UUID version to use.
     *
     * @return int
     */
    protected function uuidVersion(): int
    {
        return 4;
    }
}
