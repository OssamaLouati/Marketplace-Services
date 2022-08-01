<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Cooperative;
use Validator;


class CooperativeController extends Controller
{

    public function search(Request $request)
    {
        $nom = $request->input('nom');
        $id_animateur = $request->input('id_animateur');
        $order_by= $request->input('order_by');  //specify in the request body the attribute to use for sorting
        $order_mode= $request->input('order_mode'); //specify in the request body the mode of sorting : asc or desc
        $region=$request->input('region');
        $ville=$request->input('province');
       $coop = Cooperative::select([
            'nom','description','rib','region','ville','techniciens', 'id_animateur'
        ])->when($id_animateur, function ($query, $id_animateur) {return $query->where('id_animateur', $id_animateur); })
         ->when($nom, function ($query, $nom) {return $query->where('nom', $nom); })    
         ->when($region, function ($query, $region) {return $query->where('region', $region); })  
         ->when($ville, function ($query, $ville) {return $query->where('ville', $ville); })      
         
         ->orderBy($order_by, $order_mode)
         -> get();
           $cooperatives=[];
         if ($request->has('nom_cooperative')) {
           
            $cooperative_chercher = strtolower($request->input('nom_cooperative'));
            $splitName = explode(' ', $cooperative_chercher);
            foreach ($coop as $c) {
            $coop_nom=explode(' ', strtolower($c["nom"]));
                   $found=count(array_intersect($coop_nom,$splitName)) >0 && count($splitName) === count(array_intersect($coop_nom,$splitName)) ;

               if($found==true )
                        {   array_push($cooperatives, $c);}
            }
            $coop = array_unique($cooperatives);
        }
        return $coop;
    }

}
