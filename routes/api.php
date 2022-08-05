<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use illuminate\contract\Mailer;


use App\Http\Controllers;
use App\Consommateur;
use App\Espece;
use App\Eleveur;
use App\Technicien;
use App\Commande;
use App\User;
use App\Centre;
use App\FCMPushNotification;
use App\Cooperative;
use App\Livraison;
use App\Animateur;
use App\Client;
use Exception;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Echo_;

/*______________Client Route Functions____________________*/


Route::get('clients', 'ClientController@index' );
Route::get('/client/{id}','ClientController@show');
Route::delete('client/{id}', 'ClientController@delete' );
Route::post('clients', 'ClientController@insert' );
Route::put('client/{id}', 'ClientController@update' );



Route::group(['middleware' => 'auth:api'], function () {
    Route::post('details', 'UserController@details');
});

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::get('details', 'UserController@details');


//Route Reset password


Route::post('/password/email', 'pass\ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset', 'pass\ResetPasswordController@reset');

//-------------------

Route::apiResource('tasks','Api\TasksController')->middleware('auth:api');


// Route::group([
//     'prefix' => 'auth'
// ], function () {
//     Route::post('login', 'UserController@login');
//     Route::post('register', 'UserController@register');

//     Route::group([
//       'middleware' => 'auth:api'
//     ], function() {
//         Route::get('logout', 'UserController@logout'); //KO
//         Route::get('user', 'UserController@user');  //KO
//     });
// });

//Routes User
// Route::get('users', function () {
//     return User::all();
    
// })->middleware('auth:api');

Route::get('users', function () {
    return User::all();
    
});

Route::get('dashboard/secteur', function () {
    $output_temp = [];
    $cmds = [];
    $output = [];

    $anim = Animateur::all();
    $cmd = Commande::where('statut' ,'<>' ,"commande annulée (deadline dépassé)")->where('statut' ,'<>' ,"annulée manuellement" )->get();

    $output["Total des commandes"] = sizeof($cmd);

    foreach ($anim as $anim_item) {
        $totalespece=0;
        $cmd_anim = Commande::where('id_animateur', '=',  $anim_item["id_user"])->where('statut' ,'<>' ,"commande annulée (deadline dépassé)")->where('statut' ,'<>' ,"annulée manuellement" )->get();
        
        foreach ($cmd_anim  as $item) {
            $totalespece += sizeof($item["especes"]);
        }
        $output_temp["Secteur"] = $anim_item["parametrage_global"]["secteur"]["nomRegion"];
        $output_temp["Nombre de commandes"] = sizeof($cmd_anim);
        $output_temp["Nombre de tête"]= $totalespece;
        array_push($cmds, $output_temp);
    }

    $output["Commandes par secteur"] = $cmds;

    return $output;
});


Route::get('user/{id}', function ($id) {
    return User::find($id);
})->middleware('auth:api');


Route::delete('user/{id}', function ($id) {
    $usr = User::find($id);
    $usr->delete();
    return response()->json("Supprimé avec succès");
})->middleware('auth:api');

Route::put('user/{id}', function (Request $request, $id) {
    $usr = User::find($id);
    if ($request->has('email')) {
        $validator = Validator::make($request->all(), [ 
            'email' => 'string|email|unique:users' ]);
         if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
            }
        else $usr->email = $request->input('email');
    }
    if ($request->has('telephone')) {
        $validator = Validator::make($request->all(), [ 
            'telephone' => 'unique:users' ]);
         if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);   
         }         
        else $usr->telephone = $request->input('telephone');
    }
    if ($request->has('password')) {
        $usr->password = bcrypt($request->input('password'));
    }
    $usr->save();
    return response()->json("Modifié avec succès");
})->middleware('auth:api');

//Routes Consommateur
// Route::get('consommateurs', function () {
//     return Consommateur::all();
// })->middleware('auth:api');
Route::get('consommateurs', function () {
    return Consommateur::all();
});

Route::post('consommateur', function (Request $request) {
    $ach = Consommateur::create($request->all());
    return response()->json(["objet" => $ach, "msg" => "Ajouté avec succès"]);
});

Route::get('consommateur/{id}', function ($id) {
    return Consommateur::where('id_user', '=',  $id)->orWhere('_id','=',  $id)->first();
});


Route::get('dashboard', function () {

    $output_tempS = [];
    $cmds = [];
    $outputS = [];

    $anim = Animateur::all();
    $cmd = Commande::where('statut' ,'<>' ,"commande annulée (deadline dépassé)")->where('statut' ,'<>' ,"annulée manuellement" )->get();

    $outputS["Total des commandes"] = sizeof($cmd);

    foreach ($anim as $anim_item) {
        $totalespece=0;
        $cmd_anim = Commande::where('id_animateur', '=',  $anim_item["id_user"])->where('statut' ,'<>' ,"commande annulée (deadline dépassé)")->where('statut' ,'<>' ,"annulée manuellement" )->get();
        
        foreach ($cmd_anim  as $item) {
            $totalespece += sizeof($item["especes"]);
        }
        $output_tempS["Secteur"] = $anim_item["parametrage_global"]["secteur"]["nomRegion"];
        $output_tempS["Nombre de commandes"] = sizeof($cmd_anim);
        $output_tempS["Nombre de tête"]= $totalespece;
        array_push($cmds, $output_tempS);
    }

    $outputS["Commandes par secteur"] = $cmds;

    
    
    $output_temp = [];
    $techs = [];
    $output = [];

    $tech = Technicien::all();
    
    foreach ($tech as $tech_item) {
        $count = 0;

        $output_temp["Nom"] = $tech_item['nom'];
        $output_temp["Prenom"] = $tech_item['prenom'];
        $output_temp["Tel"] = $tech_item['tel'];

        foreach ($tech_item["eleveurs"] as $eleveur_item) {
            $especes = Espece::where('id_eleveur', '=', $eleveur_item["id_eleveur"])->get();
            $count = sizeof($especes) + $count;
           
            // $elev = Eleveur::find($eleveur_item["id_eleveur"]);
            // $anim = Animateur::where('id_eleveur', '=', $elev["id_animateur"])->first();
            // $output_temp["Secteur"] = $anim["parametrage_global"]["secteur"]["nomRegion"];

        }
        $output_temp["Nombre animaux"] = $count;
        array_push($techs, $output_temp);
    }

    $output["Nombre techniciens"] = sizeof($techs);
    $output["techniciens"] = $techs;
    $outputS["Nombre techniciens"]=$output["Nombre techniciens"];
    $outputS["techniciens"]=$output["techniciens"];
    
    return $outputS;
    // return $output;
});

Route::get('dashboard/techniciens', function () {
    
    $output_temp = [];
    $techs = [];
    $output = [];

    $tech = Technicien::all();
    
    foreach ($tech as $tech_item) {
        $count = 0;

        $output_temp["Nom"] = $tech_item['nom'];
        $output_temp["Prenom"] = $tech_item['prenom'];
        $output_temp["Tel"] = $tech_item['tel'];

        foreach ($tech_item["eleveurs"] as $eleveur_item) {
            $especes = Espece::where('id_eleveur', '=', $eleveur_item["id_eleveur"])->get();
            $count = sizeof($especes) + $count;
           
            // $elev = Eleveur::find($eleveur_item["id_eleveur"]);
            // $anim = Animateur::where('id_eleveur', '=', $elev["id_animateur"])->first();
            // $output_temp["Secteur"] = $anim["parametrage_global"]["secteur"]["nomRegion"];

        }
        $output_temp["Nombre animaux"] = $count;
        array_push($techs, $output_temp);
    }

    $output["Nombre techniciens"] = sizeof($techs);
    $output["techniciens"] = $techs;

    return $output;
});
// ->middleware('auth:api');

Route::delete('consommateur/{id}', function ($id) {
    $ach = Consommateur::find($id);
    $ach->delete();
    return response()->json("Supprimé avec succès");
})->middleware('auth:api');

Route::put('consommateur/{id}', function (Request $request, $id) {
    $ach = Consommateur::where('id_user', '=',  $id)->first();
    if ($request->has('nom')) {
        $ach->nom = $request->input('nom');
    }
    if ($request->has('prenom')) {
        $ach->prenom = $request->input('prenom');
    }
    if ($request->has('tel')) {
        $ach->tel = $request->input('tel');
    }
    if ($request->has('email')) {
        $ach->email = $request->input('email');
    }
    if ($request->has('adresse')) {
        $ach->adresse = $request->input('adresse');
    }
    if ($request->has('favoris')) {
        $ach->favoris = $request->input('favoris');
    }
    if ($request->has('panier')) {
        $ach->panier = $request->input('panier');
    }
    if ($request->has('tokenDevice')) {
        $ach->tokenDevice = $request->input('tokenDevice');
    }
    $ach->save();
    return response()->json("Modifié avec succès");
});
// ->middleware('auth:api');

//Ajouter un Espece dans le panier du consommateur
Route::put('consommateur/{id}/panier', function (Request $request, $id) {
    Consommateur::where('id_user', '=',  $id)
        ->push('panier', array('id_espece' => $request->input('id_espece')));
    $cons = Consommateur::find($id);
    return response()->json(["objet" => $cons, "msg" => "Espece ajouté au tableau panier du consommateur"]);
})->middleware('auth:api');

//Récupérer la liste des Especes dans le panier du consommateur
Route::get('consommateur/{id}/panier', function ($id) {
    try{
    $cons = Consommateur::where('id_user', '=',  $id)->first();
    $panier = [];
    $panier_Especes = $cons->panier;
    foreach ($panier_Especes as $panier_Especes_item) {
        $espece = Espece::find($panier_Especes_item["id_espece"]);
        array_push($panier, $espece);
    }
    // $panier=$panier->groupBy('id_cooperative');

// $panierGroupedByIdCopperative = array();
// foreach ($panier as $item) {

//     $panierGroupedByIdCopperative[$item['id_cooperative']][] = $item;

// }
    return response()->json($panier);
    }catch(Exception $e){
        return response()->json(["message"=>"ce id du consommateur n'éxiste pas"],404);
    }
})->middleware('gzip');
// ->middleware('auth:api')

//Récupérer la liste des Especes dans le panier du consommateur groupé par coopérative 
Route::get('consommateur/{id}/panierGroupes', function ($id) {
    $cons = Consommateur::where('id_user', '=',  $id)->first();
    $panier = [];
    $panier_Especes = $cons->panier;
    foreach ($panier_Especes as $panier_Especes_item) {
        $espece = Espece::find($panier_Especes_item["id_espece"]);
        if($espece['statut'] == "disponible")
        array_push($panier, $espece);
    }

    //Group By elements dans panier par id_cooperative
    $result = array();
    foreach ($panier as $element) {
        $result[$element['id_cooperative']]["especes"][] = $element;
    }

    $keys = array_keys($result);

    foreach ($keys as $key_id) {
        $total = 0;

        foreach ($result[$key_id]["especes"] as $item) {
            $total = $item["prix"] + $total;
        }
        
        $result[$key_id]["total"] = $total;

        //Remplacer les key du tableau (id_cooperative) par les noms des coopératives
        $coop = Cooperative::where('_id', '=',  $key_id)->first();
        $result[$coop["nom"]] = $result[$key_id];
        unset($result[$key_id]);
    }

    return response()->json($result);
})->middleware('gzip');
// ->middleware('auth:api')

//Supprimer un Espece depuis le panier du consommateur
Route::put('consommateur/{id}/panier/{id_espece}', function ($id, $id_espece) {
    Consommateur::where('id_user', '=',  $id)
        ->where('panier.id_espece', '=',  $id_espece)
        ->pull('panier', array('id_espece' => $id_espece));
    $cons = Consommateur::find($id);
    return response()->json(["objet" => $cons, "msg" => "Espece supprimé du tableau panier du consommateur"]);
})->middleware('auth:api');

//Ajouter une Espece dans les favoris du consommateur
Route::put('consommateur/{id}/favoris', function (Request $request, $id) {
    Consommateur::where('id_user', '=',  $id)
        ->push('favoris', array('id_espece' => $request->input('id_espece')));
    $cons = Consommateur::find($id);
    return response()->json(["objet" => $cons, "msg" => "Espece ajouté au tableau favoris du consommateur"]);
})->middleware('auth:api');

//Récupérer la liste des Especes depuis les favoris du consommateur
Route::get('consommateur/{id}/favoris', function (Request $request,$id) {
    $cons = Consommateur::where('id_user', '=',  $id)->first();
    $favoris = [];
    $favoris_Especes = $cons->favoris;
    foreach ($favoris_Especes as $favoris_Especes_item) {
        $espece = Espece::find($favoris_Especes_item["id_espece"]);
        array_push($favoris, $espece);
    }

    $favorisFiltrees = [];
    if ($request->has('espece')) {
        foreach ($favoris_Especes as $favoris_Especes_item) {
            $espece = Espece::find($favoris_Especes_item["id_espece"]);
            if ($espece['espece'] ==  $request->input('espece')){
                array_push($favorisFiltrees, $espece);
            }
        }

        return response()->json($favorisFiltrees);
    }

    if ($request->has('race')) {
        foreach ($favoris_Especes as $favoris_Especes_item) {
            $espece = Espece::find($favoris_Especes_item["id_espece"]);
            if ($espece['race'] ==  $request->input('race')){
                array_push($favorisFiltrees, $espece);
            }
        }

        return response()->json($favorisFiltrees);
    }

    return response()->json($favoris);
})->middleware('auth:api');

//Supprimer une Espece depuis les favoris du consommateur
Route::put('consommateur/{id}/favoris/{id_espece}', function ($id, $id_espece) {
    Consommateur::where('id_user', '=',  $id)
        ->where('favoris.id_espece', '=',  $id_espece)
        ->pull('favoris', array('id_espece' => $id_espece));
    $cons = Consommateur::find($id);
    return response()->json(["objet" => $cons, "msg" => "Espece supprimé du tableau favoris du consommateur"]);
})->middleware('auth:api');

//Routes Espece
Route::get('Especes', function () {
    return Espece::all();
});


// getAllEspece selon la catégorie
Route::get('Especes', 'EspeceController@getEspeceCategorie');

