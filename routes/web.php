<?php

use App\Models\User;
use Orchid\Platform\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Notifications\NewsForOperator;
use Illuminate\Support\Facades\Notification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    $notification = new NewsForOperator('Новый оператор в штат', 'Мы наняли нового оператора - Шуру!');
    //User::find(2)->notify($notification); //Передаем сообщение для пользователя с id=2
    //Массовое уведомление для всех операторов:
    $users = Role::find(1)->getUsers();
    Notification::send($users, $notification);

    return redirect()->route('platform.login');
});
