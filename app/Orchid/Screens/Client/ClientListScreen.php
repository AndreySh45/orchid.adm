<?php

namespace App\Orchid\Screens\Client;

use App\Models\Client;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ClientListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Клиенты';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Список клиентов';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'clients' => Client::paginate(10)
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::table('clients', [
                TD::make('phone', 'Телефон')->width('150px')->cantHide()->canSee($this->isWorkTime()), //скрытие колонки при внешних условий
                TD::make('status', 'Статус')->render(function (Client $client) {
                    return $client->status === 'interviewed' ? 'Опрошен' : 'Не опрошен'; //можно использовать гетеры
                })->width('150px')->popover('Статус по результатам работы оператора'), //Подсказка
                TD::make('email', 'Email'),
                TD::make('assessment', 'Оценка')->width('200px')->align(TD::ALIGN_RIGHT),
                TD::make('created_at', 'Дата создания')->defaultHidden(), //по умолчанию скрыто, можно показать
                TD::make('updated_at', 'Дата обновления')->defaultHidden()
            ])
        ];
    }

    //Чтоб нельзя было работать о время обеденного перерыва
    private function isWorkTime():bool
    {
        $lunch = CarbonPeriod::create('13:00', '14:00');
        return $lunch->contains(Carbon::now(config('app.timezone'))) === false;
    }
}