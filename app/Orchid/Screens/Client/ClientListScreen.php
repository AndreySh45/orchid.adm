<?php

namespace App\Orchid\Screens\Client;

use Carbon\Carbon;
use App\Models\Client;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Http\Requests\ClientRequest;
use Orchid\Screen\Actions\ModalToggle;
use App\Orchid\Layouts\CreateOrUpdateClient;
use App\Orchid\Layouts\Client\ClientListTable;
use App\View\Components\ProdressBoard;

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

    public $permission = 'platform.clients';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $clients = Client::all();
        $interviewedClients = $clients->where('status', 'interviewed'); //Все опрошенные

        $countYesterday = $interviewedClients->filter(function ($client) {
            return $client->updated_at->toDateString() === Carbon::yesterday()->toDateString();
        })->count(); // Опрошенные вчера
        $countToday = $interviewedClients->filter(function ($client) {
            return $client->updated_at->toDateString() === Carbon::now()->toDateString();
        })->count(); // Опрошенные сегодня

        $progressDay = $countToday > 0 ? ($countToday - $countYesterday) / ($countYesterday > 0 ? $countYesterday : 1) * 100 : 0; // Процент улучшения показателя

        return [
            'clients' => Client::filters()->defaultSort('status', 'desc')->paginate(10), //сортировка по умолчанию
            'title'   => 'Результат выполненной работы',
            'percent' => $progressDay,
            'mainDigit' => $interviewedClients->count(),
            'quantityFromOneHundred' => ceil(100 * $interviewedClients->count() / $clients->count())
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
            ModalToggle::make('Новый клиент')->modal('createClient')->method('createOrUpdateClient'),
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
            Layout::component(ProdressBoard::class), //Подключение кастомного компонента
            ClientListTable::class,
            Layout::modal('createClient', CreateOrUpdateClient::class)->title('Добавление нового клиента')->applyButton('Создать'),
            Layout::modal('editClient', CreateOrUpdateClient::class)->async('asyncGetClient')
        ];
    }



    public function asyncGetClient(Client $client): array
    {
        $client->load('invoice');
        return [
          'client' => $client
        ];
    }


    public function createOrUpdateClient(ClientRequest $request): void{
        //dd($request->all());
        $clientId = $request->input('client.id');
        Client::updateOrCreate([
            'id' => $clientId
        ], array_merge($request->validated()['client'], [
            'status' => 'interviewed',
            'invoice_id' => array_shift($request->validated()['client']['invoice_id'])
        ]));


        is_null($clientId) ? Toast::info('Клиент создан') : Toast::info('Клиент обновлен');
    }


}
