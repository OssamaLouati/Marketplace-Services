<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Commande;
use App\Espece;
use App\Consommateur;
use App\Eleveur;
use App\Cooperative;
use App\Animateur;
use Validator;

class CommandeController extends Controller
{
  /**
     * Get  Commande by id
     * @OA\Get (
     *     path="/api/commande/{id}",
     *     tags={"Commande"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="0101010101"
     *                     ),
     *                     @OA\Property(
     *                         property="point relais",
     *                         type="string",
     *                         example="example point relais"
     *                     ),
     *                     @OA\Property(
     *                         property="Ville Livraison",
     *                         type="string",
     *                         example="example nom"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    /**
     * Get  Commande d'un tech by status
     * @OA\Get (
     *     path="/api/commandes/{id_technicien}/avalider",
     *     tags={"Commande"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id_technicien",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="0101010101"
     *                     ),
     *                     @OA\Property(
     *                         property="point relais",
     *                         type="string",
     *                         example="example point relais"
     *                     ),
     *                     @OA\Property(
     *                         property="Ville Livraison",
     *                         type="string",
     *                         example="example nom"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    /**
     * Get Commande d'un tech by  status
     * @OA\Get (
     *     path="/api/commandes/{id_technicien}/validees",
     *     tags={"Commande"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id_technicien",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="0101010101"
     *                     ),
     *                     @OA\Property(
     *                         property="point relais",
     *                         type="string",
     *                         example="example point relais"
     *                     ),
     *                     @OA\Property(
     *                         property="Ville Livraison",
     *                         type="string",
     *                         example="example nom"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    /**
     * Get tout Commandes d'un tech
     * @OA\Get (
     *     path="/api/commandes/{id_technicien}/toutcommandes",
     *     tags={"Commande"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id_technicien",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="0101010101"
     *                     ),
     *                     @OA\Property(
     *                         property="point relais",
     *                         type="string",
     *                         example="example point relais"
     *                     ),
     *                     @OA\Property(
     *                         property="Ville Livraison",
     *                         type="string",
     *                         example="example nom"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
  /**
     * Get  Commande multi criteria search
     * @OA\Get (
     *     path="/api/commande",
     *     tags={"Commande"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="0101010101"
     *                     ),
     *                     @OA\Property(
     *                         property="point relais",
     *                         type="string",
     *                         example="example point relais"
     *                     ),
     *                     @OA\Property(
     *                         property="Ville Livraison",
     *                         type="string",
     *                         example="example nom"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
  /**
     * Get  Commande multi criteria custom search
     * @OA\Get (
     *     path="/api/commandes/search",
     *     tags={"Commande"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="0101010101"
     *                     ),
     *                     @OA\Property(
     *                         property="point relais",
     *                         type="string",
     *                         example="example point relais"
     *                     ),
     *                     @OA\Property(
     *                         property="Ville Livraison",
     *                         type="string",
     *                         example="example nom"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
  /**
     * Get  Commandes in the dashboard
     * @OA\Get (
     *     path="/api/commandes/dashboard",
     *     tags={"Commande"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="0101010101"
     *                     ),
     *                     @OA\Property(
     *                         property="point relais",
     *                         type="string",
     *                         example="example point relais"
     *                     ),
     *                     @OA\Property(
     *                         property="Ville Livraison",
     *                         type="string",
     *                         example="example nom"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    /**
     * Verify if commande is dispo
     * @OA\Get (
     *     path="/api/command",
     *     tags={"Commande"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="0101010101"
     *                     ),
     *                     @OA\Property(
     *                         property="point relais",
     *                         type="string",
     *                         example="example point relais"
     *                     ),
     *                     @OA\Property(
     *                         property="Ville Livraison",
     *                         type="string",
     *                         example="example nom"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    /**
     * Delete Espece of
     * @OA\Get (
     *     path="/api/deleteEspece/{ides}",
     *     tags={"Commande"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id_technicien",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="0101010101"
     *                     ),
     *                     @OA\Property(
     *                         property="point relais",
     *                         type="string",
     *                         example="example point relais"
     *                     ),
     *                     @OA\Property(
     *                         property="Ville Livraison",
     *                         type="string",
     *                         example="example nom"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    
     /**
     * Delete Commande
     * @OA\Delete (
     *     path="/api/commande/{id}",
     *     tags={"Commande"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Commande supprimé avec success")
     *         )
     *     )
     * )
     */

