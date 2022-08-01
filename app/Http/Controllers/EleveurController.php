<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Eleveur; 
use App\Technicien;
use App\Espece;
use App\Commande;
use App\Cooperative;
use Illuminate\Support\Str;

class EleveurController extends Controller 
{
     /**
     * Get all Eleveurs
     * @OA\Get (
     *     path="/api/eleveurs",
     *     tags={"Eleveur"},
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
     *                         example="000000"
     *                     ),
     *                     @OA\Property(
     *                         property="cin",
     *                         type="string",
     *                         example="example cin"
     *                     ),
     *                     @OA\Property(
     *                         property="nom",
     *                         type="string",
     *                         example="example nom"
     *                     ),
     *                     @OA\Property(
     *                         property="prenom",
     *                         type="string",
     *                         example="example prenom"
     *                     ),
     *                     @OA\Property(
     *                         property="tel",
     *                         type="string",
     *                         example="example tel"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="example email"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    /**
     * Get Detail Eleveur
     * @OA\Get (
     *     path="/api/eleveur/{id}",
     *     tags={"Eleveur"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         description="Insert the user ID here", 
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                         property="cin",
     *                         type="string",
     *                         example="example cin"
     *                     ),
     *                     @OA\Property(
     *                         property="nom",
     *                         type="string",
     *                         example="example nom"
     *                     ),
     *                     @OA\Property(
     *                         property="prenom",
     *                         type="string",
     *                         example="example prenom"
     *                     ),
     *                     @OA\Property(
     *                         property="tel",
     *                         type="string",
     *                         example="example tel"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="example email"
     *                     )
     *         )
     *     )
     * )
     */

    /**
     * Delete Eleveur
     * @OA\Delete (
     *     path="/api/eleveur/{id}",
     *     tags={"Eleveur"},
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
     *             @OA\Property(property="message", type="string", example="Eleveur supprimé avec success")
     *         )
     *     )
     * )
     */
    /**
     * Get the items of an Eleveur
     * @OA\Get (
     *     path="/api/eleveur/{id}/Especes",
     *     tags={"Eleveur"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         description="Insert the Eleveur Id here to get the chart", 
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
     *                         property="especes",
     *                         type="string",
     *                         example="[]"
     *                     ),
     *                     @OA\Property(
     *                          property="updated_at", 
     *                          type="string", 
     *                          example="2021-12-11T09:25:53.000000Z"
     *                     ),
     *                     @OA\Property(
     *                          property="created_at", 
     *                          type="string", 
     *                          example="2021-12-11T09:25:53.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */










