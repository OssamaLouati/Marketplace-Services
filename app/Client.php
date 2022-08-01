<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Client extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'clients';

    /**@var array
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        'id','CIN','Nom'
    ];
}



