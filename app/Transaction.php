<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    protected $table = 'transaction';
    protected $primaryKey = 'id';
    protected $fillable = ['id','user_id','transaction_name','transaction_type','orderid','paypal_transactionid','status','amount','currency','paypal_description'];

    public function user()
    {
        return $this->hasOne('\App\User','id','user_id');
    }
}
