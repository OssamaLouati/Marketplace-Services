<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Consommateur extends Eloquent
{
    protected $connection = 'mongodb';
	protected $collection = 'consommateur';
    protected $primaryKey = '_id';

    /**@var array
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        '_id','civilisation','nom','prenom', 'tel','email','adresse','favoris','panier','id_user','ville','pays', 'tokenDevice'
    ];
}