//create Espece with images and video as base 64 Strings
// Route::post('Espece', function (Request $request) {
//     $last_document = Espece::latest()->first();
//      $espece = Espece::create($request->all());// error_log($last_document);
//     if($last_document==null){
//     $espece->reference = 1;
//    }else if($last_document!=null){
//     if ($last_document->reference != null){
//         // error_log("first IF");
//         $espece->reference = $last_document->reference + 1;
//     }
//     else if ($last_document->reference == null ){
//         // error_log("second IF");
//         $espece->reference = 1;
//     }}
//     $espece->save();
//     return response()->json(["objet" => $espece, "msg" => "Ajouté avec succès"]);
// })->middleware('auth:api');

// Fromat reference => Région.RéférenceChiffre.M/V/C.jour.mois.année Exple: CS.0001.M.10.10.20
Route::post('Espece', function (Request $request) {
    $categorie_espece = "";
    $region_indicatif = "";

    $espece = Espece::create($request->all());
    $id_eleveur=$espece->id_eleveur;
    $eleveur = Eleveur::find($id_eleveur);
    if($eleveur->isParticipe=="true") $espece->isParticipe=true;

    //Récupérer la dernière espèce de la même catégorie
    $last_document_ByEspece = Espece::select(['_id', 'espece', 'region', 'reference', 'created_at'])-> get();
    $last_document_ByEspece = $last_document_ByEspece->where('_id', '<>', $espece->_id);
    $last_document_ByEspece = $last_document_ByEspece->where('espece', $espece->espece);
    $last_document_ByEspece = $last_document_ByEspece->where('region',  $espece->region);
    $last_document_ByEspece = $last_document_ByEspece->sortByDesc('created_at')->first();

    error_log($last_document_ByEspece);

    //Date création espèce
    $date = date_create($espece->created_at);

    //Symboles categories espece
    if($espece->espece == "mouton")
        $categorie_espece = "M";
    if($espece->espece == "vache")
        $categorie_espece = "V";
    if($espece->espece == "chevre")
        $categorie_espece = "C";

    //Indicatifs région espèce
    if($espece->region == "Tanger-Tétouan-Al Hoceïma")
        $region_indicatif = "TTAH";
    if($espece->region == "L'Oriental")
        $region_indicatif = "O";
    if($espece->region == "Fès-Meknès")
        $region_indicatif = "FM";
    if($espece->region == "Rabat-Salé-Kénitra")
        $region_indicatif = "RSK";
    if($espece->region == "Béni Mellal-Khénifra")
        $region_indicatif = "BMK";
    if($espece->region == "Casablanca-Settat")
        $region_indicatif = "CS";
    if($espece->region == "Marrakech-Safi")
        $region_indicatif = "MS";
    if($espece->region == "Drâa-Tafilalet")
        $region_indicatif = "DT";
    if($espece->region == "Souss-Massa")
        $region_indicatif = "SM";
    if($espece->region == "Guelmim-Oued Noun")
        $region_indicatif = "GON";
    if($espece->region == "Laâyoune-Sakia El Hamra")
        $region_indicatif = "LSEH";
    if($espece->region == "Dakhla-Oued Ed-Dahab")
        $region_indicatif = "DOED";

    //if collection Espece empty
    if($last_document_ByEspece == null){
        error_log("First IF");
        $espece->reference = $region_indicatif . ".1." . $categorie_espece . "." . date_format($date, 'd.m.y');
    }
    //if collection Espece not empty
    else if($last_document_ByEspece != null){
        error_log("Second IF");

        if ($last_document_ByEspece ->reference != null){
            error_log("Third IF");
            $lastRef = explode(".", $last_document_ByEspece->reference);
            error_log($lastRef[1]);
            $espece->reference =  $region_indicatif . "." . ($lastRef[1] + 1) . "." . $categorie_espece . "." . date_format($date, 'd.m.y');
        }
        else if ($last_document_ByEspece->reference == null ){
            error_log("Fourth IF");
            error_log($region_indicatif);
            error_log($categorie_espece);
            error_log(date_format($date, 'd.m.y'));
            $espece->reference = $region_indicatif . ".1." . $categorie_espece . "." . date_format($date, 'd.m.y') ;
        }
    }
    $espece->save();
    return response()->json(["objet" => $espece, "msg" => "Ajouté avec succès"]);
})->middleware('auth:api');

//to avoid this error : 413Payload Too Large , while sending a video in a request
//you should modify post_max_size and upload_max_filesize in php.ini
//Route::post('Espece', 'EspeceController@createEspece'); //create Espece with images and video included

//Récupération infos Espece avec son eleveur
Route::get('Espece/{id}', function ($id) {
    $espece = Espece::find($id);
    $elev = Eleveur::select(['civilisation', 'nom', 'prenom', 'tel', 'email', 'adresse', 'region', 'rib', 'boucle'])
        ->where('Especes.id_espece', '=',  $id)
        ->get();
    return response()->json(["objet" => $espece, "Eleveur" => $elev]);
});

Route::get('Espece', 'EspeceController@search')->middleware('gzip'); //multi criteria search
Route::get('EspecesMinMax', 'EspeceController@minmax'); // get min & max values

//Route::get('Espece/getVideo', 'EspeceController@getVideo'); //get video of Espece
//Route::get('Espece/getImage', 'EspeceController@getImages'); //get images of Espece

Route::delete('Espece/{id}', function ($id) {
    $espece = Espece::find($id);
    $espece->delete();
    return response()->json("Supprimé avec succès");
});
//->middleware('auth:api')
Route::put('Espece/{id}', function (Request $request, $id) {
    $espece = Espece::find($id);
    if ($request->has('region')) {
        $espece->region = $request->input('region');
    }
    if ($request->has('boucle')) {
        $espece->boucle = $request->input('boucle');
    }
    if ($request->has('boucle_de_naissance')) {
        $espece->boucle_de_naissance = $request->input('boucle_de_naissance');
    }
    if ($request->has('race')) {
        $espece->race = $request->input('race');
    }
    if ($request->has('race_ar')) {
        $espece->race_ar = $request->input('race_ar');
    }
    if ($request->has('statut')) {
        $espece->statut = $request->input('statut');
    }
    if ($request->has('age')) {
        $espece->age = $request->input('age');
    }
    if ($request->has('sexe')) {
        $espece->sexe = $request->input('sexe');
    }
    if ($request->has('sexe_ar')) {
        $espece->sexe_ar = $request->input('sexe_ar');
    }
    if ($request->has('poids')) {
        $espece->poids = $request->input('poids');
    }
    if ($request->has('localisation')) {
        $espece->localisation = $request->input('localisation');
    }
    if ($request->has('prix')) {
        $espece->prix = $request->input('prix');
    }
    if ($request->has('categorie')) {
        $espece->categorie = $request->input('categorie');
    }
    if ($request->has('espece')) {
        $espece->espece = $request->input('espece');
    }
    if ($request->has('image_face')) {
        $espece->image_face = $request->input('image_face');
    }
    if ($request->has('image_profile')) {
        $espece->image_profile = $request->input('image_profile');
    }
    if ($request->has('image_boucle')) {
        $espece->image_boucle = $request->input('image_boucle');
    }
    if ($request->has('video')) {
        $espece->video = $request->input('video');
    }
    if ($request->has('description')) {
        $espece->description = $request->input('description');
    }
    if ($request->has('date_ajout')) {
        $espece->date_ajout = $request->input('date_ajout');
    }
    if ($request->has('avance')) {
        $espece->avance = $request->input('avance');
    }
    if ($request->has('anoc')) {
        $espece->anoc = $request->input('anoc');
    }
    if ($request->has('type')) {
        $espece->type = $request->input('type');
    }
    if ($request->has('age_min')) {
        $espece->age_min = $request->input('age_min');
    }
    if ($request->has('age_max')) {
        $espece->age_max = $request->input('age_max');
    } 
    if ($request->has('poids_min')) {
        $espece->poids_min = $request->input('poids_min');
    }
    if ($request->has('poids_max')) {
        $espece->poids_max = $request->input('poids_max');
    }
    if ($request->has('boucles')) {
        $espece->boucles = $request->input('boucles');
    }
    $espece->save();
    return response()->json("Modifié avec succès");
});
// ->middleware('auth:api')

//Ajouter une photo pour un Espece
Route::post('Espece/{id}/image', function (Request $request, $id) {
    $espece = Espece::find($id);
    $image = $request->input('nom_image');
    $value_image = $request->input('value');

    /*$validator = Validator::make($request->all(), [
                                                                    'image' => 'required|image|mimes:jpg,png,jpeg,gif'
                                                                ]);

                                                                if($request->hasFile('image'))
                                                                {
                                                                    $image =$request->file('image');
                                                                    //error_log($image);
                                                                    $name = $image->getClientOriginalName();
                                                                    //error_log($name);
                                                                    $path = public_path().'/uploads/Especes/'.$boucle.'/images/';
                                                                    //error_log($path);
                                                                    $image->move($path,$name);
                                                                   $request['image']='/uploads/Especes/'.$boucle.'/images/'.$name;
                                                                   Espece::where('_id', '=',  $id )
                                                                   ->push('images', array($request->input('image')));
                                                                }*/
    if ($image == "image_face") {
        $espece->image_face = $value_image;
    } else if ($image == "image_profile") {
        $espece->image_profile = $value_image;
    } else if ($image == "image_boucle") {
        $espece->image_boucle = $value_image;
    }

    $espece->save();
    $espece_maj = Espece::find($id);
    return response()->json(["objet" => $espece_maj, "msg" => "Image " . $image . " du Espece ajouté"]);
})->middleware('auth:api');

//Récupérer les photos d'un Espece
/*Route::get('Espece/{id}/images', function($id){
                                                  $espece = Espece::find($id);
                                                  $espece_images=$espece->images;
                                                  return response()->json($espece_images);
                                                 });*/
//Supprimer une photo d'un Espece
Route::put('Espece/{id}/image', function (Request $request, $id) {
    $espece = Espece::find($id);
    $image = $request->input('nom_image');
    //$boucle=$espece->boucle;
    //unlink(public_path().'/uploads/Especes/'.$boucle.'/images/'.$image_name);
    //Espece::where('_id', '=',  $id )
    //->where('images', '=',  '/uploads/Especes/'.$boucle.'/images/'.$image_name )
    //->pull('images', '/uploads/Especes/'.$boucle.'/images/'.$image_name);

    if ($image == "image_face") {
        $espece->image_face = "";
    } else if ($image == "image_profile") {
        $espece->image_profile = "";
    } else if ($image == "image_boucle") {
        $espece->image_boucle = "";
    }

    $espece->save();
    $espece_maj = Espece::find($id);
    return response()->json(["objet" => $espece_maj, "msg" => $image . " du Espece supprimé "]);
})->middleware('auth:api');

//Routes Eleveur
Route::get('eleveurs', function () {
    return Eleveur::all();
});

//create eleveur with profile picture
Route::post('eleveur', function (Request $request) {
    /*$validator = Validator::make($request->all(), [
                                                        'photo_profil' => 'image|mimes:jpg,png,jpeg,gif'
                                                    ]);

                                                    $input = $request->all();
                                                    if($request->hasfile('photo_profil'))
                                                    {
                                                        $image=$request->file('photo_profil');
                                                        $name = $image->getClientOriginalName();
                                                        error_log($name);
                                                        $path = public_path().'/uploads/photos_profil/';
                                                        error_log($path);
                                                        $image->move($path,$name);
                                                        $input['photo_profil']='/uploads/photos_profil/'.$name;
                                                        error_log($input['photo_profil']);
                                                    }*/

    $elev = Eleveur::create($request->all());
    return response()->json(["objet" => $elev, "msg" => "Ajouté avec succès"]);
}); //->middleware('auth:api');

Route::put('import/eleveurs', function (Request $request) {
    $listTmp=[];

    foreach($request->input("liste") as $tmp){
        $elev = Eleveur::create($tmp);
        Technicien::where('id_user', '=',  $elev["id_technicien"])->orWhere('_id', '=',  $elev["id_technicien"])
        ->push('eleveurs', array('id_eleveur' => $elev["_id"]));
        array_push($listTmp,$elev);
    }
    
    return response()->json(["objet" => $listTmp, "msg" => "Ajouté avec succès"]);
}); //->middleware('auth:api');


Route::get('eleveur/{id}', function ($id) {
    // return Eleveur::find($id);
    $elev = Eleveur::find($id);
    // $tech=Eleveur::all();
        
    // foreach ($tech as $espece) {
    //     $esp = Espece::find($espece["id_espece"]);
    //     $espece["espece"] = $esp;
    //     array_push($elev_Especes, $espece);
    // }
    $tech = Technicien::select('civilisation', 'nom', 'prenom', 'cin','id_cooperative')
    ->where('eleveurs.id_eleveur', '=',  $id)
   ->get();

   
     $elev->nomtech =$tech[0]->nom;
     $elev->civilisationtech =$tech[0]->civilisation;
     $elev->prenomtech =$tech[0]->prenom;
     $elev->cintech =$tech[0]->cin;
    //  $cop=Cooperative::select('rib')->where('_id', '=',  $elev->id_cooperative)
    //  ->get();
     $cop=Cooperative::find($elev->id_cooperative);
    $elev->ribcoop=$cop->rib;
   
    return  $elev;

});

Route::get('eleveur', 'EleveurController@search')->middleware('gzip');
// ->middleware('gzip') //multi criteria search

Route::delete('eleveur/{id}', function ($id) {
    $elev = Eleveur::find($id);
    $tech=Technicien::where('id_user', '=',$elev->id_technicien)->orWhere('_id', '=',$elev->id_technicien)
    ->pull('eleveurs',array('id_eleveur'=>$id));
    $elev->delete();
    return response()->json("Supprimé avec succès");
});

/*Route::put('taille', function (Request $request) {
$cop = Cooperative::all();
foreach($cop as $i){
}
});*/

//mettre un eleveur non participe
Route::put('isParticipe/{id}', function ( $id) {
    $eleveur=Eleveur::find($id);
    if($eleveur->isParticipe == true){
        $eleveur->isParticipe = false;
       /* foreach($eleveur->Especes as $esp){
            $espece = Espece::find($esp["id_espece"])
            $espece->statut = 'non disponible';
            $espece->save();
        }*/
    }else if( $eleveur->isParticipe == false ){
    
        $eleveur->isParticipe = true;
       /* foreach($eleveur->Especes as $esp){
            $espece = Espece::find($esp["id_espece"])
            $espece->statut = 'disponible';
            $espece->save();
        }*/
    }
    
    $eleveur->save();
    $eleveur=Eleveur::find($id);
    return $eleveur;
    
});

