<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Eleveur extends Eloquent
{
    protected $connection = 'mongodb';
	protected $collection = 'eleveur';
    protected $primaryKey = '_id';

    /**@var array
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        '_id','civilisation','nom','prenom', 'tel','email','adresse','region','rib', 'photo_profil','Especes','id_user','cin','anoc','ville', 'rating',"id_cooperative","isParticipe","id_technicien","id_animateur", "toDelete","code_eleveur","statut"
        ,'nom_ar','prenom_ar','adresse_ar','region_ar','ville_ar'
    ];
}

