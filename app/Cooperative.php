<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Cooperative extends Eloquent
{
    protected $connection = 'mongodb';
	protected $collection = 'cooperative';
    protected $primaryKey = '_id';
    /**@var array
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        '_id','nom','description','rib','region','ville','livraison','techniciens','occasion','especes','adresse','delai_paiement_avance','delai_paiement_reste','categories','isLaid','dateslivraison', 'adresse','parametres','bonus','correction_reçu','delai_avance','delai_complement','delai_reste','delais_paiement', 'id_animateur', 'paiement_rapide','pourcentage_avance',
        "nom_ar",'region_ar',"ville_ar","adresse_ar"
    ];
}

