<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Mouton extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'mouton';
    protected $primaryKey = '_id';

    /**@var array
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        '_id','boucle','race', 'sexe','poids','localisation','prix','image_face', 'image_profile', 'image_boucle','video','description', 'statut', 'age','date_ajout', 'avance','id_eleveur'
    ];
}
