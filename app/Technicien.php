<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Technicien extends Eloquent
{
    protected $connection = 'mongodb';
	protected $collection = 'technicien';
    protected $primaryKey = '_id';

    /**@var array
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        '_id','civilisation','nom','prenom', 'email', 'tel','statut','id_cooperative','eleveurs','id_user','cin', 'livraisons','rib', 'cooperatives', 'agence_bancaire',
        "nom_ar",'prenom_ar'
    ];
}