//Modifier un eleveur -> avec possibilité de modifier sa photo de profil également
Route::put('eleveur/{id}', function (Request $request, $id) {
    $elev = Eleveur::find($id);
    if ($request->has('nom')) {
        $elev->nom = $request->input('nom');
    }
    if ($request->has('nom_ar')) {
        $elev->nom_ar = $request->input('nom_ar');
    }
    if ($request->has('prenom_ar')) {
        $elev->prenom_ar = $request->input('prenom_ar');
    }
    if ($request->has('prenom')) {
        $elev->prenom = $request->input('prenom');
    }
    if ($request->has('tel')) {
        $elev->tel = $request->input('tel');
    }
    if ($request->has('cin')) {
        $elev->cin = $request->input('cin');
    }
    if ($request->has('email')) {
        $elev->email = $request->input('email');
    }
    if ($request->has('adresse')) {
        $elev->adresse = $request->input('adresse');
    }
    if ($request->has('adresse_ar')) {
        $elev->adresse_ar = $request->input('adresse_ar');
    }
    if ($request->has('rib')) {
        $elev->rib = $request->input('rib');
    }
    if ($request->has('Especes')) {
        $elev->Especes = $request->input('Especes');
    }
    if ($request->has('photo_profil')) {
        $elev->photo_profil = $request->input('photo_profil');
    }
    if ($request->has('anoc')) {
        $elev->anoc = $request->input('anoc');
    }
    if ($request->has('code_eleveur')) {
        $elev->code_eleveur = $request->input('code_eleveur');
    }
    
    if ($request->has('isParticipe')) {
        $elev->isParticipe = $request->input('isParticipe');
         if($request->input('isParticipe') == "true"){
        if($elev->Especes!=null)
       foreach($elev->Especes as $esp){
            $espece = Espece::find($esp["id_espece"]);
            if($espece!=null){
            $espece->isParticipe = true;
            $espece->save();}
        }
   }else if( $request->input('isParticipe')=="false" ){
    if($elev->Especes!=null)
       foreach($elev->Especes as $esp){
            $espece = Espece::find($esp["id_espece"]);
            if($espece!=null){
            $espece->isParticipe = false;
            $espece->save();}
        
    }
    }}
    if ($request->has('toDelete')) {
        $elev->toDelete = $request->input('toDelete');
    }
    /* if($request->hasfile('photo_profil'))
                                                                {
                                                                    $old_pic=$elev->photo_profil;
                                                                    unlink(public_path().$old_pic);
                                                                    $image=$request->file('photo_profil');
                                                                    $name = $image->getClientOriginalName();
                                                                    error_log($name);
                                                                    $path = public_path().'/uploads/photos_profil/';
                                                                    error_log($path);
                                                                    $image->move($path,$name);
                                                                    $elev->photo_profil='/uploads/photos_profil/'.$name;
                                                                }*/

    $elev->save();
    return response()->json(["objet" => $elev, "msg" => "Modifié avec succès"]);
});

// ->middleware('auth:api');

//Ajouter un Espece dans le tableau Especes de l'éleveur
Route::put('eleveur/{id}/Espece', function (Request $request, $id) {
    Eleveur::where('_id', '=',  $id)
        ->push('Especes', array('id_espece' => $request->input('id_espece')));
    $elev = Eleveur::find($id);
    return response()->json(["objet" => $elev, "msg" => "Espece ajouté au tableau Especes de l'éleveur"]);
})->middleware('auth:api');

//Récupérer la liste des Especes d'un éleveur
Route::get('eleveur/{id}/Especes', function (Request $request, $id) {
    $elev = Eleveur::find($id);
    // $elev_Especes = $elev->Especes;
    // return response()->json($elev_Especes);
    $elev_Especes = [];
    foreach ($elev["Especes"] as $espece) {
        $esp = Espece::find($espece["id_espece"]);
        $espece["espece"] = $esp;
        array_push($elev_Especes, $espece);
    }

    $especesFiltrees = [];
    if ($request->has('espece')) {
        foreach ($elev["Especes"] as $espece) {
            $esp = Espece::find($espece["id_espece"]);
            if ($esp['espece'] ==  $request->input('espece')){
                $espece["espece"] = $esp;
                array_push($especesFiltrees, $espece);
            }
        }

        return response()->json($especesFiltrees);
    }

    if ($request->has('race')) {
        foreach ($elev["Especes"] as $espece) {
            $esp = Espece::find($espece["id_espece"]);
            if ($esp['race'] ==  $request->input('race')){
                $espece["espece"] = $esp;
                array_push($especesFiltrees, $espece);
            }
        }

        return response()->json($especesFiltrees);
    }
    
    return response()->json($elev_Especes);
});

//Supprimer un Espece depuis le tableau Especes de l'éleveur
Route::put('eleveur/{id}/Espece/{id_espece}', function ($id, $id_espece) {
    Eleveur::where('_id', '=',  $id)
        ->where('Especes.id_espece', '=',  $id_espece)
        ->pull('Especes', array('id_espece' => $id_espece));
    $elev = Eleveur::find($id);
    return response()->json(["objet" => $elev, "msg" => "Espece supprimé du tableau Especes de l'éleveur"]);
});

//Routes Technicien
Route::get('techniciens', function () {
    
    return Technicien::all();
});

Route::post('technicien', function (Request $request) {
    $tech = Technicien::create($request->all());
    return response()->json(["objet" => $tech, "msg" => "Ajouté avec succès"]);
});

Route::get('technicien/{id}', function ($id) {
    try{
    $tech= Technicien::where('id_user', '=',  $id)->orWhere('_id','=',  $id)->first();
    $coops=[];
    foreach ($tech["cooperatives"] as $cooperative) {
        $cop = Cooperative::find($cooperative["id_cooperative"]);
       
        array_push($coops, $cop);
    }
    $tech["cooperatives_data"]=$coops;
    return response()->json($tech);
    }catch (Exception $e){
        return response()->json(["message"=>"Technicien n'éxiste pas"],401);
    }
});

Route::get('dashboard/techniciens', function () {
    
    $output_temp = [];
    $techs = [];
    $output = [];

    $tech = Technicien::all();
    
    foreach ($tech as $tech_item) {
        $count = 0;

        $output_temp["Nom"] = $tech_item['nom'];
        $output_temp["Prenom"] = $tech_item['prenom'];
        $output_temp["Tel"] = $tech_item['tel'];

        foreach ($tech_item["eleveurs"] as $eleveur_item) {
            $especes = Espece::where('id_eleveur', '=', $eleveur_item["id_eleveur"])->get();
            $count = sizeof($especes) + $count;
           
            // $elev = Eleveur::find($eleveur_item["id_eleveur"]);
            // $anim = Animateur::where('id_eleveur', '=', $elev["id_animateur"])->first();
            // $output_temp["Secteur"] = $anim["parametrage_global"]["secteur"]["nomRegion"];

        }
        $output_temp["Nombre animaux"] = $count;
        array_push($techs, $output_temp);
    }

    $output["Nombre techniciens"] = sizeof($techs);
    $output["Techniciens"] = $techs;

    return $output;
}); 


Route::get('Animateur/{id}', function ($id) {
    return Animateur::where('id_user', '=',  $id)->orWhere('_id','=',  $id)->first();
   
});

Route::get('technicien', 'TechnicienController@search'); //multi criteria search

Route::delete('technicien/{id}', function ($id) {
    $tech = Technicien::find($id);
    $tech->delete();
    return response()->json("Supprimé avec succès");
});

Route::put('technicien/{id}', function (Request $request, $id) {
    $tech = Technicien::find($id);
    if ($request->has('nom')) {
        $tech->nom = $request->input('nom');
    }
    if ($request->has('prenom')) {
        $tech->prenom = $request->input('prenom');
    }
    if ($request->has('email')) {
        $tech->email = $request->input('email');
    }
    if ($request->has('tel')) {
        $tech->tel = $request->input('tel');
    }
    if ($request->has('groupement')) {
        $tech->groupement = $request->input('groupement');
    }
    if ($request->has('statut')) {
        $tech->statut = $request->input('statut');
        if($request->input('statut')=="validé")
        foreach ($tech["cooperatives"] as $coope) {
             Cooperative::find($coope["id_cooperative"])->push('techniciens', array('id_technicien' => $tech["_id"]));
       
        }
 
    }
    if ($request->has('eleveurs')) {
        $tech->Especes = $request->input('eleveurs');
    }
    $tech->save();
    $tech_maj = Technicien::find($id);
    return response()->json(["technicien" => $tech_maj, "msg" => "Modifié avec succès"]);
});

//Ajouter un eleveur dans le tableau eleveurs du techncien with {id} in params equal to id_user
Route::put('technicien/{id}/eleveur', function (Request $request, $id) {
    Technicien::where('id_user', '=',  $id)->orWhere('_id', '=',$id)
        ->push('eleveurs', array('id_eleveur' => $request->input('id_eleveur')));
    $tech = Technicien::where('id_user', '=',  $id)->first();
    return response()->json(["objet" => $tech, "msg" => "Eleveur ajouté au tableau eleveurs du technicien"]);
})->middleware('auth:api');

//Récupérer la liste des eleveurs d'un technicien with {id} in params equal to id_user
Route::get('technicien/{id}/eleveurs', function ($id) {
    $tech = Technicien::where('id_user', '=',  $id)->orWhere('_id', '=',  $id)->first();
    $tech_eleveurs = [];
    foreach ($tech["eleveurs"] as $eleveur) {
        $elev = Eleveur::find($eleveur["id_eleveur"]);
        $eleveur["eleveur"] = $elev;
        array_push($tech_eleveurs, $eleveur);
    }
    $array_elev_coop=[];
    foreach ($tech_eleveurs as $elve) {
        $cop = Cooperative::find($elve['eleveur']['id_cooperative']);
        $elve['cooperative']=$cop;
        
            array_push($array_elev_coop, $elve);
    }
    return response()->json($array_elev_coop);
});//->middleware('auth:api')

Route::get('test',function(){
    
    $re=Espece::select('_id')->where('statut','=','disponible')->get();
    return response()->json($re);
});

Route::get('technicien/{id}/especes', function ($id,Request $request) {
    $tech = Technicien::where('id_user', '=',  $id)->orWhere('_id', '=',  $id)->first();
    if ($request->has('races')){
                    $espece = Espece::where('statut','=','disponible')
                    ->whereIn('race',$request->input('races'))
                    ->get();
        return response()->json($espece);
    }else{
       $especes = [];
       if(isset($tech["eleveurs"]))
    foreach ($tech["eleveurs"] as $eleveur) {
        if($eleveur["id_eleveur"]!=null)
                $espece = Espece::where('id_eleveur','=',$eleveur["id_eleveur"])->get();
                if(sizeof($espece)!=0)
                array_push($especes, $espece);
           
        // $eleveur["eleveur"] = ;
        // array_push($especes, $elev);
    }
    return response()->json($especes); 
    }
    // $especes=Espece::All();
   // return $especes;
});

//Supprimer un eleveur depuis le tableau eleveurs du technicien with {id} in params equal to id_user
Route::put('technicien/{id}/eleveur/{id_eleveur}', function ($id, $id_eleveur) {
    
    $test=Technicien::where('id_user', '=',$id)->orWhere('_id', '=',$id)
    ->pull('eleveurs',array('id_eleveur'=>$id_eleveur));
    // $test->save();
    
    $tech = Technicien::where('id_user', '=',  $id)->orWhere('_id','=',$id)->first();
    return response()->json(["objet" => $tech, "msg" => "Eleveur supprimé du tableau eleveurs du technicien"]);
});
//Supprimer une cooperative depuis le tableau cooperatives d'animateur with {id} in params equal to id_user
Route::put('animateur/{id}/cooperative/{id_cooperative}', function ($id, $id_cooperative) {
    
    $test=Animateur::where('id_user', '=',$id)->orWhere('_id', '=',$id)
    ->pull('cooperatives',array('id_cooperative'=>$id_cooperative));
    $tech = Animateur::where('id_user', '=',  $id)->first();
    return response()->json(["objet" => $tech, "msg" => "cooperative supprimé du tableau cooperatives d'animateur"]);
});