    public function verifyDisponibility(Request $request) {
        $msg ="";
        $id_espece = $request->input('id_espece');
        $id_consommateur = $request->input('id_consommateur');

        // $cmd = Commande::all();
        $cmd = DB::table('commande') ->get();

        foreach ($cmd as $cmd_item) {
            if ($cmd_item["id_consommateur"] != $id_consommateur && $cmd_item["id_espece"] == $id_espece){
                $espece = Espece::find($cmd_item["id_espece"]);
                if($espece["statut"] == "réservé")
                $msg = "Déjà vendu";
            } else if ($cmd_item["id_consommateur"] == $id_consommateur && $cmd_item["id_espece"] == $id_espece) {
                $msg = "réservé par moi même";
            }
        }
        return $msg;
    }

    public function getCommandesDashboard(Request $request)
    {
       $id_consommateur = $request->input('id_consommateur');

       $out = [];
       $deadlines = [];
       $delivery = [];
       $counts = [];

       $cmdValideeDateLivraisonProche;
       $cmdCashValideeDateLivraisonProche;

       $cmdAvancesEnAttenteDePaiement = [];
       $cmdResteEnAttenteDePaiement = [];
       $cmdComplementEnAttenteDePaiement = [];
       $cmdCashEnAttenteDePaiement = [];

       $cmdValidee = [];
       $cmdCashValidee = [];


        $cmdAvancesEnAttenteDePaiement = Commande::select([
            'id_consommateur',
            'statut',
            'deadline',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
          ->where('statut', "en attente de paiement avance")
          ->orderBy("deadline", "desc")
          ->get();

        $cmdResteEnAttenteDePaiement = Commande::select([
            'id_consommateur',
            'statut',
            'deadline',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
          ->where('statut', "en attente de paiement du reste")
          ->orderBy("deadline", "desc")
          ->get();

          $cmdComplementEnAttenteDePaiement = Commande::select([
            'id_consommateur',
            'statut',
            'deadline',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
          ->where('statut', "en attente de paiement du complement")
          ->orderBy("deadline", "desc")
          ->get();

          $cmdCashEnAttenteDePaiement = Commande::select([
            'id_consommateur',
            'statut',
            'deadline',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
          ->where('statut', "en attente de paiement cash")
          ->orderBy("deadline", "desc")
          ->get();

          $cmdValidee = Commande::select([
            'id_consommateur',
            'statut',
            'date_de_livraison',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
          ->where('statut', "validé")
          ->orderBy("date_de_livraison", "desc")
          ->get();

          $cmdCashValidee = Commande::select([
            'id_consommateur',
            'statut',
            'date_de_livraison',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
          ->where('statut', "paiement cash effectué")
          ->orderBy("date_de_livraison", "desc")
          ->get();
          
          /******************************************** deadlines ***********************************************************************************/

          //cmdAvancesEnAttenteDePaiement
          if(sizeof($cmdAvancesEnAttenteDePaiement) != 0){
            $cmdAvancesEnAttenteDePaiementDelaiProche = $cmdAvancesEnAttenteDePaiement[count($cmdAvancesEnAttenteDePaiement)-1];
            array_push($deadlines, $cmdAvancesEnAttenteDePaiementDelaiProche["deadline"]);
          } 
          else if(sizeof($cmdAvancesEnAttenteDePaiement) == 0)
            array_push($deadlines, "");

          //cmdResteEnAttenteDePaiement
          if(sizeof($cmdResteEnAttenteDePaiement) != 0){
            $cmdResteEnAttenteDePaiementDelaiProche = $cmdResteEnAttenteDePaiement[count($cmdResteEnAttenteDePaiement)-1];
            array_push($deadlines, $cmdResteEnAttenteDePaiementDelaiProche["deadline"]);
          } 
          else if(sizeof($cmdResteEnAttenteDePaiement) == 0)
            array_push($deadlines, "");

          //cmdComplementEnAttenteDePaiement
          if(sizeof($cmdComplementEnAttenteDePaiement) != 0){
            $cmdComplementEnAttenteDePaiementDelaiProche = $cmdComplementEnAttenteDePaiement[count($cmdComplementEnAttenteDePaiement)-1];
            array_push($deadlines, $cmdComplementEnAttenteDePaiementDelaiProche["deadline"]);
          } 
          else if(sizeof($cmdComplementEnAttenteDePaiement) == 0)
            array_push($deadlines, "");

          //cmdCashEnAttenteDePaiement
          if(sizeof($cmdCashEnAttenteDePaiement) != 0){
            $cmdCashEnAttenteDePaiementDelaiProche = $cmdCashEnAttenteDePaiement[count($cmdCashEnAttenteDePaiement)-1];
            array_push($deadlines, $cmdCashEnAttenteDePaiementDelaiProche["deadline"]);
          } 
          else if(sizeof($cmdCashEnAttenteDePaiement) == 0)
            array_push($deadlines, "");

          /******************************************** delivery ***********************************************************************************/

          //cmdValidee
          if(sizeof($cmdValidee) != 0){
              $cmdValideeDateLivraisonProche = $cmdValidee[count($cmdValidee)-1];
              // array_push($delivery, $cmdValideeDateLivraisonProche["date_de_livraison"]);
              array_push($delivery,$cmdValideeDateLivraisonProche["date_de_livraison"]);
          } 
          else if(sizeof($cmdValidee) == 0)
               // array_push($delivery, "");
            array_push($delivery,"");

          //cmdCashValidee
          if(sizeof($cmdCashValidee) != 0){
              $cmdCashValideeDateLivraisonProche = $cmdCashValidee[count($cmdCashValidee)-1];
              array_push($delivery,$cmdCashValideeDateLivraisonProche["date_de_livraison"]);
          } 
          else if(sizeof($cmdCashValidee) == 0)
               // array_push($delivery, "");
            array_push($delivery,"");

          /******************************************** counts ***********************************************************************************/

          $cmdsAnnulees = Commande::select([
            'id_consommateur',
            'statut',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
          ->whereIn(
            'statut', ["commande annulée (deadline dépassé)", "reçu avance refusé", "reçu reste refusé", "annulée manuellement", "avarié", "avarié_remboursement", "avarié_annulée", "rejetée"])
          ->get();

          //0
          array_push($counts, sizeof($cmdsAnnulees));

          $cmdsAvanceAP = Commande::select([
            'id_consommateur',
            'statut',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
          ->where(function ($query) {
             $query ->where('statut', "en attente de paiement avance")
                    ->orWhere(function ($q) {
                     $q->where('ancien_statut', "en attente de paiement avance")
                           ->where('statut', "avarié_changement");
                      });
          })->get();

          //1
          array_push($counts, sizeof($cmdsAvanceAP));

          $cmdsAvanceAValider = Commande::select([
            'id_consommateur',
            'statut',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
          ->where('statut', "en attente de validation avance")
          ->get();

          //2
          array_push($counts, sizeof($cmdsAvanceAValider));

          $cmdAvancesValidees = Commande::select([
            'id_consommateur',
            'statut',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
            ->where(function ($query) {
             $query ->where('statut', "en attente de paiement du reste")
                    ->orWhere(function ($q) {
                     $q->where('ancien_statut', "en attente de paiement du reste")
                           ->where('statut', "avarié_changement");
                      });
          })->get();

          //3
          array_push($counts, sizeof($cmdAvancesValidees));

          $cmdPaiementFinalvalide = Commande::select([
            'id_consommateur',
            'statut',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
          ->where(function ($query) {
             $query ->whereIn('statut', ["validé" , "en attente de validation reste", "en attente de validation complément"])
                    ->orWhere(function ($q) {
                     $q->where('ancien_statut', "validé")
                       ->where('statut', "avarié_changement");
                      });
          })->get();

          //4
          array_push($counts, sizeof($cmdPaiementFinalvalide));

          $cmdsComplementAP = Commande::select([
            'id_consommateur',
            'statut',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
          ->where('statut', "en attente de paiement du complement")
          ->get();

          //5
          array_push($counts, sizeof($cmdsComplementAP));

          $cmdsCashAP = Commande::select([
            'id_consommateur',
            'statut',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
            ->where('statut', "en attente de paiement cash")
            ->get();

          //6
          array_push($counts, sizeof($cmdsCashAP));

          $cmdsCashVal = Commande::select([
            'id_consommateur',
            'statut',
          ])->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
            ->where('statut', "paiement cash effectué")
            ->get();

          //7
          array_push($counts, sizeof($cmdsCashVal));

          $out["deadline"] = $deadlines;
          $out["delivery"] = $delivery;
          $out["count"] = $counts;

          return $out;
    }

    public function search(Request $request)
    {
        $id_espece = $request->input('id_espece');
        $id_eleveur = $request->input('id_eleveur');
        $id_consommateur = $request->input('id_consommateur');
        $id_cooperative = $request->input('id_cooperative');
        $id_animateur= $request->input('id_animateur');
        $deadline = $request->input('deadline');
        $statut = $request->input('statut');
        $ancien_statut = $request->input('ancien_statut');
       $mode_paiement_choisi=$request->input('mode_paiement_choisi');

        $point_relais=$request->input('point_relais');
        $reçu_avance=$request->input('reçu_avance');
        $avance_transmis_le = $request->input('avance_transmis_le');
        $avance = $request->input('avance');
        $reste = $request->input('reste');
        $reste_transmis_le = $request->input('reste_transmis_le');
        $feedback_avance=$request->input('feedback_avance');
        $msg_refus_avance=$request->input('msg_refus_avance');
        $reçu_montant_restant=$request->input('reçu_montant_restant');
        $feedback_reçu_montant_restant=$request->input('feedback_reçu_montant_restant');
        $msg_refus_reste=$request->input('msg_refus_reste');
        $date_creation = $request->input('date_creation');
        $rating= $request->input('rating'); 
        $date_de_livraison= $request->input('date_de_livraison'); 
        $type_livraison= $request->input('type_livraison'); 
        $adresse_domicile= $request->input('adresse_domicile');
        $ville_livraison= $request->input('ville_livraison'); 
        $statut_validation= $request->input('statut_validation');
        $order_by= $request->input('order_by');  //specify in the request body the attribute to use for sorting
        $order_mode= $request->input('order_mode'); //specify in the request body the mode of sorting : asc or desc
        // $order_avance= $request->input('order_avance'); //specify in the request body the mode of sorting : asc or desc
        
       $cmd = Commande::select([
        'id_espece',
        'id_eleveur',
        'id_consommateur',
        'id_cooperative',
        'id_animateur',

        'mode_paiement_choisi',
        'reference',
     
        'statut',
        'ancien_statut',

        'deadline',
        'complement',
        'reste',
        'avance',
        'prix_total',
        
        'reçu_avance',
        'feedback_avance',
        'msg_refus_avance',

        'reçu_montant_restant',
        'feedback_reçu_montant_restant',
        'msg_refus_reste',

        'reçu_montant_complement',
        'feedback_reçu_montant_complement',
        'msg_refus_complement',

        'date_creation',
        'rating',
        'details_remboursement',
        'date_de_livraison',
        'type_livraison',
        'ville_livraison',
        'adresse_domicile',
        'point_relais',
        'isDelivered',

        'avance_transmis_le',
        'reste_transmis_le',
        'complement_transmis_le',

        'especes',
      

        ])
        // ->when($id_espece, function ($query, $id_espece) {return $query->where('id_espece', $id_espece); })
         ->when($id_eleveur, function ($query, $id_eleveur) {return $query->where('id_eleveur', $id_eleveur); })
         ->when($id_consommateur, function ($query, $id_consommateur) {return $query->where('id_consommateur', $id_consommateur); })
         ->when($id_cooperative, function ($query, $id_cooperative) {return $query->where('id_cooperative', $id_cooperative); })
         ->when($id_animateur, function ($query, $id_animateur) {return $query->where('id_animateur', $id_animateur); })
         //->when($prix, function ($query, $prix) {return $query->where('prix', $prix); })
         ->when($deadline, function ($query, $deadline) {return $query->where('deadline',$deadline); })
         ->when($avance_transmis_le, function ($query, $avance_transmis_le) {return $query->where('avance_transmis_le',$avance_transmis_le); })
         ->when($statut, function ($query, $statut) {return $query->where('statut',$statut); })
         ->when($ancien_statut, function ($query, $ancien_statut) {return $query->where('ancien_statut',$ancien_statut); })
         ->when($mode_paiement_choisi, function ($query, $mode_paiement_choisi) {return $query->where('mode_paiement_choisi',$mode_paiement_choisi); })

         ->when($reste_transmis_le, function ($query, $reste_transmis_le) {return $query->where('reste_transmis_le',$reste_transmis_le); })
         ->when($reste, function ($query, $reste) {return $query->where('reste',$reste); })
         ->when($avance, function ($query, $avance) {return $query->where('avance',$avance); })

         ->when($point_relais, function ($query, $point_relais) {return $query->where('point_relais',$point_relais); })
         //->when($reçu_avance, function ($query, $reçu_avance) {return $query->where('reçu_avance',$reçu_avance); })
         ->when($feedback_avance, function ($query, $feedback_avance) {return $query->where('feedback_avance',$feedback_avance); })
         ->when($msg_refus_avance, function ($query, $msg_refus_avance) {return $query->where('msg_refus_avance',$msg_refus_avance); })
        // ->when($reçu_montant_restant, function ($query, $reçu_montant_restant) {return $query->where('reçu_montant_restant',$reçu_montant_restant); })
         ->when($feedback_reçu_montant_restant, function ($query, $feedback_reçu_montant_restant) {return $query->where('feedback_reçu_montant_restant',$feedback_reçu_montant_restant); })
        ->when($msg_refus_reste, function ($query, $msg_refus_reste) {return $query->where('msg_refus_reste',$msg_refus_reste); })
         ->when($date_creation, function ($query, $date_creation) {return $query->where('date_creation',$date_creation); })
         ->when($rating, function ($query, $rating) {return $query->where('rating',$rating); })
         ->when($date_de_livraison, function ($query, $date_de_livraison) {return $query->where('date_de_livraison',$date_de_livraison); })
         ->when($date_de_livraison, function ($query, $type_livraison) {return $query->where('type_livraison',$type_livraison); })
         ->when($ville_livraison, function ($query, $ville_livraison) {return $query->where('ville_livraison',$ville_livraison); })
         ->when($statut_validation,function($query,$statut_validation){return $query->whereIn('statut',$statut_validation); })
         ->when($adresse_domicile, function ($query, $adresse_domicile) {return $query->where('adresse_domicile',$adresse_domicile); })

         ->orderBy($order_by, $order_mode)
         -> get();

        foreach($cmd as $cmd_item){
                //error_log($cmd_item);
                $esp=[];
                $elev=[];
                $espav=[];
                foreach ($cmd_item->especes as $e) {
                 
                 
                    $espec = Espece::find($e["id_espece"]);
                    $elevec = Eleveur::find($e["id_eleveur"]);
                    array_push($elev,$elevec);
                    array_push($esp,$espec);
                    if($cmd_item["statut"]=="avarié"||$cmd_item["statut"]=="avarié_changement"||$cmd_item["statut"]=="avarié_remboursement"||$cmd_item["statut"]=="avarié_remboursement"||$cmd_item["statut"]=="en attente de paiement du complement"){
                    foreach($e["produits_changement"] as $ee){
                        $espavc = Espece::find($ee["id_espece"]);
                        array_push($espav,$espavc);
                    }}
                }
                $consom = Consommateur::where('id_user', '=', $cmd_item ->id_consommateur)->first();
                $coop = Cooperative::find($cmd_item->id_cooperative);
                $anim = Animateur::where('id_user', '=', $cmd_item ->id_animateur)->first();
                
                //error_log($espece);
                //error_log($elev);
                //error_log($consom);
                $cmd_item["espece_avariee"] = $espav;
                $cmd_item["espece"] = $esp;
                $cmd_item["eleveur"] = $elev;
                $cmd_item["consommateur"] = $consom;
                $cmd_item["cooperative"] = $coop;
                $cmd_item["animateur"] = $anim;

                if($cmd_item->statut=='en attente de validation reste'){
                    $cmd_item["last_transmis"] = $cmd_item->reste_transmis_le;
                }
                if($cmd_item->statut=='en attente de validation avance'){
                    $cmd_item["last_transmis"] = $cmd_item->avance_transmis_le;
                }
                if($cmd_item->statut=='en attente de validation complément'){
                    $cmd_item["last_transmis"] = $cmd_item->complement_transmis_le;
                }
        }

        // $collection = collect(json_decode($cmd, true));
       // if ($order_avance =="asc") {
       //      // Log::info($cmd[0]->espece->avance);
       //      // $cmd = $collection->sortBy('deadline');
       //      // $cmd->espece->sortBy('avance');
       //      // $cmd = $cmd->sortBy(function($cmdItem ) {
       //      //       return $cmdItem->espece->avance;
       //      // });

       //      // $cmd = $collection::select()->orderBy("espece.avance", "asc") -> get();
       //      $cmd = $collection::select()->orderBy('deadline', 'asc') -> get();

       //      // $cmd = $collection::select()->sortBy("espece.avance") -> get();
       //      // $cmd = $cmd::select()->sortBy("deadline") -> get();

       // } else if($order_avance =="desc"){
       //      // $cmd->sortByDesc($cmd->espece['avance']);
       //      $cmd = $collection->sortByDesc('avance');
       // }

       if($request->has("id_espece")){
        $cmd_filtre=[];
       
        foreach($cmd as $cmd_item){
          foreach ($cmd_item->especes as $e) {
              // error_log($e->boucle);
              if ($e["id_espece"] == $id_espece)
                  // $resp = $cmd_item;
                  array_push($cmd_filtre,$cmd_item);

              // else
              //     $resultat["msg"] ="Aucune commande trouvée avec cette boucle";
          }
        }
       $cmd = $cmd_filtre;

       }

        return $cmd;
    }

    public function customSearch(Request $request)
    {
        $resultat = [];
        $boucle = $request->input('boucle_espece');
        $nomClient = $request->input('nom_client');
        $id_cooperative = $request->input('id_cooperative');
        $id_technicien = $request->input('id_technicien');
        $id_animateur = $request->input('id_animateur');
        $id_eleveur = $request->input('id_eleveur');
        $nomEleveur = $request->input('eleveur');
        $espece = $request->input('espece');
        $race = $request->input('race');
        $statut = $request->input('statut');
        $mode_paiement_choisi= $request->input('mode_paiement_choisi');
        $prenomClient = $request->input('prenom_client');
        $villeLivraison = $request->input('ville_livraison');
        $typeLivraison  = $request->input('type_livraison');
        $statut_validation= $request->input('statut_validation');
        $order_by= $request->input('order_by');  //specify in the request body the attribute to use for sorting
        $order_mode= $request->input('order_mode'); //specify in the request body the mode of sorting : asc or desc
        // $order_avance= $request->input('order_avance'); //specify in the request body the mode of sorting : asc or desc

       $cmd = Commande::select([
        'id_espece',
        'id_eleveur',
        'id_consommateur',
        'id_cooperative',
        // 'id_technicien',
        'id_animateur',

        'mode_paiement_choisi',
        'reference',
     
        'statut',
        'ancien_statut',

        'deadline',
        'complement',
        'reste',
        'avance',
        'prix_total',
        
        'reçu_avance',
        'feedback_avance',
        'msg_refus_avance',

        'reçu_montant_restant',
        'feedback_reçu_montant_restant',
        'msg_refus_reste',

        'reçu_montant_complement',
        'feedback_reçu_montant_complement',
        'msg_refus_complement',

        'date_creation',
        'rating',
        'details_remboursement',
        'date_de_livraison',
        'type_livraison',
        'ville_livraison',
        'adresse_domicile',
        'point_relais',
        'isDelivered',

        'avance_transmis_le',
        'reste_transmis_le',
        'complement_transmis_le',

        'especes',
      

        ])
         // ->when($id_espece, function ($query, $id_espece) {return $query->where('id_espece', $id_espece); })
         // ->orderBy($order_by, $order_mode)
        // -> orderBy("date_de_livraison", "desc")
        // ->when($id_technicien, function ($query, $id_technicien) {return $query->where('id_technicien',$id_technicien); })
        ->when($id_cooperative, function ($query, $id_cooperative) {return $query->where('id_cooperative',$id_cooperative); })
        ->when($statut, function ($query, $statut) {return $query->where('statut',$statut); })
        ->when($id_animateur, function ($query, $id_animateur) {return $query->where('id_animateur',$id_animateur); })
        ->when($statut_validation,function($query,$statut_validation){return $query->whereIn('statut',$statut_validation); })

        -> get();
       
        foreach($cmd as $cmd_item){
                //error_log($cmd_item);
                $esp=[];
                $elev=[];
                $espav=[];
                foreach ($cmd_item->especes as $e) {
                 
                 
                    $espec = Espece::find($e["id_espece"]);
                    $elevec = Eleveur::find($e["id_eleveur"]);
                    array_push($elev,$elevec);
                    array_push($esp,$espec);
                    if($cmd_item["statut"]=="avarié"||$cmd_item["statut"]=="avarié_changement"||$cmd_item["statut"]=="avarié_remboursement"||$cmd_item["statut"]=="avarié_remboursement"||$cmd_item["statut"]=="en attente de paiement du complement"){
                    foreach($e["produits_changement"] as $ee){
                        $espavc = Espece::find($ee["id_espece"]);
                        array_push($espav,$espavc);
                    }}
                }
                $consom = Consommateur::where('id_user', '=', $cmd_item ->id_consommateur)->first();
                $cmd_item["espece"] = $esp;
                $cmd_item["consommateur"] = $consom;
                $cmd_item["eleveur"] = $elev;
                $cmd_item["espece_avariee"] = $espav;
        }

        error_log(sizeof($cmd));
        
        
                //error_log($cmd_item);
                // $esp=[];
                $resultat =$cmd;

                if ($request->has('boucle_espece')) {
                  $tmp = [];
                  foreach($resultat as $cmd_item){
                    foreach ($cmd_item->espece as $e) {
                        error_log($e->boucle);
                        if ($e->boucle == $boucle)
                            // $resp = $cmd_item;
                            array_push($tmp,$cmd_item);

                        // else
                        //     $resultat["msg"] ="Aucune commande trouvée avec cette boucle";
                    }
                  }
                  $resultat = $tmp;
                }
                if ($request->has('statut')) {
                  $tmp = [];
                  foreach($resultat as $cmd_item){
                      error_log($cmd_item->statut);
                      if ($cmd_item->statut== $statut)
                          // $resp = $cmd_item;
                          array_push($tmp,$cmd_item);
                      // else
                      //     $resultat["msg"] ="Aucune commande trouvée avec cette boucle";
                  }
                  $resultat = $tmp;
              }
              if ($request->has('mode_paiement_choisi')) {
                $tmp = [];
                foreach($resultat as $cmd_item){
                    error_log($cmd_item->mode_paiement_choisi);
                    if ($cmd_item->mode_paiement_choisi== $mode_paiement_choisi)
                        // $resp = $cmd_item;
                        array_push($tmp,$cmd_item);
                    // else
                    //     $resultat["msg"] ="Aucune commande trouvée avec cette boucle";
                }
                $resultat = $tmp;
            }
                if ($request->has('espece')) {
                  $tmp = [];
                  foreach($resultat as $cmd_item){
                  foreach ($cmd_item->espece as $e) {
                      error_log($e->espece);
                      if ($e->espece== $espece){
                        array_push($tmp,$cmd_item);
                      }
                          // $resp = $cmd_item;
                          
                      // else
                      //     $resultat["msg"] ="Aucune commande trouvée avec cette boucle";
                  }
                } 
                $resultat = $tmp;
              }
              
              if ($request->has('race')) {
                $tmp = [];
                foreach($resultat as $cmd_item){
                foreach ($cmd_item->espece as $e) {
                    error_log($e->race);
                    if ($e->race== $race)
                        // $resp = $cmd_item;
                        array_push($tmp,$cmd_item);
                    // else
                    //     $resultat["msg"] ="Aucune commande trouvée avec cette boucle";
                }
              }
              $resultat = $tmp;
            }
                if ($request->has('eleveur')) {
                $id_eleveur=[];
                $eleveur_chercher = strtolower($request->input('eleveur'));
                $splitName = explode(' ', $eleveur_chercher);
                $eleveurs=Eleveur::all();
                foreach($eleveurs as $elv){
               $eleveur_nom=explode(' ', strtolower($elv["nom"]));
               $eleveur_prenom=explode(' ', strtolower($elv["prenom"]));
               $eleveur=array_merge($eleveur_nom,$eleveur_prenom);
               $found=count(array_intersect($eleveur,$splitName)) >0 && count($splitName) === count(array_intersect($eleveur,$splitName)) ;

               if($found==true)
                        {   array_push($id_eleveur, $elv["_id"]);} }

                  $tmp = [];
                  foreach($resultat as $cmd_item){
                    foreach ($cmd_item->especes as $e) {
                     
                        if (in_array($e["id_eleveur"],$id_eleveur))
                        
                            array_push($tmp,$cmd_item);
                    }
                  }
$resultat = $tmp;
              }

              if ($request->has('id_eleveur')) {
                $tmp = [];

                foreach($resultat as $cmd_item){
                foreach ($cmd_item->especes as $e) {
                    // error_log($e->nom);
                    if ($e["id_eleveur"] == $id_eleveur)
                        // $resp = $cmd_item;
                        array_push($tmp,$cmd_item);
                    // else
                    //     $resultat["msg"] ="Aucune commande trouvée avec cette boucle";
                }
              }
$resultat = $tmp;
            }
              if ($request->has('nom_prenom_client')) {
                $tmp = [];
                $client_chercher = strtolower($request->input('nom_prenom_client'));
                $splitName = explode(' ', $client_chercher);
                foreach($resultat as $cmd_item){
         
                  $client_nom=explode(' ', strtolower($cmd_item->consommateur->nom));
                  $client_prenom=explode(' ', strtolower($cmd_item->consommateur->prenom));
                  $client=array_merge($client_nom,$client_prenom);

                  $found=count(array_intersect($client,$splitName)) === count($client);

                  if ($found==true)
                    {  array_push($tmp,$cmd_item);}}
                 
                      // else
                  //     $resultat["msg"] ="Aucune commande trouvée avec ce nom client";
                
$resultat = $tmp;
              }
                if ($request->has('nom_client')) {
                  $tmp = [];

                  foreach($resultat as $cmd_item){
                    if ($cmd_item->consommateur->nom == $nomClient)
                        array_push($tmp,$cmd_item);
                   
                        // else
                    //     $resultat["msg"] ="Aucune commande trouvée avec ce nom client";
                  }
$resultat = $tmp;
                }


                if ($request->has('prenom_client')) {
                  $tmp = [];

                  foreach($resultat as $cmd_item){
                    if ($cmd_item->consommateur->prenom == $prenomClient)
                        array_push($tmp,$cmd_item);
                    // else
                    //     $resultat["msg"] ="Aucune commande trouvée avec ce prénom client";
                  }
$resultat = $tmp;
                }

                if ($request->has('ville_livraison') ) {
                  $tmp = [];

                  foreach($resultat as $cmd_item){
                  foreach($villeLivraison as $tmpVille){
                    
                    if ($cmd_item->ville_livraison == $tmpVille)
                        
                        array_push($tmp,$cmd_item);
                  }
                    
                    // else
                    //     $resultat["msg"] ="Aucune commande trouvée avec cette ville de livraison";
                  }
                $resultat = $tmp;
                }

                if ($request->has('ville_livraison') == false && $request->has('type_livraison')) {
                  $tmp = [];

                  foreach($resultat as $cmd_item){
                    if ($typeLivraison == "relais") {
                            if ($cmd_item->isDeliveredTo_PointRelais == true)
                                array_push( $tmp,$cmd_item);
                            // else
                            //     $resultat["msg"] ="Aucune commande trouvée avec livraison au point relais";
                    }

                    if($typeLivraison == "domicile"){
                            if ($cmd_item->isDeliveredTo_Home == true)
                                array_push($resultat,$cmd_item);
                            // else
                            //     $resultat["msg"] ="Aucune commande trouvée avec livraison à domicile";
                    }
                  }
                  $resultat = $tmp;
                }

                if ($request->has('ville_livraison') && $request->has('type_livraison')) {
                  $tmp = [];
                  foreach($resultat as $cmd_item){
                    if ($typeLivraison == "relais") {
                            if ($cmd_item->ville_livraison == $villeLivraison && $cmd_item->isDeliveredTo_PointRelais == true)
                                array_push($tmp,$cmd_item);
                            // else
                            //     $resultat["msg"] ="Aucune commande trouvée avec livraison au point relais";
                    }

                    if($typeLivraison == "domicile"){
                            if ($cmd_item->ville_livraison == $villeLivraison && $cmd_item->isDeliveredTo_Home == true)
                                array_push($tmp,$cmd_item);
                            // else
                            //     $resultat["msg"] ="Aucune commande trouvée avec livraison à domicile";
                    }
                  }
                  $resultat = $tmp;
                }
        

    

        /*foreach($resultat as $cmd_item){
                $grouped_cmds_byDate[$cmd_item['date_de_livraison']][] = $cmd_item;
        }
        if ($request->has('statut')&&count($resultat)==0) {
                 
          $resultat=$cmd;
     
  }*/
//   if (!count($resultat)==0) {
                 
//     $resultat=array_unique($resultat);
//     $temp = [];
//         foreach ($resultat as $key => $value)
//         {
//             array_push($temp, $resultat[$key]);
//         }

//         $resultat = $temp;

// }

         return $resultat;

        // return (array) $resultat;
    }
}
