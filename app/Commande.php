<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Commande extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'commande';
    protected $primaryKey = '_id';

    /**@var array
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        '_id','id_espece','id_eleveur', 'id_consommateur', 'statut', 'ancien_statut','date_creation', 'reçu_avance','feedback_avance','msg_refus_avance','reçu_montant_restant','feedback_reçu_montant_restant','msg_refus_reste','complement','deadline','rating','date_de_livraison','type_livraison','point_relais', 'ville_livraison', 'avance', 'prix_total','avance_transmis_le','reste_transmis_le','reste','mode_paiement_choisi', 'date_annulation', "date_suppression_max", "isDelivered", "motif_rejet", "justification_rejet", "rating_livraison", "justification_rating_livraison", "rating_produit", "justification_rating_produit","especes","reference","id_cooperative", "isDeliveredTo_Home", "adresse_domicile", "isDeliveredTo_PointRelais", "isTakenFrom_Cooperative","complement_transmis_le", "reçu_montant_complement","feedback_reçu_montant_complement","msg_refus_complement", "id_animateur","details_remboursement"
    ];
}