Route::get('t/{id}/e/{id_eleveur}',function($id, $id_eleveur){
    //echo $id;
   $test=Technicien::where('id_user',$id)
    ->pull('eleveurs',array('id_eleveur'=>$id_eleveur));
    $tech = Technicien::where('id_user', '=',  $id)->first();
    return response()->json(["objet" => $tech, "msg" => "Eleveur supprimé du tableau eleveurs du technicien"]);


})->middleware('auth:api');
// validation espece a la livraison
Route::put('validationlivraison/{idliv}/commande/{idcmd}/espece/{boucle}', function (Request $request,$idliv,$idcmd,$boucle) {
    $livraison = Livraison::find($idliv);
    $tmpcmp=$livraison->commandes;
    $espDeSortie="";
    for($cmd=0;$cmd<count($tmpcmp) ; $cmd++ ){
        
        if($tmpcmp[$cmd]["id_commande"]==$idcmd){
            for ($esp=0;$esp<count($tmpcmp[$cmd]["especes"]);$esp++){
                if($tmpcmp[$cmd]["especes"][$esp]["boucle"]==$boucle){
                    
                    $tmpcmp[$cmd]["especes"][$esp]["statut_livraion"]=$request->input('statutliv');
                    $tmpcmp[$cmd]["especes"][$esp]["compromis"]=$request->input('compromis');
                    $tmpcmp[$cmd]["especes"][$esp]["boucle_rechange"]=$request->input('boucle_rechange');
                    $tmpcmp[$cmd]["especes"][$esp]["prix_rechange"]=$request->input('prix_rechange');
                    $espDeSortie=$tmpcmp[$cmd]["especes"][$esp];
           }
         }
       } 
    }
    $livraison->commandes=$tmpcmp;
    $livraison->save();
    $livraison = Livraison::find($idliv);

    return   $espDeSortie;
});
Route::get('getEtat/{idliv}/commande/{idcmd}', function ($idliv,$idcmd) {
    $livraison = Livraison::find($idliv);
    $tmpcmp=$livraison->commandes;
    $espDeSortie="";
    for($cmd=0;$cmd<count($tmpcmp) ; $cmd++ ){
        
        if($tmpcmp[$cmd]["id_commande"]==$idcmd){
            return $tmpcmp[$cmd]["especes"];
         }
       } 
    
   
});
// change status commande livré
Route::put('isdelivered/{idliv}/commande/{idcmd}', function ($idliv,$idcmd) {
    $livraison = Livraison::find($idliv);
    $tmpcmp=$livraison->commandes;
    $espDeSortie="";
    for($cmd=0;$cmd<count($tmpcmp) ; $cmd++ ){
        
        if($tmpcmp[$cmd]["id_commande"]==$idcmd){
            $tmpcmp[$cmd]["isDelivered"]=true;
       } 
    }
    $livraison->commandes=$tmpcmp;
    $livraison->save();
    $commande = Commande::find($idcmd);
    $commande->isDelivered = true;
    $commande->save();
    $livraison = Livraison::find($idliv);
    return $livraison ;
});
Route::put('isdelivered/tetesecoursdispo', function (Request $request) {
   $esp =Espece::whereIn("boucle",$request->input("groupstatut"))->get();
   for ($i=0;$i<count($esp);$i++){
    $esp[$i]->statut="disponible";
    $esp[$i]->save();
   }
   
   $espMaj =Espece::whereIn("boucle",$request->input("groupstatut"))->get();
   return $espMaj ;
});
//Ajouter une livraison dans le tableau livraisons du techncien with {id} in params equal to id_user
Route::put('technicien/{id}/livraison', function (Request $request, $id) {
    Technicien::where('id_user', '=',  $id)
        ->push('livraisons', array('id_livraison' => $request->input('id_livraison')));
    $tech = Technicien::where('id_user', '=',  $id)->first();
    return response()->json(["objet" => $tech, "msg" => "Livraison ajoutée au tableau livraison du technicien"]);
})->middleware('auth:api');
//MAJ structure bdd
/*Route::put('MAJD', function () {
    $cmd= Commande::all();
    foreach($cmd as $cmd_item){
        $detail_p=$cmd_item->especes[0]['details_remboursement'];
        $cmd_item->details_remboursement= $detail_p;
        $cmd_item->save(); 
    }
    $cmdm= Commande::all();
    return $cmdm;
});*/
//Routes Commande
Route::get('commandes', function () {
    $cmd = Commande::all();                                          // 3.00s - 2.99s
    // $cmd = DB::table('commande') ->get();                        // 2.84s - 2.60s
    foreach ($cmd as $cmd_item) {
        $cons = Consommateur::where('id_user', '=',  $cmd_item["id_consommateur"])->first();

        $cmd_item["consommateur"] = $cons;
        $cmd_item["device_token"] = $cons["tokenDevice"];

        error_log(gettype($cmd_item));
    }
    return $cmd;
}) ->middleware('gzip'); 

// Route::get('commandes/{idEleveur}/all', function($idEleveur) {
//     $cmd = Commande::all(); 
    
//     $cmd_temp = [];
//     $rating = 0.0;
//     $count = 0;

//     foreach ($cmd as $cmd_item) {
//         foreach ($cmd_item['especes'] as $espece) {
//             if ($espece['id_eleveur'] == $idEleveur)
//                 array_push($cmd_temp, $cmd_item);
//         }
//     }

//     foreach ($cmd_temp as $cd) {
//        if ($cd['rating_produit'] != null) {
//             $rating = $rating + $cd['rating_produit'];
//             $count = $count + 1;
//         } else if ($cd['rating_produit'] == null) {
//             $rating = $rating;
//             $count = $count;
//         }
//     }

//     if ($count == 0)
//         $rating = 0.0;
//     else
//         $rating = $rating / $count;

//     // return $cmd_temp;
//     return $rating;

// }) ->middleware('gzip');


Route::put('commandes', function () {
    $today = Carbon::now();
    $cmd = Commande::all();
    foreach ($cmd as $cmd_item) {
        if ($cmd_item["statut"] == "annulée manuellement" && Carbon::now() > $cmd_item["date_suppression_max"]){
            //$cmd_item->delete();
            $cmd_item["ancien_statut"] = $cmd_item['statut'];
            $cmd_item['statut'] = "supprimé";

            $cmd_item->save();
        }
    }
    return response()->json("Commandes annulées dépassant 5 jours archivées");
});

Route::post('commande', function (Request $request) {
    $cmd = Commande::create($request->all());
    return response()->json(["objet" => $cmd, "msg" => "Ajouté avec succès"]);
});

Route::get('commande/{id}', function ($id) {
    $cmd = Commande::find($id);

    $cons = Consommateur::where('id_user', '=', $cmd["id_consommateur"])->first();
    $elev = Eleveur::find($cmd["especes"][0]["id_eleveur"]);
    // $cooperative=Cooperative::find($cmd["id_cooperative"]);
    $especes=[];
    foreach ($cmd["especes"] as $cmd_item) {
    $espece = Espece::find($cmd_item["id_espece"]);
    if($cmd["statut"]=="avarié"){
    $espece->motif_annulation=$cmd_item["motif_annulation"];
    $espece->choix_client=$cmd_item["choix_client"];
    $especesChangement=[];
    foreach ($cmd_item["produits_changement"] as $pc) {
        $es = Espece::find($pc['id_espece']);
        $es->feedback=$pc['feedback'];
        array_push($especesChangement, $es);
    }
    $espece->produits_changement=$especesChangement;


}
    array_push($especes, $espece);
    }
    $cooperative=Cooperative::find($especes[0]["id_cooperative"]);
    $cmd["consommateur"] = $cons;
    $cmd["eleveur"] = $elev;
    $cmd["cooperative"]=$cooperative;
     $tech=Technicien::find($cooperative["techniciens"][0]["id_technicien"]);
     $cmd["technicien"] =  $tech;
    $cmd["especes"] = $especes;
    return $cmd;
});
// ->middleware('auth:api');


//delete espece from commande

Route::put('commande/{id}/espece/{Id_espece}', function ($id,$Id_espece) {
    
    $cmd = Commande::find($id);
    $espece=Espece::find($Id_espece);
    $espece->statut="disponible";
    $esp=$cmd->especes;
    $especes=[];
    foreach ($esp as $esp_item) {
        $lstelem = json_decode(json_encode($esp_item));
        if($lstelem->id_espece !=$Id_espece){
        array_push($especes, $lstelem);
                                        }
    }
    if($cmd->reçu_avance == null   ){
        $cmd->avance=($cmd->avance-(float)$espece->avance);
        $cmd->prix_total=($cmd->prix_total-(float)$espece->prix);
        $cmd->reste=($cmd->prix_total - $cmd->avance);

    }

    if($cmd->reçu_avance != null   ){
        // $cmd->avance=($cmd->avance-(float)$espece->avance);
        $cmd->prix_total=($cmd->prix_total-(float)$espece->prix)+(float)$espece->avance;
        $cmd->reste=($cmd->prix_total - $cmd->avance);

    }

    // if (($key = array_search($Id_service, $s) !== false) {
    //     unset($s[$key]);
    // }

    // $centr->service = $services;
    $cmd->especes =$especes;
    $cmd->save();
    $espece->save();
    return response()->json(["msg" => " espece supprimé avec succès"]);
    // return  $lstelem->Id;
});

Route::get('commande', 'CommandeController@search'); //multi criteria search
Route::get('commandes/search', 'CommandeController@customSearch'); //multi criteria custom search
Route::get('especes/search', 'EspeceController@customSearch'); //multi criteria custom search
Route::get('commandes/dashboard', 'CommandeController@getCommandesDashboard'); 

Route::get('command', 'CommandeController@verifyDisponibility'); //verifier disponibilité mouton

Route::delete('commande/{id}', function ($id) {
    $cmd = Commande::find($id);
    $cmd->delete();
    return response()->json("Supprimé avec succès");
});
Route::get('deleteEspece/{ides}',function ($ides){
    $cmd=Commande::where('especes.id_espece',$ides)->get();
    return $cmd ;
});
//->middleware('auth:api')
Route::get('test/{id}',function ($id){
    $cmd=Commande::find($id);
    $coop=Cooperative::find($cmd->id_cooperative);
    $date = str_replace("/","-",$cmd->deadline);
    echo date('Y-m-d, H:i:s', strtotime($date. ' + 48 hours'));
   // return $coop->Parametres['delais_paiement']['correction_reçu']."  ".$cmd->deadline ;
});
//API de modification d'une commande avec ajout des reçus ou modification des statuts
Route::put('commande/{id}', function (Request $request, $id) {
    /*$validator = Validator::make($request->all(), [
                                                                    'reçu_avance' => 'image|mimes:jpg,png,jpeg,gif',
                                                                    'reçu_montant_restant' => 'image|mimes:jpg,png,jpeg,gif'
                                                                ]);*/
    // "complement_transmis_le", "reçu_montant_complement","feedback_reçu_montant_complement","msg_refus_complement"
    //error_log($id);
    $cmd = Commande::find($id);
    
    if ($request->has('statut')) {
        $cmd->statut = $request->input('statut');
    }
    if ($request->has('ispaid')) {
        $tmp_details_remboursement=$cmd->details_remboursement;
        $tmp_details_remboursement['isPaid']= $request->input('ispaid');
        $cmd->details_remboursement=$tmp_details_remboursement;}
    if ($request->has('ancien_statut')) {
        $cmd->ancien_statut = $request->input('ancien_statut');
    }
    if ($request->has('complement_transmis_le')) {
        $cmd->complement_transmis_le = $request->input('complement_transmis_le');
    }
    if ($request->has('reçu_montant_complement')) {
        $cmd->reçu_montant_complement = $request->input('reçu_montant_complement');
    }
    if($request->has('msg_refus_complement')){
        $cmd->msg_refus_complement = $request->input('msg_refus_complement');
    }
    if($request->has('feedback_complement')){
        $cmd->feedback_complement = $request->input('feedback_complement');
    }
    if ($request->has('feedback_avance')) {
        $cmd->feedback_avance = $request->input('feedback_avance');
    }
    if ($request->has('msg_refus_avance')) {
        $cmd->msg_refus_avance = $request->input('msg_refus_avance');
    }
    if ($request->has('feedback_reçu_montant_restant')) {
        $cmd->feedback_reçu_montant_restant = $request->input('feedback_reçu_montant_restant');
    }
    if ($request->has('msg_refus_reste')) {
        $cmd->msg_refus_reste = $request->input('msg_refus_reste');
    }
    if ($request->has('reçu_avance')) {
        $cmd->reçu_avance = $request->input('reçu_avance');
    }
    if ($request->has('reçu_montant_restant')) {
        $cmd->reçu_montant_restant = $request->input('reçu_montant_restant');
    }
    if ($request->has('point_relais')) {
        $cmd->point_relais = $request->input('point_relais');
    }
    if ($request->has('avance_transmis_le')) {
        $cmd->avance_transmis_le = $request->input('avance_transmis_le');
    }
    if ($request->has('reste_transmis_le')) {
        $cmd->reste_transmis_le = $request->input('reste_transmis_le');
    }
    if ($request->has('reste')) {
        $cmd->reste = $request->input('reste');
    }
    if ($request->has('avance')) {
        $cmd->avance = $request->input('avance');
    }
    if ($request->has('prix_total')) {
        $cmd->prix_total = $request->input('prix_total');
    }
    if ($request->has('deadline')) {
        // $date = str_replace("/","-",$request->input('deadline'));
        // $coop=Cooperative::find($cmd->id_cooperative);
        // $corr=$coop->Parametres['delais_paiement']['correction_reçu'];
        // $deadlinef = str_replace("-","/",date('Y-m-d, H:i:s', strtotime($date. ' + '.$corr.' hours')));
        // $cmd->deadline = $deadlinef;
        $cmd->deadline = $request->input('deadline') ;
    }

    if ($request->has('date_annulation')) {
        $cmd->date_annulation = $request->input('date_annulation');
    }

    if ($request->has('date_suppression_max')) {
        $cmd->date_suppression_max = $request->input('date_suppression_max');
    }

    if ($request->has('isDelivered')) {
        $cmd->isDelivered = $request->input('isDelivered');
    }

    if ($request->has('motif_rejet')) {
        $cmd->motif_rejet = $request->input('motif_rejet');
    }

    if ($request->has('justification_rejet')) {
        $cmd->justification_rejet = $request->input('justification_rejet');
    }

    if ($request->has('rating_livraison')) {
        $cmd->rating_livraison = $request->input('rating_livraison');
    }

    if ($request->has('justification_rating_livraison')) {
        $cmd->justification_rating_livraison = $request->input('justification_rating_livraison');
    }

    if ($request->has('rating_produit')) {
        $cmd->rating_produit = $request->input('rating_produit');
    }

    if ($request->has('justification_rating_produit')) {
        $cmd->justification_rating_produit = $request->input('justification_rating_produit');
    } 
    if ($request->has('especes')) {
        $cmd->especes = $request->input('especes');
    } 
    $tmp=$cmd->especes;
    if($request->has('especess'))  {
        $cmd->ancien_statut=$cmd->statut;
        $cmd->statut="avarié";
        $dataEspece=$request->input('especess');
        $updateEspece=$cmd->especes;
        for($i=0;$i<count($updateEspece);$i++){
            foreach($dataEspece as $daes ){
                if($updateEspece[$i]['id_espece']==$daes['id_espece']){
                    $updateEspece[$i]['motif_annulation']=$daes['motifAnnul'];
                        foreach($daes['idEspChoix'] as $prch){
                            $espchange=array(
                                "id_espece"=>$prch,
                                "feedback"=>"",
                            );
                            array_push($updateEspece[$i]['produits_changement'],json_decode(json_encode($espchange)));
                        }
                }
            }
        }
        $cmd->especes=$updateEspece;
    } 

    /* if($request->hasFile('reçu_avance'))
                                                                {
                                                                    $reçu_avance=$request->file('reçu_avance');
                                                                    $name = $reçu_avance->getClientOriginalName();
                                                                    error_log($name);
                                                                    $path = public_path().'/uploads/reçus_commandes/'.$id;
                                                                    error_log($path);
                                                                    $reçu_avance->move($path,$name);
                                                                    //move_uploaded_file ($image , $path);
                                                                    //$path_complet=strval($path)+"/"+strval($name);
                                                                    $cmd->reçu_avance='/uploads/reçus_commandes/'.$id.'/'.$name;
                                                                }

                                                                if($request->hasFile('reçu_montant_restant'))
                                                                {
                                                                    $reçu_montant_restant=$request->file('reçu_montant_restant');
                                                                    $name = $reçu_montant_restant->getClientOriginalName();
                                                                    error_log($name);
                                                                    $path = public_path().'/uploads/reçus_commandes/'.$id;
                                                                    error_log($path);
                                                                    $reçu_montant_restant->move($path,$name);
                                                                    //move_uploaded_file ($image , $path);
                                                                    //$path_complet=strval($path)+"/"+strval($name);
                                                                    $cmd->reçu_montant_restant='/uploads/reçus_commandes/'.$id.'/'.$name;
                                                                }*/

    $cmd->save();
    $commande =   Commande::find($id);
    return response()->json(["objet" => $commande, "msg" => "Modifié avec succès"]);
});

