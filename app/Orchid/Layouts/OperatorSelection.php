<?php

namespace App\Orchid\Layouts;

use Orchid\Filters\Filter;
use App\Orchid\Filters\AgeFilter;
use App\Orchid\Filters\StatusFilter;
use Orchid\Screen\Layouts\Selection;

class OperatorSelection extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return [
            StatusFilter::class, //Фильтр статуса
            AgeFilter::class  //Фильтр возраста
        ];
    }
}
