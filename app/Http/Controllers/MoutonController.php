<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Validator;
use App\Mouton;

class MoutonController extends Controller
{

    public function search(Request $request)
    {
        $boucle = $request->input('boucle');
        $race = $request->input('race');
        $poids = $request->input('poids');
        $localisation = $request->input('localisation');
        $id_eleveur = $request->input('id_eleveur');
        $statut = $request->input('statut');
        $age = $request->input('age');
        $prix_min = $request->input('prix_min');
        $prix_max = $request->input('prix_max');
        $poids_min = $request->input('poids_min');
        $poids_max = $request->input('poids_max');
        $order_by= $request->input('order_by');  //specify in the request body the attribute to use for sorting
        $order_mode= $request->input('order_mode'); //specify in the request body the mode of sorting : asc or desc

       $sheeps = Mouton::select([
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
        ])->when($boucle, function ($query, $boucle) {return $query->where('boucle', $boucle); })
         ->when($race, function ($query, $race) {return $query->where('race', $race); })
         ->when($statut, function ($query, $statut) {return $query->where('statut', $statut); })
         ->when($age, function ($query, $age) {return $query->where('age', $age); })
         ->when($id_eleveur, function ($query, $id_eleveur) {return $query->where('id_eleveur', $id_eleveur); })
         ->when($poids_min, function ($query, $poids_min) {return $query->where('poids', '>=',$poids_min); })
         ->when($poids_max, function ($query, $poids_max) {return $query->where('poids', '<=',$poids_max); })
         ->when($localisation, function ($query, $localisation) {return $query->where('localisation', $localisation); })
         //->when($prix, function ($query, $prix) {return $query->where('prix', $prix); })
         ->when($prix_min, function ($query, $prix_min) {return $query->where('prix', '>=',$prix_min); })
         ->when($prix_max, function ($query, $prix_max) {return $query->where('prix', '<=',$prix_max); })
         ->orderBy($order_by, $order_mode)
         -> get();

        return $sheeps;

    }

    function getVideo(Request $request) {
        $boucle = $request->input('boucle');
        $filename = $request->input('filename');
        $video_path= public_path().'/uploads/moutons/'.$boucle.'/video/'.$filename;
        //$video = Storage::disk('local')->get($video_path);
        //$response = Response::make($video, 200);
        //$response->header('Content-Type', 'video/mp4');
        return response()->download($video_path);
    }

    function getImage(Request $request) {
        $boucle = $request->input('boucle');
        $filename = $request->input('filename');
        $img_path= public_path().'/uploads/moutons/'.$boucle.'/images/'.$filename;
        //$video = Storage::disk('local')->get($video_path);
        //$response = Response::make($video, 200);
        //$response->header('Content-Type', 'image/png');
        return response()->file($img_path);
    }

    /*public function createMouton(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'boucle' => 'required|unique:App\Mouton,boucle',
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
                    $path = public_path().'/uploads/moutons/'.$boucle.'/images/';
                    error_log($path);
                    $image->move($path,$name);
                    //move_uploaded_file ($image , $path);
                    //$path_complet=strval($path)+"/"+strval($name);
                    array_push($input['images'],'/uploads/moutons/'.$boucle.'/images/'.$name);
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
            $path = public_path().'/uploads/moutons/'.$boucle.'/video/';
            error_log($path);
            $video->move($path,$name);
            //move_uploaded_file ($image , $path);
            //$path_complet=strval($path)+"/"+strval($name);
            $input['video']='/uploads/moutons/'.$boucle.'/video/'.$name;
        }

        $shp=Mouton::create($input);
        return response()->json(["objet" => $shp,"msg" =>"Ajouté avec succès"]);
    }*/

}
