<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    use AsSource; //источник данных макета
    use Filterable; //трейт

    protected $fillable = ['phone', 'name', 'last_name', 'email', 'birthday', 'status', 'service_id', 'assessment'];

    protected $allowedSorts = [
        'status'
    ];
    protected $allowedFilters = [
        'phone'
    ];

    public function setPhoneAttribute($phoneCandidate)
    {
        $this->attributes['phone'] =  str_replace('+', '', PhoneNumber::make($phoneCandidate, 'RU')->formatE164());
    }


}
