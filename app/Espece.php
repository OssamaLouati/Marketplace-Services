<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Espece extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'espece';
    protected $primaryKey = '_id';

    /**@var array
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        '_id','boucle','boucle_de_naissance','race', 'sexe','poids','localisation','region','prix','image_face', 'image_profile',
         'image_boucle','video','description', 'statut', 'age','date_ajout', 'avance','id_eleveur','categorie','anoc', 'reference',
         'id_cooperative','taille','espece','isParticipe','type','age_max','age_min','boucles','poids_min','poids_max','isMulti',
         "race_ar", 'sexe_ar',"categorie_ar",'localisation_ar',"description_ar","region_ar","espece_ar"
    ];
}
