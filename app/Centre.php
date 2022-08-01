<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Centre extends Eloquent
{
    protected $connection = 'mongodb';
	protected $collection = 'centre';
    protected $primaryKey = '_id';

    /**@var array
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        '_id','nom','description','service'
    ];
}

