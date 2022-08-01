<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Validator;
use App\Espece;
use App\Eleveur;
use App\Technicien;

class EspeceController extends Controller
{

     /**
     * Get all Especes
     * @OA\Get (
     *     path="/api/Especes",
     *     tags={"Espece"},
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
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         example="example status"
     *                     ),
     *                     @OA\Property(
     *                         property="boucle",
     *                         type="string",
     *                         example="example boucle"
     *                     ),
     *                     @OA\Property(
     *                         property="prix",
     *                         type="string",
     *                         example="example prix"
     *                     ),
     *                     @OA\Property(
     *                         property="avance",
     *                         type="string",
     *                         example="example avance"
     *                     ),
     *                     @OA\Property(
     *                         property="poinds",
     *                         type="string",
     *                         example="example poids"
     *                     ),
     *                     @OA\Property(
     *                         property="age",
     *                         type="string",
     *                         example="example age"
     *                     ),
     *                     @OA\Property(
     *                         property="espece",
     *                         type="string",
     *                         example="example espece"
     *                     )
     *                     
     *                 )
     *             )
     *         )
     *     )
     * )
     */
     /**
     * Get all Especes Id where status equals to Disponible
     * @OA\Get (
     *     path="/api/test",
     *     tags={"Espece"},
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
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         example="example status"
     *                     ),
     *                     @OA\Property(
     *                         property="boucle",
     *                         type="string",
     *                         example="example boucle"
     *                     ),
     *                     @OA\Property(
     *                         property="prix",
     *                         type="string",
     *                         example="example prix"
     *                     ),
     *                     @OA\Property(
     *                         property="avance",
     *                         type="string",
     *                         example="example avance"
     *                     ),
     *                     @OA\Property(
     *                         property="poinds",
     *                         type="string",
     *                         example="example poids"
     *                     ),
     *                     @OA\Property(
     *                         property="age",
     *                         type="string",
     *                         example="example age"
     *                     ),
     *                     @OA\Property(
     *                         property="espece",
     *                         type="string",
     *                         example="example espece"
     *                     )
     *                     
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    
     /**
     * Get multi criteria search
     * @OA\Get (
     *     path="/api/Espece",
     *     tags={"Espece"},
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
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         example="example status"
     *                     ),
     *                     @OA\Property(
     *                         property="boucle",
     *                         type="string",
     *                         example="example boucle"
     *                     ),
     *                     @OA\Property(
     *                         property="prix",
     *                         type="string",
     *                         example="example prix"
     *                     ),
     *                     @OA\Property(
     *                         property="avance",
     *                         type="string",
     *                         example="example avance"
     *                     ),
     *                     @OA\Property(
     *                         property="poinds",
     *                         type="string",
     *                         example="example poids"
     *                     ),
     *                     @OA\Property(
     *                         property="age",
     *                         type="string",
     *                         example="example age"
     *                     ),
     *                     @OA\Property(
     *                         property="espece",
     *                         type="string",
     *                         example="example espece"
     *                     )
     *                     
     *                 )
     *             )
     *         )
     *     )
     * )
     */

     /**
     * Get  Min et max values
     * @OA\Get (
     *     path="/api/EspecesMinMax",
     *     tags={"Espece"},
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
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="prix min",
     *                         type="string",
     *                         example="example"
     *                     ),
     *                     @OA\Property(
     *                         property="prix max",
     *                         type="string",
     *                         example="example boucle"
     *                     ),
     *                     @OA\Property(
     *                         property="age min",
     *                         type="string",
     *                         example="example "
     *                     ),
     *                     @OA\Property(
     *                         property="age max",
     *                         type="string",
     *                         example="example avance"
     *                     ),
     *                     @OA\Property(
     *                         property="poids min",
     *                         type="string",
     *                         example="example poids"
     *                     ),
     *                     @OA\Property(
     *                         property="poinds max",
     *                         type="string",
     *                         example="example age"
     *                     )
     *                     
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    
     /**
     * Get Detail Espece avec Eleveur
     * @OA\Get (
     *     path="/api/Espece/{id}",
     *     tags={"Espece"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         description="Insert the espece ID here", 
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         example="example status"
     *                     ),
     *                     @OA\Property(
     *                         property="boucle",
     *                         type="string",
     *                         example="example boucle"
     *                     ),
     *                     @OA\Property(
     *                         property="prix",
     *                         type="string",
     *                         example="example prix"
     *                     ),
     *                     @OA\Property(
     *                         property="avance",
     *                         type="string",
     *                         example="example avance"
     *                     ),
     *                     @OA\Property(
     *                         property="poinds",
     *                         type="string",
     *                         example="example poids"
     *                     ),
     *                     @OA\Property(
     *                         property="age",
     *                         type="string",
     *                         example="example age"
     *                     ),
     *                     @OA\Property(
     *                         property="espece",
     *                         type="string",
     *                         example="example espece"
     *                     )
     *         )
     *     )
     * )
     */

