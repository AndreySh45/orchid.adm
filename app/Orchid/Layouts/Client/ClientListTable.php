<?php

namespace App\Orchid\Layouts\Client;

use Carbon\Carbon;
use Orchid\Screen\TD;
use App\Models\Client;
use Carbon\CarbonPeriod;
use Orchid\Screen\Layouts\Table;

class ClientListTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'clients';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('phone', 'Телефон')->width('150px')->cantHide()->canSee($this->isWorkTime())->filter(TD::FILTER_TEXT), //скрытие колонки при внешних условий
            TD::make('status', 'Статус')->render(function (Client $client) {
                return $client->status === 'interviewed' ? 'Опрошен' : 'Не опрошен'; //можно использовать гетеры
            })->width('150px')->popover('Статус по результатам работы оператора')->sort(), //Подсказка и возможность переключить сортировку
            TD::make('email', 'Email'),
            TD::make('name', 'Имя клиента')->width('300px')->align(TD::ALIGN_LEFT)->defaultHidden(),
            TD::make('assessment', 'Оценка')->width('200px')->align(TD::ALIGN_RIGHT),
            TD::make('created_at', 'Дата создания')->defaultHidden(), //по умолчанию скрыто, можно показать
            TD::make('updated_at', 'Дата обновления')->defaultHidden()
        ];
    }

    //Чтоб нельзя было работать о время обеденного перерыва
    private function isWorkTime():bool
    {
        $lunch = CarbonPeriod::create('12:00', '13:00');
        return $lunch->contains(Carbon::now(config('app.timezone'))) === false;
    }
}
