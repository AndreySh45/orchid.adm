<?php

namespace App\Models;

use App\Models\Service;
use Orchid\Screen\AsSource;
use Orchid\Metrics\Chartable;
use Orchid\Filters\Filterable;
use Orchid\Attachment\Attachable;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Models\Attachment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    use AsSource; //источник данных макета
    use Filterable; //трейт
    use Chartable; //Для графика
    use Attachable;

    protected $fillable = ['phone', 'name', 'last_name', 'email', 'birthday', 'status', 'service_id', 'assessment', 'invoice_id'];

    protected $allowedSorts = [
        'status'
    ];
    protected $allowedFilters = [
        'phone'
    ];
    public const STATUS = [//для экспорта файла с клиентами
        'interviewed' => 'Опрошен',
        'not_interviewed' => 'Не опрошен'
    ];



    public function setPhoneAttribute($phoneCandidate)
    {
        $this->attributes['phone'] =  make_phone_normalized($phoneCandidate);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

   /*  public function getPhoneAttribute($phoneCandidate)
    {
        return ltrim($phoneCandidate, '7');
    } */
    public function invoice()
    {
        return $this->hasOne(Attachment::class, 'id', 'invoice_id');
    }


}
