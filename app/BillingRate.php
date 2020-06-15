<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillingRate extends Model
{
    protected $table = 't_store_';
    protected $primaryKey = 'id';
    protected $fillable = ['id'];
    public $timestamps = false;
}