    /**
     * Delete Espece
     * @OA\Delete (
     *     path="/api/Espece/{id}",
     *     tags={"Espece"},
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
     *             @OA\Property(property="message", type="string", example="item deleted successfuly")
     *         )
     *     )
     * )
     */
 

    public function minmax(Request $request)
    {
       $values = [];
       $especes = Espece::select() -> get();

       $values['all']['prix_min'] = $especes->min('prix');
       $values['all']['prix_max'] = $especes->max('prix');
       $values['all']['age_min'] = $especes->min('age');
       $values['all']['age_max'] = $especes->max('age');
       $values['all']['poids_min'] = $especes->min('poids');
       $values['all']['poids_max'] = $especes->max('poids');

       $moutons = $especes->where('espece', "mouton");

       $values['mouton']['prix_min'] = $moutons->min('prix');
       $values['mouton']['prix_max'] = $moutons->max('prix');
       $values['mouton']['age_min'] = $moutons->min('age');
       $values['mouton']['age_max'] = $moutons->max('age');
       $values['mouton']['poids_min'] = $moutons->min('poids');
       $values['mouton']['poids_max'] = $moutons->max('poids');


       $chevres = $especes->where('espece', "chevre");

       $values['chevre']['prix_min'] = $chevres->min('prix');
       $values['chevre']['prix_max'] = $chevres->max('prix');
       $values['chevre']['age_min'] = $chevres->min('age');
       $values['chevre']['age_max'] = $chevres->max('age');
       $values['chevre']['poids_min'] = $chevres->min('poids');
       $values['chevre']['poids_max'] = $chevres->max('poids');

       return $values;
    }

    public function search(Request $request)
    {
        $espece = [];
        
        $order_by= $request->input('order_by');  //specify in the request body the attribute to use for sorting
        $order_mode= $request->input('order_mode'); //specify in the request body the mode of sorting : asc or desc
        $order_mode_prix= $request->input('order_mode_prix'); //specify in the request body the mode of sorting : asc or desc
        $order_mode_age= $request->input('order_mode_age'); //specify in the request body the mode of sorting : asc or desc
        $order_mode_poids= $request->input('order_mode_poids'); //specify in the request body the mode of sorting : asc or desc
        $isParticipe=$request->input('isParticipe');
       if ($order_mode =="asc") {
           // $espece = Espece::all()->sortBy($order_by);        //==>> 18.92s - 6.69s - 16.98s
            $espece = DB::table('espece')
                     ->orderBy($order_by,'ASC')
                     ->get();                                    //==>> 10.47s - 7.62s - 10.03s

       } else if($order_mode =="desc"){
            $espece = Espece::all()->sortByDesc($order_by);
       }

       if ($order_mode_prix =="asc") {
            $espece = Espece::all()->sortBy('prix');
       } else if($order_mode_prix =="desc"){
            $espece = Espece::all()->sortByDesc('prix');
       }

       if ($order_mode_age =="asc") {
            $espece = Espece::all()->sortBy('age');
       } else if($order_mode_age =="desc"){
            $espece = Espece::all()->sortByDesc('age');
       }

       if ($order_mode_poids =="asc") {
            $espece = Espece::all()->sortBy('poids');
       } else if($order_mode_poids =="desc"){
            $espece = Espece::all()->sortByDesc('poids');
       }                           

        if ($request->has('boucle')) {
            $espece = $espece->where('boucle', $request->input('boucle'));
        }
        if ($request->has('isParticipe')) {
            $espece = $espece->where('isParticipe', $request->input('isParticipe'));
        }
        

        if ($request->has('race')) {
            $espece = $espece->where('race', $request->input('race'));
        }

        if ($request->has('statut')) {
            $espece = $espece->where('statut', $request->input('statut'));
        }

        if ($request->has('id_eleveur')) {
            $espece = $espece->where('id_eleveur', $request->input('id_eleveur'));
        }

        if ($request->has('poids_min')) {
            $espece = $espece->where('poids', '>=', $request->input('poids_min'));
        }

        if ($request->has('poids_max')) {
            $espece = $espece->where('poids', '<=', $request->input('poids_max'));
        }

        if ($request->has('localisation')) {
            $espece = $espece->where('localisation', $request->input('localisation'));
        }

        if ($request->has('espece')) {
            $espece = $espece->where('espece', $request->input('espece'));
        }

        if ($request->has('avec_avance')) {
            if($request->input('avec_avance') == "true")
                $espece = $espece->where('avance', '>', 0);
            else if ($request->input('avec_avance') == "false")
                $espece = $espece->where('avance', '=', 0);
        }

        if ($request->has('anoc')) {
            if($request->input('anoc') == "anoc")
                $espece = $espece->where('anoc', '==', "anoc");
            else 
                $espece = $espece->where('anoc', '==', "libre");
        }

        if ($request->has('reference')) {
            $espece = $espece->where('reference', '==', $request->input('reference'));
        }

        if ($request->has('prix_min')) {
            $espece = $espece->where('prix', '>=', $request->input('prix_min'));
        }

        if ($request->has('prix_max')) {
            $espece = $espece->where('prix', '<=', $request->input('prix_max'));
        }

        if ($request->has('age_min')) {
            $espece = $espece->where('age', '>=', $request->input('age_min'));
        }

        if ($request->has('age_max')) {
            $espece = $espece->where('age', '<=', $request->input('age_max'));
        }


        $array_especes = [];
        foreach ($espece as $espece_item) {
            array_push($array_especes, $espece_item);
        }

        return $array_especes;
    }

