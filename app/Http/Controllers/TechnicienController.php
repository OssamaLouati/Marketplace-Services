<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Technicien; 
use App\Animateur;
use App\Cooperative;

class TechnicienController extends Controller 
{
    /**
     * Get all Techniciens
     * @OA\Get (
     *    path="/api/techniciens",
     *    tags={"Technicien"},
     *    @OA\Response(
     *        response=200,
     *        description="success",
     *        @OA\Items(
     *            type="object",
     *            @OA\Property(
     *                property="_id",
     *                type="number",
     *                example="000000"
     *            ),
     *            @OA\Property(
     *                property="civilisation",
     *                type="string",
     *                example="example civilisation"
     *            ),
     *            @OA\Property(
     *                property="nom",
     *                type="string",
     *                example="example nom"
     *            ),
     *            @OA\Property(
     *                property="prenom",
     *                type="string",
     *                example="example prenom"
     *            ),
     *            @OA\Property(
     *                property="email",
     *                type="string",
     *                example="example email"
     *            )
     *         )
     *     )
     * )
     * 
     */
    /**
     * Get all Techniciens and number of items for each technicien
     * @OA\Get (
     *    path="/api/dashboard/techniciens",
     *    tags={"Technicien"},
     *    @OA\Response(
     *        response=200,
     *        description="success",
     *        @OA\Items(
     *            type="object",
     *            @OA\Property(
     *                property="_id",
     *                type="number",
     *                example="000000"
     *            ),
     *            @OA\Property(
     *                property="civilisation",
     *                type="string",
     *                example="example civilisation"
     *            ),
     *            @OA\Property(
     *                property="nom",
     *                type="string",
     *                example="example nom"
     *            ),
     *            @OA\Property(
     *                property="prenom",
     *                type="string",
     *                example="example prenom"
     *            ),
     *            @OA\Property(
     *                property="email",
     *                type="string",
     *                example="example email"
     *            ),
     *            @OA\Property(
     *                property="nombre d'animaux",
     *                type="number",
     *                example="example nombre"
     *            )
     *         )
     *     )
     * )
     * 
     */
    /**
     * Get all Techniciens Especes
     * @OA\Get (
     *    path="/api/technicien/{id}/especes",
     *    tags={"Technicien"},
     *    @OA\Parameter(
     *         in="path",
     *         name="id",
     *         description="Insert the Technicien Id here to get the chart", 
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *    @OA\Response(
     *        response=200,
     *        description="success",
     *        @OA\Items(
     *            type="object",
     *            @OA\Property(
     *                property="_id",
     *                type="number",
     *                example="000000"
     *            ),
     *            @OA\Property(
     *                property="civilisation",
     *                type="string",
     *                example="example civilisation"
     *            ),
     *            @OA\Property(
     *                property="nom",
     *                type="string",
     *                example="example nom"
     *            ),
     *            @OA\Property(
     *                property="prenom",
     *                type="string",
     *                example="example prenom"
     *            ),
     *            @OA\Property(
     *                property="email",
     *                type="string",
     *                example="example email"
     *            ),
     *            @OA\Property(
     *                property="nombre d'animaux",
     *                type="number",
     *                example="example nombre"
     *            )
     *         )
     *     )
     * )
     * 
     */
    /**
     * Get all Techniciens eleveurs
     * @OA\Get (
     *    path="/api/technicien/{id}/eleveurs",
     *    tags={"Technicien"},
     *    @OA\Parameter(
     *         in="path",
     *         name="id",
     *         description="Insert the Technicien Id here to get the chart", 
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *    @OA\Response(
     *        response=200,
     *        description="success",
     *        @OA\Items(
     *            type="object",
     *            @OA\Property(
     *                property="_id",
     *                type="number",
     *                example="000000"
     *            ),
     *            @OA\Property(
     *                property="civilisation",
     *                type="string",
     *                example="example civilisation"
     *            ),
     *            @OA\Property(
     *                property="nom",
     *                type="string",
     *                example="example nom"
     *            ),
     *            @OA\Property(
     *                property="prenom",
     *                type="string",
     *                example="example prenom"
     *            ),
     *            @OA\Property(
     *                property="email",
     *                type="string",
     *                example="example email"
     *            ),
     *            @OA\Property(
     *                property="nombre d'animaux",
     *                type="number",
     *                example="example nombre"
     *            )
     *         )
     *     )
     * )
     * 
     */

