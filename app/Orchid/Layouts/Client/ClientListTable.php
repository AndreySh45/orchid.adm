<?php

namespace App\Orchid\Layouts\Client;

use Carbon\Carbon;
use Orchid\Screen\TD;
use App\Models\Client;
use Carbon\CarbonPeriod;
use App\Orchid\Fields\Rate;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\ModalToggle;

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
                return Client::STATUS[$client->status]; //используем замену
            })->width('150px')->popover('Статус по результатам работы оператора')->sort(), //Подсказка и возможность переключить сортировку
            TD::make('email', 'Email'),
            TD::make('name', 'Имя клиента')->width('300px')->align(TD::ALIGN_LEFT)->defaultHidden(),
            TD::make('assessment', 'Оценка')->width('300px')->render(function (Client $client) {
                $numberAssessment = [
                    'Отлично' => 4,
                    'Хорошо' => 3,
                    'Удовлетворительно' => 2,
                    'Отвратительно' => 1,
                    'Не известно' => 0
                ];
                return Rate::make('rate')
                       ->count(4)
                       ->readonly(true)
                       ->haveRated($numberAssessment[$client->assessment] ?? 0);
            }),
            TD::make('created_at', 'Дата создания')->defaultHidden(), //по умолчанию скрыто, можно показать
            TD::make('updated_at', 'Дата обновления')->defaultHidden(),
            TD::make('action')->render(function (Client $client) {
                return ModalToggle::make('Редактировать')
                    ->modal('editClient')
                    ->method('createOrUpdateClient')
                    ->modalTitle('Редактирование клиента ' . $client->phone)
                    ->asyncParameters([
                        'client' => $client->id
                    ]);
            })
        ];
    }

    //Чтоб нельзя было работать о время обеденного перерыва
    private function isWorkTime():bool
    {
        $lunch = CarbonPeriod::create('15:00', '16:00');
        return $lunch->contains(Carbon::now(config('app.timezone'))) === false;
    }
}