     public function getEspeceCategorie(Request $request){
        $categorie = $request->input('espece');
        $espece = Espece::select([
        'boucle',
        'id_eleveur',
        'sexe',
        'images',
        'video',
        'description',
        'statut',
        'age',
        'avance',
        'date_ajout',
        'race',
        'poids',
        'localisation',
        'prix',
        'image_face',
        'image_profile',
        'image_boucle',
        'categorie',
        'espece',
        'anoc',
        'updated_at',
        'created_at',
            ])->when($categorie, function ($query, $categorie) {return $query->where('espece', $categorie); })
            -> get();
            return $espece;

    }

    function getVideo(Request $request) {
        $boucle = $request->input('boucle');
        $filename = $request->input('filename');
        $video_path= public_path().'/uploads/Especes/'.$boucle.'/video/'.$filename;
        //$video = Storage::disk('local')->get($video_path);
        //$response = Response::make($video, 200);
        //$response->header('Content-Type', 'video/mp4');
        return response()->download($video_path);
    }

    function getImage(Request $request) {
        $boucle = $request->input('boucle');
        $filename = $request->input('filename');
        $img_path= public_path().'/uploads/Especes/'.$boucle.'/images/'.$filename;
        //$video = Storage::disk('local')->get($video_path);
        //$response = Response::make($video, 200);
        //$response->header('Content-Type', 'image/png');
        return response()->file($img_path);
    }
    public function customSearch(Request $request)
    { $tech = Technicien::where('id_user', '=',  $request->input('id_technicien'))->first();
         $especes=Espece::all();
       if ($request->has('boucle')) {
            $tab=[];

           
                foreach($especes as $e){
                    if($e->boucle==$request->input('boucle')){
                            array_push($tab, $e) ;
                    }              
                } 
                $especes =  $tab;
            }
            if ($request->has('statut')) {
                $tab=[]; 
                foreach($especes as $e){              
                    if($e->statut==$request->input('statut')){
                            array_push($tab, $e) ;
                    }              
                } 
                $especes =  $tab;
            }
            if ($request->has('race')) {
                $tab=[];
              
           
                foreach($especes as $e){              
                    if($e->race==$request->input('race')){
                            array_push($tab, $e) ;
                    }              
                } 
                $especes =  $tab;
            }

            if ($request->has('espece')) {
                $tab=[];
              
               
                foreach($especes as $e){
                    if($e->espece==$request->input('espece')){
                            array_push($tab, $e) ;
                    }              
                    
                } 
                $especes =  $tab;
            }
            if ($request->has('categorie')) {
                $tab=[];
               
               
                foreach($especes as $e){
                    if($e->categorie==$request->input('categorie')){
                            array_push($tab, $e) ;
                    }              
                    
                } 
                $especes =  $tab;
            }
            if ($request->has('region')) {
                $tab=[];
               
               
                foreach($especes as $e){
                    if($e->region==$request->input('region')){
                            array_push($tab, $e) ;
                    }              
                    
                } 
                $especes =  $tab;
            }
            if ($request->has('province')) {
                $tab=[];
              
               
                foreach($especes as $e){
                    if($e->localisation==$request->input('province')){
                            array_push($tab, $e) ;
                    }              
                    
                } 
                $especes =  $tab;
            }
            if ($request->has('cooperative')) {
                $tab=[];
             
                foreach($especes as $e){
                    if($e->id_cooperative==$request->input('cooperative')){
                            array_push($tab, $e) ;
                    }              
                    
                } 
                $especes =  $tab;
            }
            if ($request->has('eleveur_nom_prenom')) {
                //$tous_les_eleveurs = Eleveur::all();
                $tech = Technicien::where('id_user', '=',  $request->input('tech'))->orWhere('_id', '=',  $request->input('tech'))->first();
    $tech_eleveurs = [];
    $table=[];
    foreach ($tech->eleveurs as $eleveur) {
        $elev = Eleveur::find($eleveur["id_eleveur"]);
        $eleveur["eleveur"] = $elev;
        array_push($tech_eleveurs, $eleveur);} 
                $eleveur_chercher = strtolower($request->input('eleveur_nom_prenom'));
                $splitName = explode(' ', $eleveur_chercher);
                $tab=[];
                foreach ($tech_eleveurs as $elv) {
                    $eleveur_nom=explode(' ', strtolower($elv["eleveur"]["nom"]));
                   $eleveur_prenom=explode(' ', strtolower($elv["eleveur"]["prenom"]));
                   $eleveur=array_merge($eleveur_nom,$eleveur_prenom);
                   $found=count(array_intersect($eleveur,$splitName)) >0 && count($splitName) === count(array_intersect($eleveur,$splitName)) ;

                   if($found==true)
                            {   array_push($tab, $elv["id_eleveur"]);}

    
                }
                foreach($especes as $e){if(in_array($e->id_eleveur, $tab)){array_push($table, $e);}}
                $especes = array_unique($table);
        
             }
            if ($request->has('eleveur')) {
                $tabElv=[];
                $tabEsp=[];
                $i=0;
            
                foreach ($tech["eleveurs"] as $eleveur) {
                    $ev=Eleveur::find($eleveur["id_eleveur"]);
                     if($eleveur["id_eleveur"]==$request->input('eleveur')){
                        array_push($tabElv, $eleveur["id_eleveur"]) ;
                    }
                }
                foreach($especes as $e){
                    if(in_array($e->id_eleveur, $tabElv)){
                        array_push($tabEsp, $e) ;
                    }              
                }

                $especes =  $tabEsp;

            }
            
          /*  $arr=[];
            $i=0;
            foreach ($tech["eleveurs"] as $eleveur) {
                $arr[$i]=[];
                foreach($especes as $esp){
                    if($esp->id_eleveur===$eleveur["id_eleveur"]){
                        array_push($arr[$i], $esp);
                    }
                }
                $i++;
            }
            $especes =  $arr;*/
                      return response()->json($especes); 

        }
 
        




