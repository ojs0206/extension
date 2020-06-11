<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 't_transaction';
    protected $primaryKey = 'ID';
    protected $fillable = ['ID','user_id'];

    public function user()
    {
        return $this->hasOne('\App\User','id','user_id');
    }
}
