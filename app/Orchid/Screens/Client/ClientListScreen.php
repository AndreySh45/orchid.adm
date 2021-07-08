<?php

namespace App\Orchid\Screens\Client;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Service;
use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Actions\ModalToggle;
use App\Orchid\Layouts\Client\ClientListTable;
use Illuminate\Http\Request;

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
            'clients' => Client::filters()->defaultSort('status', 'desc')->paginate(10) //сортировка по умолчанию
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            ModalToggle::make('Новый клиент')->modal('createClient')->method('create')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            ClientListTable::class,
            Layout::modal('createClient', Layout::rows([
                Input::make('phone')->required()->title('Телефон')->mask('(999) 999-9999'),
                Group::make([ //размещение в одну строку
                    Input::make('name')->required()->title('Имя'),
                    Input::make('last_name')->required()->title('Фамилия'),
                ]),
                Input::make('email')->type('email')->title('Email'),
                DateTimer::make('birthday')->required()->format('Y-m-d')->title('День рождения'),
                Relation::make('service_id')->fromModel(Service::class, 'name')->title('Тип услуги')->required()
            ]))->title('Добавление нового клиента')->applyButton('Создать')
        ];
    }

    public function create(ClientRequest $request){
        Client::create(array_merge($request->validated(), [
            'status' => 'interviewed'
        ]));
        Toast::info('Клиент успешно создан');
    }


}