    public function search(Request $request) 
    { 
        $nom = $request->input('nom');
        $prenom = $request->input('prenom');
        $tel = $request->input('tel');
        $email = $request->input('email');
        $adresse = $request->input('adresse');
        $region = $request->input('region');
        $ville = $request->input('ville');
        $rib = $request->input('rib');
        $cin = $request->input('cin');
        $anoc = $request->input('anoc');
        $id_animateur=$request->input('id_animateur');
        $isParticipe = $request->input('isParticipe');
        $rating = $request->input('rating');
        $toDelete = $request->input('toDelete');
        $id_animateur = $request->input('id_animateur');
        $order_by= $request->input('order_by');  //specify in the request body the attribute to use for sorting
        $order_mode= $request->input('order_mode'); //specify in the request body the mode of sorting : asc or desc

       $array_elev = Eleveur::select([
        'civilisation',
        'nom',
        'prenom',
        'nom_ar',
        'prenom_ar',
        'tel',
        'email',
        'adresse',
        'adresse_ar',
        'region',
        'region_ar',
        'rib',
        'ville',
        'ville_ar',
        'isParticipe',
        'photo_profil',
        'anoc',
        'rating',
        'id_animateur',
        'id_cooperative',
        'Especes',
        'toDelete',
        'updated_at',
        'created_at',
        ])
         ->when($nom, function ($query, $nom) {return $query->where('nom', $nom); })
         ->when($prenom, function ($query, $prenom) {return $query->where('prenom', $prenom); })
         ->when($tel, function ($query, $tel) {return $query->where('tel', $tel); })
         ->when($email, function ($query, $email) {return $query->where('email', $email); })
         ->when($adresse, function ($query, $adresse) {return $query->where('adresse',$adresse); })
         ->when($region, function ($query, $region) {return $query->where('region',$region); })
         ->when($ville, function ($query, $ville) {return $query->where('ville',$ville); })
         ->when($rib, function ($query, $rib) {return $query->where('rib',$rib); })
         ->when($cin, function ($query, $cin) {return $query->where('cin',$cin); })
         ->when($anoc, function ($query, $anoc) {return $query->where('anoc',$anoc); })
         ->when($rating, function ($query, $rating) {return $query->where('rating',$rating); })
         ->when($id_animateur, function ($query, $id_animateur) {return $query->where('id_animateur',$id_animateur); })
         ->when($isParticipe, function ($query, $isParticipe) {return $query->where('isParticipe',$isParticipe); })
         ->when($toDelete, function ($query, $toDelete) {return $query->where('toDelete',$toDelete); })

         ->orderBy($order_by, $order_mode)
         -> get();

        // foreach ($array_elev as $elv) {
        //     $reserv_array = [];
        //     $dispo_array = [];
        //     //si l'objet tableau elv contient l'attribut Especes
        //     // if(array_key_exists('Especes', $elv)) {
        //     if(isset($elv['Especes']) == "true"){
        //         foreach ($elv['Especes'] as $espece) {
        //             $esp = Espece::find($espece['id_espece']);
        //             if($esp["statut"] == "réservé" )
        //                 array_push($reserv_array, $esp);
        //             else if ($esp["statut"] == "disponible" )
        //                 array_push($dispo_array, $esp);
        //         }
        //         $elv['especes_reserve'] = sizeof($reserv_array);
        //         $elv['especes_dispo'] = sizeof($dispo_array);
        //     } else {
        //         $elv['especes_reserve'] = 0;
        //         $elv['especes_dispo'] = 0;    
        //     }
        // }

        $eleveurs = [];
        if ($request->has('race')) {
            //$tous_les_eleveurs = Eleveur::all();
            foreach ($array_elev as $elv) {
                //si l'objet tableau elv contient l'attribut Especes
                // if(array_key_exists('Especes', $elv)) {
                if(isset($elv['Especes']) == "true"){
                    foreach ($elv['Especes'] as $espece) {
                        $esp = Espece::find($espece['id_espece']);
                        if($esp["race"] == $request->input('race'))
                            array_push($eleveurs, $elv);
                    }
                } 
            }
            $array_elev = array_unique($eleveurs);
        }
        if ($request->has('eleveur_nom_prenom')) {
            //$tous_les_eleveurs = Eleveur::all();
            $eleveurs = [];
            $eleveur_chercher = strtolower($request->input('eleveur_nom_prenom'));
            $splitName = explode(' ', $eleveur_chercher);
            foreach ($array_elev as $elv) {
            $eleveur_nom=explode(' ', strtolower($elv["nom"]));
            $eleveur_prenom=explode(' ', strtolower($elv["prenom"]));
               $eleveur=array_merge($eleveur_nom,$eleveur_prenom);
                   $found=count(array_intersect($eleveur,$splitName)) >0 && count($splitName) === count(array_intersect($eleveur,$splitName)) ;
               if($found==true )
                        {   array_push($eleveurs, $elv);}
            }
            $array_elev = array_unique($eleveurs);
        }
        if ($request->has('cooperative')) {
            //$tous_les_eleveurs = Eleveur::all();
            $eleveurs = [];
            $cooperative=Cooperative::find($request->input('cooperative'));
          
            foreach ($array_elev as $elv) {
                if($elv["id_cooperative"] == $request->input('cooperative'))
                {           
                         array_push($eleveurs, $elv);
                }
                                            }
                       $array_elev = array_unique($eleveurs);
                       
 }

        
 if ($request->has('id_technicien')) {
     $tab=[];
    $tech = Technicien::where('id_user', '=',  $request->input('id_technicien'))->orWhere('_id', '=',  $request->input('id_technicien'))->first();
    $tech_eleveurs = [];
    foreach ($tech["eleveurs"] as $eleveur) {
        $elev = Eleveur::find($eleveur["id_eleveur"]);
        $eleveur["eleveur"] = $elev;
        array_push($tech_eleveurs, $eleveur);}
        foreach ($tech_eleveurs as $el) {  
            foreach ($array_elev as $e) {
       if($el["id_eleveur"]==$e["_id"]){array_push($tab, $e);}
        }}
         $array_elev = $tab;
    }
        if ($request->has('espece')) {
            //$tous_les_eleveurs = Eleveur::all();
            foreach ($array_elev as $elv) {
                //si l'objet tableau elv contient l'attribut Especes
                // if(array_key_exists('Especes', $elv)) {
                    // error_log(sizeof($elv['Especes']));
                    // $err=[];
                    // array_push($err, count((array)$elv['Especes']));
                if(isset($elv['Especes']) == "true" && count((array)$elv['Especes'])!=0){
                    foreach ($elv['Especes'] as $espece) {
                        $esp = Espece::find($espece['id_espece']);
                        if($esp["espece"] == $request->input('espece'))
                            array_push($eleveurs, $elv);
                    }
                } 
            }
            $array_elev = array_unique($eleveurs);
        }

     
        $temp = [];
        foreach ($array_elev as $key => $value)
        {
            array_push($temp, $array_elev[$key]);
        }

        $array_elev = $temp;

        //Calculer rating  de chaque éleveur

        $cmd = Commande::all(); 
        


        foreach ($array_elev as $elev) {
            $cmd_temp = [];
            $rating = 0.0;
            $count = 0;

            foreach ($cmd as $cmd_item) {
                foreach ($cmd_item['especes'] as $espece) {
                    if ($espece['id_eleveur'] == $elev['_id'])
                        array_push($cmd_temp, $cmd_item);
                }
            }

            foreach ($cmd_temp as $cd) {
               if ($cd['rating_produit'] != null) {
                    $rating = $rating + $cd['rating_produit'];
                    $count = $count + 1;
                } else if ($cd['rating_produit'] == null) {
                    $rating = $rating;
                    $count = $count;
                }
            }

            if ($count == 0)
                $rating = 0.0;
            else
                $rating = $rating / $count;

            $elev["rating"] = $rating;
        }
        $array_elev_coop=[];
        foreach ($array_elev as $elve) {
            $cop = Cooperative::find($elve['id_cooperative']);
            $elve['cooperative']=$cop;
            
                array_push($array_elev_coop, $elve);
        }
// return  $err;
        return $array_elev_coop;
    }
}