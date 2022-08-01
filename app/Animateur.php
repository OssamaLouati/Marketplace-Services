<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Animateur extends Eloquent
{
    protected $connection = 'mongodb';
	protected $collection = 'animateur';
    protected $primaryKey = '_id';

    /**@var array
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        '_id','civilisation','nom','prenom', 'email', 'tel','statut','id_user','parametrage_global', 'cooperatives'
    ];
}
