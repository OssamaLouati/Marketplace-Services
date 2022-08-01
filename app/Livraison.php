<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Livraison extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'livraison';
    protected $primaryKey = '_id';

    /**@var array
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        '_id','commandes','isDeliveredByMe', "isDeliveredByEleveur", "isDeliveredByChauffeur", "especes_secours","date_livraison","heure_depart","heure_arrive","ville_depart","ville_arrive", "compartiments", "id_technicien","id_livreur"
    ];
}
