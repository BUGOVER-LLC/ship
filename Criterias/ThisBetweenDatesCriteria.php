<?php

declare(strict_types=1);

namespace Ship\Criterias;

use Ship\Parents\Criterias\Criteria;
use Carbon\Carbon;

class ThisBetweenDatesCriteria extends Criteria
{
    public function __construct(
        private string $field,
        private Carbon $start,
        private Carbon $end,
    ) {
    }

    public function apply($model)
    {
        return $model->whereBetween($this->field, [$this->start->toDateString(), $this->end->toDateString()]);
    }
}
