<?php

namespace App\Orchid\Filters;

use App\Models\Client;
use Orchid\Screen\Field;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Select;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = ['status'];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Статус';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->where('status', $this->request->get('status'));
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
       return [
            Select::make('status')
            ->options(Client::STATUS)
            ->empty()
            ->value($this->request->get('status'))
            ->title('Статус')
       ];
    }
}