//get commandes d'un tech by status

Route::get('commandes/{id_technicien}/avalider', function ($id_technicien) {
    $tech = Technicien::select()->where('id_user', '=',  $id_technicien)->first();
    error_log($tech);
    $cmd = Commande::select()
            ->where('statut', '=',  "validé")
            // ->where('id_cooperative', '=',  $tech["id_cooperative"])
            ->get();
            // ->groupBy('date_de_livraison');
            foreach ($cmd as $cmd_item) {
                $cons = Consommateur::where('id_user', '=',  $cmd_item["id_consommateur"])->first();
                $especes=[];
                $eleveurs=[];
                foreach ($cmd_item["especes"] as $esp){
                    $elev = Eleveur::find($esp["id_eleveur"]);
                    $espece = Espece::find($esp["id_espece"]);
                    array_push($especes, $espece);
                    array_push($eleveurs, $elev);
                }
            
            $cmd_item["consommateur"] = $cons;
            $cmd_item["eleveurs"] = $eleveurs;
            $cmd_item["especes"] = $especes;
            // $cmd_item["device_token"] = $cons["tokenDevice"];
        }
    return $cmd;
    // return $array_commandes;
});
////get tout commandes
Route::get('commandes/{id_technicien}/toutcommandes', function ($id_technicien) {
    $tech = Technicien::select()->where('id_user', '=',  $id_technicien)->first();
    error_log($tech);
    $cmd = Commande::select()
            
            // ->where('id_cooperative', '=',  $tech["id_cooperative"])
            ->get();
            // ->groupBy('date_de_livraison');
            foreach ($cmd as $cmd_item) {
                $cons = Consommateur::find($cmd_item["id_consommateur"]);
                
                $especes=[];
                $eleveurs=[];
                foreach ($cmd_item["especes"] as $esp){
                    $t= json_encode($esp);
                    $elev = Eleveur::find($esp["id_eleveur"]);
                    $espece = Espece::find($esp["id_espece"]);
                   $espjson= json_decode(json_encode($esp));
                    $tmpEspece=json_decode(json_encode('{
                        "id_espece":"'.$espjson->id_espece.'",
                        "id_eleveur":"'.$espjson->id_eleveur.'",
                        "motif_annulation":"'.$espjson->motif_annulation.'",
                        "produits_changement":['.implode(",",$espjson->produits_changement).'],
                        "choix_client":"'.$espjson->choix_client.'",
                        "statut":"'.$espece->statut.'",
                        "boucle":"'.$espece->boucle.'",
                        "poids":"'.$espece->poids.'",
                        "prix":"'.$espece->prix.'",
                        "age":"'.$espece->age.'",
                        "id_eleveur":"'.$espece->id_eleveur.'",
                        "image_face":"'.$espece->image_face.'",
                        "image_profile":"'.$espece->image_profile.'",
                        "image_boucle":"'.$espece->image_boucle.'",
                        "sexe":"'.$espece->sexe.'",
                        "race":"'.$espece->race.'",
                        "categorie":"'.$espece->categorie.'",
                        "espece":"'.$espece->espece.'",
                        "anoc":"'.$espece->anoc.'",
                        "localisation":"'.$espece->localisation.'",
                        "region":"'.$espece->region.'",
                        "updated_at":"'.$espece->updated_at.'",
                        "created_at":"'.$espece->created_at.'",
                        "reference":"'.$espece->reference.'",
                        "id_cooperative":"'.$espece->id_cooperative.'",
                        "boucle_de_naissance":"'.$espece->boucle_de_naissance.'"

                        


                    }'));
                   
                    array_push($especes,json_decode($tmpEspece));
                    array_push($eleveurs, $elev);
                }
            
            $cmd_item["consommateur"] = $cons;
            $cmd_item["eleveurs"] = $eleveurs;
            $cmd_item["especes"] = $especes;
            // $cmd_item["device_token"] = $cons["tokenDevice"];
        }
    return $cmd;
    // return $array_commandes;
});

Route::get('cc', function ($id_technicien) {
    $tech = Technicien::select()->where('id_user', '=',  $id_technicien)->first();
    error_log($tech);
    $cmd = Commande::select()
            ->where('statut', '=',  "validé")
            ->orWhere('statut', '=',  "paiement cash effectué")
            ->where('id_cooperative', '=',  $tech["cooperatives"][0]["id_cooperative"])
            // ->where('id_cooperative', '=',  $tech["id_cooperative"])
            ->get();
            // ->groupBy('date_de_livraison');

        foreach ($cmd as $cmd_item) {
                $cons = Consommateur::where('id_user', '=',  $cmd_item["id_consommateur"])->first();
                $especes=[];
                $eleveurs=[];
                foreach ($cmd_item["especes"] as $esp){
                    $elev = Eleveur::find($esp["id_eleveur"]);
                    $espece = Espece::find($esp["id_espece"]);
                    array_push($especes, $espece);
                    array_push($eleveurs, $elev);
                }
            
            $cmd_item["consommateur"] = $cons;
            $cmd_item["eleveurs"] = $eleveurs;
            $cmd_item["espece"] = $especes;
            // $cmd_item["device_token"] = $cons["tokenDevice"];
         }

        $grouped_cmds_byDate = array();

        foreach($cmd as $cmd_item){
                $grouped_cmds_byDate[$cmd_item['date_de_livraison']][] = $cmd_item;
        }

    return $cmd;
    // return $array_commandes;
});

Route::post('sendmail/{to}/{subject}', function (Request $request, $to, $subject) {
    $content=$request->messagee;
    Mail::raw($content, function ($message) use ($to, $subject) {
        $message->from('myanoc.app@gmail.com', 'MY ANOC');
        $message->to($to)->subject($subject);
    });

    return response()->json("Email envoyé");
});

// CRUD CENTRE 

Route::get('centres', function () {
    return Centre::all();
});

Route::get('centre/{id}', function ($id) {
    return Centre::find($id);
});

Route::post('centre', function (Request $request) {
    $tech = Centre::create($request->all());
    return response()->json(["objet" => $tech, "msg" => "Ajouté avec succès"]);
});

Route::delete('centre/{id}', function ($id) {
    $centre = Centre::find($id);
    $centre->delete();
    return response()->json("Supprimé avec succès");
});

Route::put('centre/{id}', function (Request $request, $id) {
    $centr = Centre::find($id);
    if ($request->has('nom')) {
        $centr->nom = $request->input('nom');
    }
    if ($request->has('description')) {
        $centr->description = $request->input('description');
    }
    $centr->save();
    $centr_maj = Centre::find($id);
    return response()->json(["centre" => $centr_maj, "msg" => "Modifié avec succès"]);
});

//get Centre Service 
Route::get('centre/{id}/services', function ($id) {
    $centr=Centre::find($id);
    
    return $centr->service;
});
//get service by Id
Route::get('centre/{id}/services/{Id_service}', function ($id,$Id_service) {
    $centr=Centre::find($id);
    $s=$centr->service;
    $objeet=json_decode('{}');
    foreach ($s as $service_item) {
        $lstelem = json_decode(json_encode($service_item));
        if($lstelem->Id ==$Id_service){
            $objeet=$lstelem;
                                        }}
    return json_encode($objeet);
});
//Add service 
Route::put('centre/{id}/service', function (Request $request, $id) {
    
    $centr = Centre::find($id);
    $s=$centr->service;
    $objeet=json_decode('{}');
    
    if(sizeof($s)==0 or empty($s)){
        $s=[];
        $objeet->Id=1;
    }
    else{
    $lstelem = json_decode(json_encode($s[array_key_last($s)]));
    
    $objeet->Id = ($lstelem->Id)+1;
    }
    if ($request->has('nom')) {
        $objeet->nom = $request->input('nom');
    }
    if ($request->has('Contact')) {
        $objeet->Contact = $request->input('Contact');
    }
    array_push($s,$objeet);
    $centr->service = $s;
    $centr->save();
   
    return response()->json(["service" => $objeet, "msg" => "ajouté avec succès"]);
    // return  $lstelem->Id;
});

// delete service
Route::put('centre/{id}/services/{Id_service}', function ($id,$Id_service) {
    
    $centr = Centre::find($id);
    $s=$centr->service;
    $services=[];
    foreach ($s as $service_item) {
        $lstelem = json_decode(json_encode($service_item));
        if($lstelem->Id !=$Id_service){
        array_push($services, $lstelem);
                                        }
    }


    // if (($key = array_search($Id_service, $s) !== false) {
    //     unset($s[$key]);
    // }

    // $centr->service = $services;
    $centr->service = $services;
    $centr->save();
   
    return response()->json(["msg" => " service supprimé avec succès"]);
    // return  $lstelem->Id;
});

// Update service  
Route::put('centre/{id}/service/{Id_service}', function (Request $request,$id,$Id_service) {
    $centr = Centre::find($id);
    // error_log('iff');
    $s=$centr->service;
    $services=[];
    foreach ($s as $service_item) {
        $lstelem = json_decode(json_encode($service_item));
        if($lstelem->Id == $Id_service){
            
            if ($request->exists('nom')) {
                error_log('Some message here.');
                $lstelem->nom = $request->input('nom');
                
            }
            if ($request->has('Contact')) {
                $lstelem->Contact = $request->input('Contact');
            }     
            
        }
        array_push($services, $lstelem);
    }
    $centr->service = $services;
    $centr->save();
   
    return response()->json(["obj"=> $request->request, "msg" => "service modifié avec succès"]);
   
});

//CRUD Contact
// get service Contacts

Route::get('centre/{id}/service/{Id_service}/contacts', function ($id,$Id_service) {
    $centr = Centre::find($id);
    $s=$centr->service;
    $contacts=[];
    foreach ($s as $service_item) {
        $lstelem = json_decode(json_encode($service_item));
        if($lstelem->Id == $Id_service){
            $contacts=$lstelem->Contact; 
        }
    }
    // return response()->json(["obj"=> $request->request, "msg" => "service modifié avec succès"]);
    return  $contacts;
});

// get contact by id 
Route::get('centre/{id}/service/{Id_service}/contact/{Id_contact}', function ($id,$Id_service,$id_contact) {
    $centr = Centre::find($id);
    $s=$centr->service;
    $contacts=[];
    foreach ($s as $service_item) {
        $lstelem = json_decode(json_encode($service_item));
        if($lstelem->Id == $Id_service){
            $contacts=$lstelem->Contact; 
            $objeet=json_decode('{}');
            foreach ($contacts as $contact_item) {
                $cntct = json_decode(json_encode($contact_item));
                if($cntct->Id_contact ==$id_contact ){
                    $objeet=$cntct;
                }

            }
        }
    }
    // return response()->json(["obj"=> $request->request, "msg" => "service modifié avec succès"]);
    return  json_encode($objeet);
});
// add Contact 
Route::put('centre/{id}/service/{Id_service}/contacts', function (Request $request,$id,$Id_service) {
    $centr = Centre::find($id);
    $s=$centr->service;
    $contacts=[];
    $serOx=[];
    foreach ($s as $service_item) {
        $lstelem = json_decode(json_encode($service_item));
        if($lstelem->Id == $Id_service){
            $contacts=$lstelem->Contact;  
            
                
                $objeet=json_decode('{}');
                
                if(sizeof($contacts)==0 or empty($contacts)){
                    $contacts=[];
                    $objeet->Id_contact=1;
                }
                else{
                    $lstContact = json_decode(json_encode($contacts[array_key_last($contacts)]));
                    $objeet->Id_contact = ($lstContact->Id_contact)+1;
                    }
                if ($request->has('Nom')) {
                    $objeet->Nom = $request->input('Nom');
                    }
                if ($request->has('type')) {
                        $objeet->type = $request->input('type');
                        }    

                if ($request->has('nom')) {
                        $objeet->nom = $request->input('nom');
                            }
                if ($request->has('prenom')) {
                        $objeet->prenom = $request->input('prenom');
                            }
                if ($request->has('adresse')) {
                        $objeet->adresse = $request->input('adresse');
                            }
                        
                if ($request->has('tel')) {
                        $objeet->tel = $request->input('tel');
                            }
                if ($request->has('fax')) {
                        $objeet->fax = $request->input('fax');
                            }
                if ($request->has('email')) {
                        $objeet->email = $request->input('email');
                            }
                array_push($contacts,$objeet);
                
                $lstelem->Contact=$contacts;
                array_push($serOx,$lstelem);
        }
        else{
            array_push($serOx,$lstelem);
        }


    }
    $centr->service = $serOx;
    $centr->save();
    return response()->json(["obj"=> $request, "msg" => "Contact ajouté avec  succès"]);
    // return  $contacts;
 
});

//update contact
Route::put('centre/{id}/service/{Id_service}/contacts/{id_contact}', function (Request $request,$id,$Id_service,$id_contact) {
    $centr = Centre::find($id);
    $s=$centr->service;
    $contacts=[];
    $serOx=[];
    foreach ($s as $service_item) {
        $lstelem = json_decode(json_encode($service_item));
        if($lstelem->Id == $Id_service){
            $contacts=$lstelem->Contact; 
            $conatctUpdated=[]; 
                // $lstContact = json_decode(json_encode($contacts[array_key_last($contacts)]));
                foreach ($contacts as $contact_item) {
                    if($contact_item->Id_contact == $id_contact){     
                        // $objeet=json_decode('{}');
                        
                        if ($request->has('Nom')) {
                            $contact_item->Nom = $request->input('Nom');
                            }
                        if ($request->has('type')) {
                            $contact_item->type = $request->input('type');
                                }    

                        if ($request->has('nom')) {
                            $contact_item->nom = $request->input('nom');
                                    }
                        if ($request->has('prenom')) {
                            $contact_item->prenom = $request->input('prenom');
                                    }
                        if ($request->has('adresse')) {
                            $contact_item->adresse = $request->input('adresse');
                                    }
                                
                        if ($request->has('tel')) {
                            $contact_item->tel = $request->input('tel');
                                    }
                        if ($request->has('fax')) {
                            $contact_item->fax = $request->input('fax');
                                    }
                        if ($request->has('email')) {
                            $contact_item->email = $request->input('email');
                                    }
                        array_push($conatctUpdated,$contact_item);
                                }

                        else{
                            array_push($conatctUpdated,$contact_item);
                        }

                    }
                $lstelem->Contact=$conatctUpdated;
                array_push($serOx,$lstelem);
        }
        
        else{
                array_push($serOx,$lstelem);
                }

    }
  
    $centr->service = $serOx;
    $centr->save();
    return response()->json(["obj"=> $request, "msg" => "Contact modifié avec  succès"]);
    // return  $contacts;
 
});


// delete Contact
Route::put('centre/{id}/service/{Id_service}/contact/{id_contact}', function ($id,$Id_service,$id_contact) {
    $centr = Centre::find($id);
    $s=$centr->service;
    $contacts=[];
    $serOx=[];
    foreach ($s as $service_item) {
        $lstelem = json_decode(json_encode($service_item));
        if($lstelem->Id == $Id_service){
            $contacts=$lstelem->Contact; 
            $conatctUpdated=[]; 
                // $lstContact = json_decode(json_encode($contacts[array_key_last($contacts)]));
                foreach ($contacts as $contact_item) {
                    if($contact_item->Id_contact != $id_contact){     
                        array_push($conatctUpdated,$contact_item);
                                }   
                    }
                $lstelem->Contact=$conatctUpdated;
                array_push($serOx,$lstelem);
        }
        else{
                array_push($serOx,$lstelem);
                }
    }
  
    $centr->service = $serOx;
    $centr->save();
    return response()->json([ "msg" => "Contact ajouté avec  succès"]);
    // return  $contacts;
 
});

//Crud cooperative

Route::get('cooperatives', function () {
    return cooperative::all();
});

Route::get('cooperative/{id}', function ($id) {
   $cooperative=cooperative::find($id);

   $tech=[];
    foreach ($cooperative->techniciens as $t) {
        $elm = Technicien::find($t["id_technicien"]);
        array_push($tech,$elm);
    }
    $cooperative->tech=$tech;

    $animateur=Animateur::where("id_user",$cooperative["id_animateur"])->first();
    $cooperative["animateur"]=$animateur;

return  $cooperative;
    
});

Route::get('cooperative', 'CooperativeController@search')->middleware('gzip'); //multi criteria search

// return parametres globale d'un animateur
Route::get('parametrageGlobale/{id}', function ($id) {
    $parametrage=Animateur::where("id_user",$id)->first();
    $coop=[];
    foreach($parametrage->parametrage_global["secteur"]["cooperative"] as $item_coop){
        $tmp_coop=cooperative::find($item_coop);
        array_push($coop,$tmp_coop);
    }
    $result=$parametrage->parametrage_global;
    $result["secteur"]["cooperative"]=$coop;
 return  $result;
     
 });
 //modifier les parametres globale d'un animateur
 Route::put('parametrageGlobaleUpdate/{id}', function (Request $request,$id) {
    $parametrage=Animateur::where("id_user",$id)->first();
    $tmp_param = $parametrage->parametrage_global;
    if($request->has('race')){
        for($i=0;$i<count($tmp_param["categories"]) ;$i++) {
            if($tmp_param["categories"][$i]["categorie"] == $request->input('race')["categorie"]){
               $tmp_race=json_decode("{}");
                $tmp_race->race=$request->input('race')["race"];
                $tmp_race->description=$request->input('race')["description"];
                array_push($tmp_param["categories"][$i]["races"],$tmp_race);
                //return $item_categorie["races"];
            }
           
        }
    }
    if($request->has('motif')){
        array_push($tmp_param["motif_annulation"],$request->input('motif'));
    }
    if($request->has('motif_ar')){
        array_push($tmp_param["motif_annulation_ar"],$request->input('motif_ar'));
    }
    if($request->has('Nom_Secteur')){
        $tmp_param["secteur"]["nomRegion"] = $request->input('Nom_Secteur');
    }
    
    if($request->has('region')){
        $pr=(array)$request->input('region')["Provinces"];
        $RegionExist=false;
         if(count($tmp_param["secteur"]["Regions"])>0)
        for($i=0;$i<count($tmp_param["secteur"]["Regions"])-1 ;$i++){
   
            if ($tmp_param["secteur"]["Regions"][$i]["Region"] == $request->input('region')["Region"]){
                
                for($j=0;$j<count($pr) ;$j++){
                    array_push($tmp_param["secteur"]["Regions"][$i]["Provinces"],$pr[$j]);
                }
                $RegionExist=true;
                break;
            }
        }

      
        if(!$RegionExist){
            array_push($tmp_param["secteur"]["Regions"],$request->input('region'));
        }

        
    }
    $parametrage->parametrage_global = $tmp_param;
    $parametrage->save();
    $parametrage_MAJ=Animateur::where("id_user",$id)->first();
    $coop=[];
    foreach($parametrage_MAJ->parametrage_global["secteur"]["cooperative"] as $item_coop){
        $tmp_coop=cooperative::find($item_coop);
        array_push($coop,$tmp_coop);
    }
    $result=$parametrage_MAJ->parametrage_global;
    $result["secteur"]["cooperative"]=$coop;
 return  $result;
//  return $tmp_param["secteur"]["Regions"][2]["Provinces"];
     
 });
 // supprimer les parametres globale
 Route::put('parametrageGlobaleDelete/{id}', function (Request $request,$id) {
    $parametrage=Animateur::where("id_user",$id)->first();
    $tmp_param = $parametrage->parametrage_global;
    
   if($request->has('raceToDelete')){
        
        for($i=0;$i<count($tmp_param["categories"]) ;$i++) {
            
            if($tmp_param["categories"][$i]["categorie"] == $request->input('raceToDelete')["categorie"]){
                if (($key = array_search( $request->input('raceToDelete')["race"],$tmp_param["categories"][$i]["races"])) !== false) {
                    unset($tmp_param["categories"][$i]["races"][$key]);
                    $tmp_param["categories"][$i]["races"] = array_values($tmp_param["categories"][$i]["races"]);
                }

                //return $item_categorie["races"];
            }
           
        }
    }
    if($request->has('motif') ){
        if (($key = array_search( $request->input('motif'),$tmp_param["motif_annulation"])) !== false) {
            unset($tmp_param["motif_annulation"][$key]);
            unset($tmp_param["motif_annulation_ar"][$key]);
            $tmp_param["motif_annulation"] = array_values($tmp_param["motif_annulation"]);
            $tmp_param["motif_annulation_ar"] = array_values($tmp_param["motif_annulation_ar"]);
        }
    }

    if($request->has('province')){
        for($i=0;$i<count($tmp_param["secteur"]["Regions"]) ;$i++){
                for($j=0;$j<count($tmp_param["secteur"]["Regions"][$i]["Provinces"]);$j++){
                    if (($key = array_search( $request->input('province'),$tmp_param["secteur"]["Regions"][$i]["Provinces"])) !== false) {
                        unset($tmp_param["secteur"]["Regions"][$i]["Provinces"][$key]);
                        $tmp_param["secteur"]["Regions"][$i]["Provinces"]=array_values($tmp_param["secteur"]["Regions"][$i]["Provinces"]);
                    }
                }
        }
    }
    $parametrage->parametrage_global = $tmp_param;
    $parametrage->save();
    $parametrage_MAJ=Animateur::where("id_user",$id)->first();
    $coop=[];
    foreach($parametrage_MAJ->parametrage_global["secteur"]["cooperative"] as $item_coop){
        $tmp_coop=cooperative::find($item_coop);
        array_push($coop,$tmp_coop);
    }
    $result=$parametrage_MAJ->parametrage_global;
    $result["secteur"]["cooperative"]=$coop;
 return $result;
     
 });

Route::post('cooperative', function (Request $request) {
    $coop = cooperative::create($request->all());
    Animateur::where('id_user', '=',  $coop["id_animateur"])->orWhere('_id', '=',  $coop["id_animateur"])
        ->push('cooperatives', array('id_cooperative' => $coop["_id"]));
    return response()->json(["objet" => $coop, "msg" => "Ajouté avec succès"]);
});

Route::delete('cooperative/{id}', function ($id) {
    $coop = Cooperative::find($id);
    $coop->delete();
    return response()->json("Supprimé avec succès");
});
Route::put('cooperative/{id}', function (Request $request, $id) {
    $coop = Cooperative::find($id);
    $updateparam=$coop->parametres;
    $updatelivr=$coop->livraison;
    if ($request->exists('paiement_rapide')) {
        $coop->paiement_rapide = $request->input('paiement_rapide');
    }
    if ($request->exists('pourcentage_avance')) {
        $coop->pourcentage_avance = $request->input('pourcentage_avance');
    }
    if ($request->exists('nom')) {
        $coop->nom = $request->input('nom');
    }
    if ($request->exists('region')) {
        $coop->region= $request->input('region');
    }
    if ($request->exists('ville')) {
        $coop->ville= $request->input('ville');
    }
    if ($request->exists('description')) {
        $coop->description = $request->input('description');
    }
    if ($request->exists('rib')) {
        $coop->rib = $request->input('rib');
    }
    if ($request->exists('adresse')) {
        $coop->adresse = $request->input('adresse');
    }
    if ($request->exists('delai_paiement_avance')) {
        $updateparam['delais_paiement']['delai_avance']=$request->input('delai_paiement_avance');
        $coop->parametres = $updateparam;
        
    }
    if ($request->exists('delai_cash')) {
        $updateparam['delais_paiement']['delai_cash']=$request->input('delai_cash');
        $coop->parametres = $updateparam;
        
    }
    if ($request->exists('delai_paiement_reste')) {
        $updateparam['delais_paiement']['delai_reste']=$request->input('delai_paiement_reste');
        $coop->parametres = $updateparam;
    }
    if ($request->exists('delai_paiement_complement')) {
        $updateparam['delais_paiement']['delai_complement']=$request->input('delai_paiement_complement');
        $coop->parametres = $updateparam;
    }
    if ($request->exists('delai_paiement_bonus')) {
        $updateparam['delais_paiement']['bonus']=$request->input('delai_paiement_bonus');
        $coop->parametres = $updateparam;
    }
    if ($request->exists('delai_paiement_correction')) {
        $updateparam['delais_paiement']['correction_reçu']=$request->input('delai_paiement_correction');
        $coop->parametres = $updateparam;
    }
    if($request->exists('livreurs')){
        $tmp =$coop->livreurs;
        array_push($tmp,$request->input('livreurs'));
        $coop->livreurs=$tmp;
    }

    ////////////////////////////////////////
    if ($request->exists('point_relais')) {

        for($i=0;$i<count($updatelivr);$i++){
            if($updatelivr[$i]["ville_livraison"]==$request->input('ville')){
                $updatelivr[$i]["point_relais"] =$request->input('point_relais');
       
            
       }
        }
        $coop->livraison=$updatelivr;
    }
    if ($request->exists('prix_VIP')) {
         for($i=0;$i<count($updatelivr);$i++){
            if($updatelivr[$i]["ville_livraison"]==$request->input('ville')){
                $updatelivr[$i]["prix_VIP"] =$request->input('prix_VIP');
       
            
       }
        }
        $coop->livraison=$updatelivr;
    }
    if ($request->exists('marge_risque')) {
        for($i=0;$i<count($updatelivr);$i++){
            if($updatelivr[$i]["ville_livraison"]==$request->input('ville')){
                $updatelivr[$i]["marge_risque"] =$request->input('marge_risque');
       
            
       }
        }
        $coop->livraison=$updatelivr;
    }
    if ($request->exists('pt_point_relais')) {
         for($i=0;$i<count($updatelivr);$i++){
            if($updatelivr[$i]["ville_livraison"]==$request->input('ville')){
                $updatelivr[$i]["prix_transport_relais"] =$request->input('pt_point_relais');
       
            
       }
        }
        $coop->livraison=$updatelivr;
    }
    if ($request->exists('pt_domicile')) {
         for($i=0;$i<count($updatelivr);$i++){
            if($updatelivr[$i]["ville_livraison"]==$request->input('ville')){
                $updatelivr[$i]["prix_transport_domicile"] =$request->input('pt_domicile');
       
            
       }
        }
        $coop->livraison=$updatelivr;
    }
    if ($request->exists('ajoutevillelivraison')) {
        array_push($updatelivr,$request->input('ajoutevillelivraison'));
       $coop->livraison=$updatelivr;
   }
   if ($request->exists('deleteVille')) {
       $tmp=[];
    for($i=0;$i<count($updatelivr);$i++){
        if($updatelivr[$i]["ville_livraison"]!=$request->input('deleteVille')){
            array_push($tmp,$updatelivr[$i]);
           
           
        
   }
    }
    $updatelivr=$tmp;
    
    $coop->livraison=$updatelivr;
}
    
    $coop->save();
    $coop_maj = Cooperative::find($id);
    return response()->json(["Cooperative" =>  $coop_maj , "msg" => "Modifié avec succès"]);
});



Route::put('cooperative/{id}/technicien', function (Request $request, $id) {
    $coop = Cooperative::find($id);
    $tech=$coop->techniciens;
    
    array_push($tech,array('id_technicien' =>$request->input('id_technicien')));
    $coop->techniciens=$tech;
    $coop->save();
    $coop_maj = Cooperative::find($id);
    return response()->json(["Cooperative" => $coop_maj, "msg" => "Techncien bien Ajouté "]);
});


Route::put('cooperative/{id}/categorie', function (Request $request, $id) {
    $coop = Cooperative::find($id);
    $categories=[];
    if($coop->categories !=null){
        $categories=$coop->categories;
    }
    
    array_push($categories,array('categorie' =>$request->input('categorie'),'races'=>array()));
    $coop->categories=$categories;
    $coop->save();
    $coop_maj = Cooperative::find($id);
    return response()->json(["Cooperative" => $coop_maj, "msg" => "categorie bien Ajoutée "]);
});


Route::put('cooperative/{id}/categorie/{categorie}', function ($id,$categorie) {
    $coop = Cooperative::find($id);
    $categories=[];
    if($coop->categories !=null){
        $categories=$coop->categories;
    }
    $categoriesFiltre=[];
    foreach ($categories as $categorie_item) {
        if($categorie_item["categorie"]!=$categorie)
        array_push($categoriesFiltre,$categorie_item);
    }
    
    $coop->categories=$categoriesFiltre;
    $coop->save();
    $coop_maj = Cooperative::find($id);
    return response()->json(["Cooperative" => $coop_maj, "msg" => "categorie bien supprimée "]);
});

//add race
Route::put('cooperative/add/{id}/categorie/{esp}/race/{race}/{prix_ref_kg}', function (Request $request,$id,$esp,$race,$prix_ref_kg) {
    $coop = Cooperative::find($id);
    $especes=[];
    $especes=$coop->especes;
    if($especes!=null){
        
    
    $filtredcat=[];

    foreach ($especes as $espece_item) {
      
        $races=$espece_item["races"];
       
        if($espece_item["espece"]==$esp) {
            $jsonrace=json_decode(json_encode(["nom_race" => $race, "prix_reference_kg" => $prix_ref_kg]));
            //return  $espece_item;
            // if($categorie_item["races"]!=null) $races=$categorie_item["races"];
        array_push($races,$jsonrace);
        $espece_item["races"]=$races;
        
    }
    array_push($filtredcat,$espece_item);

    }
    
    $coop->especes=$filtredcat;
}
    $coop->save();
    $coop_maj = Cooperative::find($id);
    return  $coop_maj;
    // return response()->json(["Cooperative" => $coop_maj, "msg" => "Race bien Ajoutée "]);
});


//delete race

Route::put('cooperative/delete/{id}/categorie/{espece}/race/{race}', function ($id,$espece,$race) {
    $coop = Cooperative::find($id);
    $especes=[];
    
    if($coop->especes !=null){
        $especes=$coop->especes;
    
    $filtredcat=[];

    foreach ($especes as $especes_item) {

     
        
       $racefiltr=[];

     
       
        if($especes_item["espece"]==$espece) {
            $races=$especes_item["races"];
          
   for($i=0;$i<count($races) ;$i++) {
            
            if($races[$i]["nom_race"] != $race){
                array_push($racefiltr,$races[$i]);
               
            }
        }
                
               $especes_item["races"]=$racefiltr; 
            
         
    }
    array_push($filtredcat,$especes_item);

    }
    
    $coop->especes=$filtredcat;
}
    $coop->save();
    $coop_maj = Cooperative::find($id);
    return  $coop_maj;
    // return response()->json(["Cooperative" => $coop_maj, "msg" => "Race bien Ajoutée "]);
});



// add date de livraison 

Route::put('cooperative/{id}/datelivraison', function (Request $request, $id) {
    $coop = Cooperative::find($id);
    $coop->jourLivraisonAvant=$request->input('jourLivraisonAvant');
    $coop->occasion="aid";
    $coop->save();
    $coop_maj = Cooperative::find($id);
    return response()->json(["Cooperative" => $coop_maj, "msg" => "datelivraison bien Ajoutée "]);
});
Route::put('CommandeLivraisonEnCours', function (Request $request){
    
    $cmds = Commande::whereIn("_id",$request->input('cmd'))
    
   ->get();
   foreach($cmds as $i){
      
       $i->statut = "livraison en cours";
    $i->save();
   }
   return $request->input('cmd');
});
// changement occasion
Route::put('cooperative/{id}/changeoccasion', function(Request $request,$id){
    $coop = Cooperative::find($id);
   
    $coop->occasion=$request->input('occasion');
    $coop->save();
    $coop_maj = Cooperative::find($id);
    return response()->json(["Cooperative" => $coop_maj, "msg" => "Occasion bien changé "]);
});
// changement entree ville
Route::put('cooperative/{id}/entreeville', function(Request $request,$id){
    $coop = Cooperative::find($id);
    $liv=$coop->livraison;
    for($i=0;$i<count($liv);$i++){
        if($liv[$i]["ville_livraison"]==$request->input('ville')){
            $liv[$i]["entrée_ville"] =$request->input('EntreeVille');
   if($request->input('EntreeVille')){
    $liv[$i]["point_relais"] = "entrée ville";
        }
        
   }
    }
   
   $coop->livraison=$liv;
    $coop->save();
    $coop_maj = Cooperative::find($id);
    return response()->json(["Cooperative" => $coop_maj, "msg" => "Entree Ville bien changé "]);
});

// delete date de livraison

Route::put('cooperative/{id}/datelivraison/{datelivraison}', function ($id,$datelivraison) {
    $coop = Cooperative::find($id);
    $dateslivraison=[];
    if($coop->dateslivraison !=null){
        $dateslivraison=$coop->dateslivraison;
    }
    $dateslivraisonFiltre=[];
    foreach ($dateslivraison as $datelivraison_item) {
        if($datelivraison_item["datelivraison"]!=$datelivraison)
        array_push($dateslivraisonFiltre,$datelivraison_item);
    }
    
    $coop->dateslivraison=$dateslivraisonFiltre;
    $coop->save();
    $coop_maj = Cooperative::find($id);
    return response()->json(["Cooperative" => $coop_maj, "msg" => "datelivraison bien supprimée "]);
});


// commande reference 

Route::get('reference/{id}', function ( $id) {
    // $categorie_espece = "";
    $region_indicatif = "";


    $esp = Espece::find($id);

    $coop=Cooperative::find($esp->id_cooperative);
    $par=$coop->parametres['delais_paiement'];
    $livraison=$coop->livraison;
    $occasion=$coop->occasion;   
    $paiement_rapide=$coop->paiement_rapide;  

    $tech = Technicien::where('_id', '=', $coop->techniciens[0]['id_technicien'])->orWhere('id_user', '=', $coop->techniciens[0]['id_technicien'])->first();
    $banque_tech = $tech->agence_bancaire;

    // $par->delais_paiement;
    $cmd=Commande::latest()->first();
    
    $date = date("Y-m-d H:i:s");

    //Indicatifs région espèce
    if($coop->region == "Tanger-Tétouan-Al Hoceïma")
        $region_indicatif = "TTAH";
    if($coop->region == "l'Oriental")
        $region_indicatif = "O";
    if($coop->region == "Fès-Meknès")
        $region_indicatif = "FM";
    if($coop->region == "Rabat-Salé-Kénitra")
        $region_indicatif = "RSK";
    if($coop->region == "Béni Mellal-Khénifra")
        $region_indicatif = "BMK";
    if($coop->region == "Casablanca-Settat")
        $region_indicatif = "CS";
    if($coop->region == "Marrakech-Safi")
        $region_indicatif = "MS";
    if($coop->region == "Drâa-Tafilalet")
        $region_indicatif = "DT";
    if($coop->region == "Souss-Massa")
        $region_indicatif = "SM";
    if($coop->region == "Guelmim-Oued Noun")
        $region_indicatif = "GON";
    if($coop->region == "Laâyoune-Sakia El Hamra")
        $region_indicatif = "LSEH";
    if($coop->region == "Dakhla-Oued Ed-Dahab")
        $region_indicatif = "DOED";

        if($cmd == null){
      
            $ref=$region_indicatif .  "." . date("d.m.Y").".1";
        }
       
        else if($cmd != null){
            error_log("Second IF");
    
            if ($cmd->reference != null){
                error_log("Third IF");
                $lastRef = explode(".", $cmd->reference);
                $ref=$region_indicatif .  "." . date("d.m.Y").".".($lastRef[4] + 1);
                // $espece->reference =  $region_indicatif . "." . ($lastRef[4] + 1) . "." . date_format($date, 'd.m.y');
            }
            else if ($cmd->reference == null ){
                $ref=$region_indicatif .  "." . date("d.m.Y").".1";
            }
        }
    // $ref=$region_indicatif .  "." . date("d.m.Y");
    // $espece->save();
   return response()->json(["reference" => $ref,"parametres"=> $par,"livraison"=>$livraison,"occasion"=>$occasion, "paiement_rapide"=>$paiement_rapide, "banque_tech" =>$banque_tech]); 
    // return $tech;
    // return $delai_avance;
});
// ->middleware('auth:api');

// Sending title , body, icon & id in request
Route::post('sendPushNotif', function (Request $request) {
    (new FCMPushNotification())->sendNotification($request);
});

Route::get('regions', function () {
   
   $regions=[];
   $reg=Eleveur::select(['region',])->groupBy('region')->get()->toArray();
   $regville=Eleveur::select(['region','ville'])->groupBy('ville')->get()->toArray();
   foreach ($reg as $reg_item) {
    $jsonRegion=json_decode('{}');
    $villes=[];
       foreach ($regville as $regvile_item) {
           if($regvile_item["region"]==$reg_item["region"]) {
               $jsonVille=json_decode('{}');
               $jsonVille->ville=$regvile_item["ville"];

            array_push($villes,$jsonVille);
           }
         $jsonRegion->region=$reg_item["region"];
         $jsonRegion->villes=$villes;
       }
      
       array_push($regions,$jsonRegion);

}
    return $regions;
});


//Routes Livraison 

Route::get('livraisons', function () {
    //return Livraison::where("date_livraison",date("Y-m-d"))->get();
    return Livraison::all();
});
// ->groupBy('date_livraison')
Route::post('livraison', function (Request $request) {
    $liv = Livraison::create($request->all());
    return response()->json(["objet" => $liv, "msg" => "Ajouté avec succès"]);
});

Route::get('livraison/{id}', function ($id) {
    return Livraison::find($id);
    
});

//cmd.statut == "validé" || cmd.statut=="paiement cash effectué"
Route::delete('livraison/{id}', function ($id) {
    $livraison =Livraison::find($id);
    $cmd= $livraison["commandes"];
    foreach($cmd as $cmd_item){
        $commande = Commande::find($cmd_item["id_commande"]);
        if($commande["reçu_avance"]==null){
            $commande->statut="paiement cash effectué";
        }
        else 
            $commande->statut="validé";

      $commande->save();

    }
    $livraison->delete();
    return response()->json("Supprimé avec succès");
    
});

Route::get('customlivraison/{id}', function (Request $request,$id) {
    $liv=Livraison::find($id);
    $resultat = [];
        $boucle = $request->input('boucle_espece');
        $nomClient = $request->input('Nom_Client');
        $statut = $request->input('Statut');
        $villeLivraison = $request->input('ville_livraison');
        $typeLivraison  = $request->input('type_livraison');
    foreach($liv->commandes as $cmd_item){
        if ($request->has('boucle_espece')) {
            foreach ($cmd_item["especes"] as $e) {
                
                if ($e["boucle"] == $boucle)
                    
                    array_push($resultat,$cmd_item);
                
            }

        }
        if ($request->has('Nom_Client')) {
          
            if ($cmd_item["client"]["nom"] == $nomClient)
                array_push($resultat,$cmd_item);
            // else
            //     $resultat["msg"] ="Aucune commande trouvée avec ce nom client";
        }
        if ($request->has('ville_livraison') ) {
                  
            
              
              if ($cmd_item["ville_livraison"] == $villeLivraison)
                  
                  array_push($resultat,$cmd_item);
            
              
              // else
              //     $resultat["msg"] ="Aucune commande trouvée avec cette ville de livraison";
          }
          
          if ($request->has('ville_livraison') == false && $request->has('type_livraison')) {
            if ($typeLivraison == "relais") {
                    if ($cmd_item["isDeliveredTo_PointRelais"] == true)
                        array_push($resultat,$cmd_item);
                    // else
                    //     $resultat["msg"] ="Aucune commande trouvée avec livraison au point relais";
            }

            if($typeLivraison == "domicile"){
                    if ($cmd_item["isDeliveredTo_Home"] == true)
                        array_push($resultat,$cmd_item);
                    // else
                    //     $resultat["msg"] ="Aucune commande trouvée avec livraison à domicile";
            }
        }

        if ($request->has('ville_livraison') && $request->has('type_livraison')) {
            if ($typeLivraison == "relais") {
                    if ($cmd_item["ville_livraison"] == $villeLivraison && $cmd_item["isDeliveredTo_PointRelais"] == true)
                        array_push($resultat,$cmd_item);
                    // else
                    //     $resultat["msg"] ="Aucune commande trouvée avec livraison au point relais";
            }

            if($typeLivraison == "domicile"){
                    if ($cmd_item["ville_livraison"] == $villeLivraison && $cmd_item["isDeliveredTo_Home"] == true)
                        array_push($resultat,$cmd_item);
                    // else
                    //     $resultat["msg"] ="Aucune commande trouvée avec livraison à domicile";
            }
        }
        if ($request->has('Statut') ) {
                  
             
              
            if ($statut == "Livraison en attente") {
                if ($cmd_item["isDelivered"] == false)
                    array_push($resultat,$cmd_item);
                // else
                //     $resultat["msg"] ="Aucune commande trouvée avec livraison au point relais";
        }

        if($statut == "Livraisons effectuées"){
           
                if ($cmd_item["isDelivered"] == true)
                    array_push($resultat,$cmd_item);
                // else
                //     $resultat["msg"] ="Aucune commande trouvée avec livraison à domicile";
        }
            
            // else
            //     $resultat["msg"] ="Aucune commande trouvée avec cette ville de livraison";
        }
    }
    if($request->has('reset')){
        return $liv;
    }else{
        $liv->commandes=$resultat;
    return $liv;
    }
    
    
});
// Route::get('livraison/{id}/commandes', function ($id) {

//    $cmd= Livraison::select('commandes')->where('_id','=',  $id)->get();
//     $cmd_ox=$cmd;


//     // foreach ($cmd as $cmd_item) {
//     //     $jsonRegion=json_decode('{}');
//     //     $villes=[];
//     //        foreach ($regville as $regvile_item) {
//     //            if($regvile_item["region"]==$reg_item["region"]) {
//     //                $jsonVille=json_decode('{}');
//     //                $jsonVille->ville=$regvile_item["ville"];
    
//     //             array_push($villes,$jsonVille);
//     //            }
//     //          $jsonRegion->region=$reg_item["region"];
//     //          $jsonRegion->villes=$villes;
//     //        }
          
//     //        array_push($regions,$jsonRegion);
    
//     // }

    
    
// });


// changement 

Route::put('commande/{id}/{idespece}/{idpc}', function (Request $request,$id,$idespece,$idpc) {
    $staut="";
    $montant_de_remboursement=0;
    $complement=0;
    $ribclient=$request->input('rib');
    $nomclient=$request->input('nom');
    
    $remb=json_decode('{}');
    $cmd = Commande::find($id);
    $espece = Espece::find($idespece);
    $especeCh = Espece::find($idpc);
    // $cons = Consommateur::where('id_user', '=', $cmd["id_consommateur"])->first();
    // $elev = Eleveur::find($cmd["especes"][0]["id_eleveur"]);
    // $cooperative=Cooperative::find($cmd["id_cooperative"]);
    $especes=[];
    foreach ($cmd["especes"] as $cmd_item) {
    
        if($cmd_item["id_espece"]==$idespece){
            $especesChangement=[];
            $cmd_item["choix_client"]="changement";

                foreach ($cmd_item["produits_changement"] as $pc) {
                    // $es = Espece::find($pc['id_espece']);
                    if($pc["id_espece"]==$idpc){

                        $pc["feedback"]="validé";


                    }
                    else if ($pc["id_espece"]!=$idpc){

                        $pc["feedback"]="refusé";


                    }

                    array_push($especesChangement, $pc);
                }
                $cmd_item["produits_changement"]=$especesChangement;


          }
          array_push($especes, $cmd_item);
            //condition sur statut 
          if( $cmd["ancien_statut"]=="en attente de paiement avance" ) {

            $cmd["avance"]=$cmd["avance"]-(int)$espece["avance"]+(int)$especeCh["avance"];

            $cmd["prix_total"]=$cmd["prix_total"]-(int)$espece["prix"]+(int)$especeCh["prix"];

            $cmd["reste"]=$cmd["reste"]-((int)$espece["prix"]-(int)$espece["avance"])+((int)$especeCh["prix"]-(int)$especeCh["avance"]);
           
            // $cmd["statut"]="avarié_changement";
            // $cmd["ancien_statut"]="produit avarié";
            $staut="avarié_changement";

          }
        
          else if( $cmd["ancien_statut"]=="en attente de paiement du reste"){                                               
                                                                                                                            
                                                                                                                            

            $cmd["prix_total"]=$cmd["prix_total"]-(int)$espece["prix"]+(int)$especeCh["prix"];                              

            $cmd["reste"]=$cmd["reste"]-(int)$espece["prix"] + (int)$especeCh["prix"]  ;            
            // $cmd["reste"]=$cmd["reste"]-((int)$espece["prix"]-(int)$espece["avance"])+((int)$especeCh["prix"]-(int)$especeCh["avance"]) +((int)$especeCh["avance"]-(int)$espece["avance"]);
            $cmd["statut"]="avarié_changement";
            // $cmd["ancien_statut"]="produit avarié";

          }

          else if($cmd["ancien_statut"]=="validé"){

                if((int)$espece["prix"]<(int)$especeCh["prix"]){
                    $complement=(int)$especeCh["prix"]-(int)$espece["prix"];
                    // $cmd["complement"]=(int)$especeCh["prix"]-(int)$espece["prix"];
                    // $cmd["deadline"]=Carbon::now()->addDays(5);
                    // $cmd["statut"]="en attente de paiement du complement";
                    // $cmd["ancien_statut"]="avarié";
                    $staut="en attente de paiement du complement";
                    

                }
                else if((int)$espece["prix"]==(int)$especeCh["prix"]){
                    $staut="avarié_changement";
                    // $cmd["statut"]="avarié_changement";
                 

                }
                else if((int)$espece["prix"]>(int)$especeCh["prix"]){
                    if($ribclient!="")
                    $remb->rib_client=$ribclient;
                    if($nomclient !="")  
                    $remb->nom_prenom_client=$nomclient;
                    $montant_de_remboursement=(int)$espece["prix"]-(int)$especeCh["prix"];
                    // $remb->montant_de_remboursement=(int)$espece["prix"]-(int)$especeCh["prix"];
                    $cmd["details_remboursement" ]=$remb;
               
                    // $cmd["statut"]="avarié_changement"; // var  
                    $staut="avarié_changement";
                    

                }
          }
}
    $cmd->especes=$especes;
    $cmd->save();
    // $cmd = Commande::find($id);
    // return $cmd;

    return response()->json([ "statut" => $staut,"montant_de_remboursement" => $montant_de_remboursement, "complement" => $complement]);
});



// avarié_changement (montant_de_remboursement)/ en attente de paiement du complement (complement) 
// /avarié_remboursement(montant_de_remboursement) /avarié_annulée 

// avarié_changement_remboursement
// total=sum(montant_de_remboursement)-sum(complement) ; 
// si rotal >00
// montant_de_remboursement (ANOC)=total
// else
// $cmd["statut"]="en attente de paiement du complement";
// complement=total;




//remboursement

Route::put('commande/{id}/{idespece}', function (Request $request,$id,$idespece) {
    $ribclient=$request->input('rib');
    $nomclient=$request->input('nom');
    $staut="";
    $montant_de_remboursement=0;
    $complement=0;
    $remb=json_decode('{}');
    $cmd = Commande::find($id);
    $espece = Espece::find($idespece);
    // $especeCh = Espece::find($idpc);
    // $cons = Consommateur::where('id_user', '=', $cmd["id_consommateur"])->first();
    // $elev = Eleveur::find($cmd["especes"][0]["id_eleveur"]);
    // $cooperative=Cooperative::find($cmd["id_cooperative"]);
    $especes=[];
    foreach ($cmd["especes"] as $cmd_item) {
    
        if($cmd_item["id_espece"]==$idespece){
            $especesChangement=[];
            $cmd_item["choix_client"]="remboursement";

                foreach ($cmd_item["produits_changement"] as $pc) {
                    // $es = Espece::find($pc['id_espece']);
                   
                    

                        $pc["feedback"]="refusé";


                  

                    array_push($especesChangement, $pc);
                }
                $cmd_item["produits_changement"]=$especesChangement;


          }
          array_push($especes, $cmd_item);

        
          if( $cmd["ancien_statut"]=="en attente de paiement du reste"){                                               
                   if($ribclient !="")                                                                                                         
            $remb->rib_client=$ribclient;
            if($nomclient !="") 
            $remb->nom_prenom_client=$nomclient;
            $montant_de_remboursement=(int)$espece["avance"];
            // $remb->montant_de_remboursement=(int)$espece["avance"];
            $cmd["details_remboursement" ]=$remb;
            
            
            // $cmd["statut"]="avarié_remboursement";
            $staut="avarié_remboursement";
           


          }

          else if( $cmd["ancien_statut"]=="validé"){                                               
                    if($ribclient !="")                                                                                                        
            $remb->rib_client=$ribclient;
            if($nomclient !="") 
            $remb->nom_prenom_client=$nomclient;
            // $remb->montant_de_remboursement=(int)$espece["prix"];
            $montant_de_remboursement=(int)$espece["prix"];
            $cmd["details_remboursement" ]=$remb;
            // $cmd["statut"]="avarié_remboursement";
            $staut=="avarié_remboursement";


          }
          else if( $cmd["ancien_statut"]!="validé" && $cmd["ancien_statut"]!="en attente de paiement du reste"){                                               
                                                                                                                            
       
            // $cmd["statut"]="avarié_annulée";
            $staut="avarié_annulée";


          }


        }

    $cmd->especes=$especes;
    $cmd->save();
    // $cmd = Commande::find($id);
    // return $cmd;
    return response()->json([ "statut" => $staut,"montant_de_remboursement" => $montant_de_remboursement, "complement" => $complement]);

});


Route::put('commandeStatut/{id}', function (Request $request,$id) {
    // $staut="";
    // $montant_de_remboursement=0;
    $cmd = Commande::find($id);
    $remb=json_decode('{}');
    $montant_de_remboursement=0;
    $complement=0;
    $changement=false;
    $rembouresement=false;
    $feedbacks=$request->all();
    foreach ($feedbacks as $feedback) {

        // $complement=$feedback;
        $complement+=$feedback["complement"];
        $montant_de_remboursement+=$feedback["montant_de_remboursement"];
        if($feedback["statut"]=="avarié_changement" or  $feedback["statut"]=="en attente de paiement du complement") $changement=true;
        else if ($feedback["statut"]=="avarié_remboursement" or  $feedback["statut"]=="avarié_annulée") $rembouresement=true;

        
        }

         $total=$complement-$montant_de_remboursement;
         if($total>0){
            // id_cooperative
                        
            $cmd->complement=$total;
            $cmd->statut="en attente de paiement du complement";
            $coop=Cooperative::find($cmd["id_cooperative"]);
            $par=$coop->parametres['delais_paiement']['delai_complement'];
            $dt=date("d-m-Y");
            $deadline=date( "d/m/Y", strtotime( "$dt +$par hours" ) )." à 16:00";
            $cmd->deadline=$deadline;


        }
         else if($total==0 && !$rembouresement && $changement) 
         $cmd->statut="avarié_changement";
         else if($total==0 && !$changement && $rembouresement) 
         $cmd->statut="avarié_annulée";

         else if($total<0)
         { if($changement && $rembouresement)  $cmd->statut="avarié_changement_remboursement";
            else  $cmd->statut="avarié_changement";
       
            
            $remb->rib_client=$cmd["details_remboursement" ]["rib_client"];
            $remb->nom_prenom_client=$cmd["details_remboursement" ]["nom_prenom_client"];
            $remb->montant_de_remboursement=-$total;
          
            $cmd["details_remboursement" ]=$remb;

         }

    $cmd->save();

    $cmd = Commande::find($id);
    return $cmd;
 

});


Route::get('panier/{id}/{id_cooperatvie}', function ($id,$id_cooperative) {
    $total=0;
    $cons = Consommateur::where('id_user', '=',  $id)->first();
    $especes=[];

    $panier_Especes = $cons->panier;
    foreach ($panier_Especes as $panier_Especes_item) {
        $espece =DB::table('espece')->find($panier_Especes_item["id_espece"]);
        // $espece = Espece::find($panier_Especes_item["id_espece"]);
        if($espece['id_cooperative']==$id_cooperative &&  $espece['statut']=="disponible"){
        $total+=$espece['prix'];
        array_push($especes, $espece);
    } 

     
    }
//  return $espece
    return response()->json([ "total" => $total,"especes" => $especes]);
});


//Routes Animateur

Route::get('animateur/{id}', function ($id) {
    $animateur=Animateur::where('id_user', '=',  $id)->orWhere('_id','=',  $id)->first();
    return  $animateur;
});

Route::post('animateur', function (Request $request) {
    $anim = Animateur::create($request->all());
    return response()->json(["objet" => $anim, "msg" => "Ajouté avec succès"]);
});

Route::get('secteurs', function (Request $request) {
    $output = [];
    $anim = Animateur::all();
    foreach($anim as $anim_item){
        $temp = [];
        foreach($anim_item['cooperatives'] as $coop){
            $coopTemp=Cooperative::find($coop['id_cooperative']);
            if($coopTemp!=null)
            array_push($temp,array("nom" => $coopTemp['nom'], "id" => $coopTemp['_id']));
        }
        array_push($output, array("secteur" => $anim_item["parametrage_global"]["secteur"]["nomRegion"],
                    "cooperatives" => $temp));
    }
    return $output;
});

Route::get('contacts', function () {
    $out = [];
    $temp = [];
    $animateur=Animateur::all();
        
    foreach ($animateur as $anim) {
        $nom = $anim['parametrage_global']['secteur']['nomRegion'];
        $service = $anim['parametrage_global']['secteur']['service'];

        $temp['nom'] = $nom;
        $temp['service'] =$service;

        array_push($out, $temp);
    }
    return  $out;
});

Route::get('consommateurs/emails', function () {
    $consomateurs= Consommateur::all();
    $emails = [];
    foreach ($consomateurs as $cons) {
       $email=$cons['email'];

        array_push($emails, $email);
    }
   return $emails ;

});

Route::post('mailConsommateurs/{id_min}/{id_max}', function ($id_min,$id_max) {
   
    $consData= Consommateur::select(['email'])->get();

    $consomateurs = [];
      $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
    foreach ($consData as $cons) {
       $email=$cons['email'];
       if(preg_match($regex, $email) &&  (!in_array($email, $consomateurs)))
        array_push($consomateurs, $email);
    }

   return $consomateurs;

    //    $content="Bonjour\nSuite à la hausse croissante du nombre de transactions sur l'application My ANOC,  la plateforme est actuellement en cours de mise à niveau, afin de vous délivrer un niveau de réponse conforme à vos attentes, incluant de nouveaux produits et services additionnels. \nNous mettons tout en œuvre afin de rétablir le service dans les plus brefs délais. Nous vous tiendrons informé, très rapidement, de la mise à disposition de la nouvelle version de l'application mobile. \n\nNous vous remercions pour votre compréhension et votre patience. \n
    //    صباح الخير، بعد الزيادة المتواصلة في عدد المعاملات على التطبيق  ، يتم حاليًا تحديث التطبيق ، من أجل تقديم مستوى استجابة يلبي توقعاتكم ، بما في ذلك المنتجات والخدمات الإضافية الجديدة.\nنحن نبذل قصارى جهدنا لاستعادة الخدمة في أسرع وقت ممكن. سنبقيكم على اطلاع  فور اصدار النسخة الجديدة نشكركم على تفهمكم وصبركم.\n\nANOC\nAssociation Nationale des Ovins et Caprins\nhttp://www.anoc.ma";
    //    $subject="My ANOC - Mise à niveau du service ";

    //   $emails = [];

    // $max=1;

    // if($id_max>count($consomateurs))
    // $max=count($consomateurs);
    // else $max=$id_max;
    // for($i = $id_min; $i < $max; ++$i) {
    //   if($consomateurs[$i] !=null ){
    //       $to=$consomateurs[$i];
         
    //     Mail::raw($content, function ($message) use ($to, $subject) {
    //             $message->from('myanoc.app@gmail.com', 'MY ANOC');
    //             $message->to($to)->subject($subject);
    //         });
    //         array_push($emails, $consomateurs[$i]);
    //       }
       
    // }

    // return response()->json(["msg"=>"Email envoyé à :" ,"Consomateurs" =>$emails]);
});