    /**
     * Get all Detail of an eleveur of a technicien
     * @OA\Get (
     *    path="/api/t/{id}/e/{id_eleveur}",
     *    tags={"Technicien"},
     *    @OA\Parameter(
     *         in="path",
     *         name="id",
     *         description="Insert the Technicien Id here", 
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *    @OA\Parameter(
     *         in="path",
     *         name="id_eleveur",
     *         description="Insert the eleveur Id here ", 
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *    @OA\Response(
     *        response=200,
     *        description="success",
     *        @OA\Items(
     *            type="object",
     *            @OA\Property(
     *                property="_id",
     *                type="number",
     *                example="000000"
     *            ),
     *            @OA\Property(
     *                property="civilisation",
     *                type="string",
     *                example="example civilisation"
     *            ),
     *            @OA\Property(
     *                property="nom",
     *                type="string",
     *                example="example nom"
     *            ),
     *            @OA\Property(
     *                property="prenom",
     *                type="string",
     *                example="example prenom"
     *            ),
     *            @OA\Property(
     *                property="email",
     *                type="string",
     *                example="example email"
     *            ),
     *            @OA\Property(
     *                property="nombre d'animaux",
     *                type="number",
     *                example="example nombre"
     *            )
     *         )
     *     )
     * )
     * 
     */

    
     /**
     * Get Detail Technicien
     * @OA\Get (
     *     path="/api/technicien/{id}",
     *     tags={"Technicien"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         description="Insert the technicien ID here", 
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                property="_id",
     *                type="number",
     *                example="000000"
     *            ),
     *            @OA\Property(
     *                property="civilisation",
     *                type="string",
     *                example="example civilisation"
     *            ),
     *            @OA\Property(
     *                property="nom",
     *                type="string",
     *                example="example nom"
     *            ),
     *            @OA\Property(
     *                property="prenom",
     *                type="string",
     *                example="example prenom"
     *            ),
     *            @OA\Property(
     *                property="email",
     *                type="string",
     *                example="example email"
     *            )
     *         )
     *     )
     * )
     */

    /**
     * Delete Technicien
     * @OA\Delete (
     *     path="/api/technicien/{id}",
     *     tags={"Technicien"},
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
     *             @OA\Property(property="message", type="string", example="Technicien supprimé avec success")
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
        $statut = $request->input('statut');
        $groupement = $request->input('groupement');
        $order_by= $request->input('order_by');  //specify in the request body the attribute to use for sorting
        $order_mode= $request->input('order_mode'); //specify in the request body the mode of sorting : asc or desc

       $tech = Technicien::select([
        'nom',
        'prenom',
        'email',
        'tel',
        'id_user',
        'statut',
        'groupement',
        'cooperatives'
        ])->when($nom, function ($query, $nom) {return $query->where('nom', $nom); })
         ->when($prenom, function ($query, $prenom) {return $query->where('prenom', $prenom); })
         ->when($tel, function ($query, $tel) {return $query->where('tel', $tel); })
         ->when($email, function ($query, $email) {return $query->where('email', $email); })
         ->when($statut, function ($query, $statut) {return $query->where('statut',$statut); })
         ->when($groupement, function ($query, $groupement) {return $query->where('groupement',$groupement); })
         ->orderBy($order_by, $order_mode)
         -> get();

        if ($request->has('id_animateur')) {
            
            $id_animateur = $request->input('id_animateur');
            $animateur=Animateur::where('id_user', '=',  $id_animateur)->orWhere('_id','=',  $id_animateur)->first();

            $temp = [];
            $tempT = [];
            // if(array_key_exists("cooperatives",$tech)) {
                foreach ($animateur['cooperatives'] as $coop_anim) {
                    foreach ($tech as $tech_item) {
                       $coops = (Array) $tech_item['cooperatives'];

                       $arrayCops = [];
                       foreach ($coops as $coop_tech) {
                           if ($coop_anim['id_cooperative'] == $coop_tech['id_cooperative']){

                                $cooperative=Cooperative::find($coop_tech['id_cooperative']);
                                $coop_tech['cooperative'] = $cooperative;
                                
                                array_push($arrayCops, $coop_tech);
                                $tech_item['cooperatives_data'] = $arrayCops;

                                array_push($temp, $tech_item);
                            } 
                       }
                    }
                }
            // }
    if ($request->has('nom_prenom')) {
            //$tous_les_eleveurs = Eleveur::all();
            $tech_chercher = strtolower($request->input('nom_prenom'));
            $splitName = explode(' ', $tech_chercher);

            foreach ($temp as $t) {
          
               $technicien_nom=explode(' ', strtolower($t["nom"]));
               $technicien_prenom=explode(' ', strtolower($t["prenom"]));
               $technicien=array_merge($technicien_nom,$technicien_prenom);
               $found=count(array_intersect($technicien,$splitName)) >0 && count($splitName) === count(array_intersect($technicien,$splitName)) ;
               if($found==true)
                        {  
                             array_push($tempT, $t);}

            }
            $temp = array_unique($tempT);
        }
            return $temp;
        }
    


        else return $tech;

    }
}