    /*public function createEspece(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'boucle' => 'required|unique:App\Espece,boucle',
            'images' => 'required',
            'images.*' => 'image|mimes:jpg,png,jpeg,gif',
            'video' => 'mimetypes:video/avi,video/mpeg,video/mp4|max:100040'
        ]);

        $input = $request->all();
        if($request->hasfile('images'))
        {
                foreach ($request->file('images') as $image) {
                    //$name = time().'.'.$file->extension();
                    $boucle=$input['boucle'];
                    $name = $image->getClientOriginalName();
                    error_log($name);
                    $path = public_path().'/uploads/Especes/'.$boucle.'/images/';
                    error_log($path);
                    $image->move($path,$name);
                    //move_uploaded_file ($image , $path);
                    //$path_complet=strval($path)+"/"+strval($name);
                    array_push($input['images'],'/uploads/Especes/'.$boucle.'/images/'.$name);
                }
        }

        //to avoid this error : 413Payload Too Large , while sending a video in a request
        //you should modify post_max_size and upload_max_filesize in php.ini
        if($request->hasfile('video'))
        {
            $boucle=$input['boucle'];
            $video=$input['video'];
            $name = $video->getClientOriginalName();
            error_log($name);
            $path = public_path().'/uploads/Especes/'.$boucle.'/video/';
            error_log($path);
            $video->move($path,$name);
            //move_uploaded_file ($image , $path);
            //$path_complet=strval($path)+"/"+strval($name);
            $input['video']='/uploads/Especes/'.$boucle.'/video/'.$name;
        }

        $shp=Espece::create($input);
        return response()->json(["objet" => $shp,"msg" =>"Ajouté avec succès"]);
    }*/


}
