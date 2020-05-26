<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardPayment extends Model
{
    use SoftDeletes;
    protected $table = 't_card_payment';
    protected $primaryKey = 'id';
    protected $fillable = ['id','user_id','amount','currency'];

    public function user()
    {
        return $this->hasOne('\App\User','id','user_id');
    }
}
