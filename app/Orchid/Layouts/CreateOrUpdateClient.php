<?php

namespace App\Orchid\Layouts;

use App\Models\Service;
use Orchid\Screen\Field;
use App\Orchid\Fields\Rate;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\DateTimer;

class CreateOrUpdateClient extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        $isClientExist = is_null($this->query->getContent('client')) === false;
        return [
            Input::make('client.id')->type('hidden'),
            Input::make('client.phone')->required()->title('Телефон')->mask('(999) 999-9999')->disabled($isClientExist),
            Group::make([ //размещение в одну строку
                Input::make('client.name')->required()->title('Имя')->placeholder('Имя клиента'),
                Input::make('client.last_name')->required()->title('Фамилия')->placeholder('Фамилия клиента'),
            ]),
            Input::make('client.email')->type('email')->title('Email'),
            DateTimer::make('client.birthday')->required()->format('Y-m-d')->title('День рождения'),
            Relation::make('client.service_id')->fromModel(Service::class, 'name')->title('Тип услуги')->required()
                ->help('Один из видов оказанных услуг для клиента'),
            Select::make('client.assessment')->required()->options([
                'Отлично' => 'Отлично',
                'Хорошо' => 'Хорошо',
                'Удовлетворительно' => 'Удовлетворительно',
                'Отвратительно' => 'Отвратительно'
            ])->help('Реакция на оказанную услугу')->empty('Не известно', 'Не известно'),
            Upload::make('client.invoice_id')
                ->maxFiles(1)
                ->acceptedFiles('.xls, .xlsx')
                ->storage('clients_invoices')
                ->title('Загрузить накладную')
                ->multiple(false)
            /* Rate::make('client.rate') //Подключение кастомного поля для ввода
             ->count(4)
             ->title('Указать рейтинг')
             ->help('Выберете кол-во звезд') */
        ];
    }
}
