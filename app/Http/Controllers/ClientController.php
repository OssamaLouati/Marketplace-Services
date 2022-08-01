<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Client;
use Exception;
use PhpParser\Node\Expr\FuncCall;

class ClientController extends Controller
{
    
    /**
     * Get all the client
     * @OA\Get (
     *     path="/api/clients",
     *     tags={"Client"},
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
     *                         property="CIN",
     *                         type="string",
     *                         example="example CIN"
     *                     ),
     *                     @OA\Property(
     *                         property="Nom",
     *                         type="string",
     *                         example="example content"
     *                     )
     *                     
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    
    public function index()
    {
        $clients = Client::all();
        return response()->json(['all clients' => $clients]);
    }
    
    /**
     * Get Detail Client
     * @OA\Get (
     *     path="/api/client/{id}",
     *     tags={"Client"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         description="Insert the client ID here", 
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="CIN", type="string", example="W450055"),
     *              @OA\Property(property="Nom", type="string", example="oussama"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *         )
     *     )
     * )
     */
    
    public function show($id)
    {
        $client = Client::find($id);
        return response()->json([$client]);
    }

    public function delete($id){
        try{
        $client = Client::find($id);
        $client->delete();
        return response()->json(["message" => "The client " . strval($id) . " has been deleted successfuly :)"]);
        }catch(Exception $e){
            return response()->json(["message" => "This client doesn't exist!!!"]);
        }
    }
    
    public function insert(Request $request){
        try{            
            // $client = Client::create($request->all());
            $client = new Client();
            $client->CIN = $request->input('CIN');
            $client->Nom = $request->input('Nom');
            $client->save();
            return response()->json(['New client' => $client, "message"=>"Client added successfuly :)"]);
        }catch(Exception $e){
            return response()->json(["message" => "Error occured when adding a new client :( Maybe the client exists already!!"]);
        }
    }



    /**
     * Delete Client
     * @OA\Delete (
     *     path="/api/client/{id}",
     *     tags={"Client"},
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
     *             @OA\Property(property="message", type="string", example="delete client success")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id){
        try
        {
            $client = Client::find($id);
            $client->CIN = $request->input('CIN') ;
            $client->Nom = $request->input('Nom') ;
            $client->save() ;
            return response()->json(["Updated Client" => $client, "message"=> " Client Updated successffuly :)"]);
        }
        catch(Exception $e)
        {
            return response()->json(["message"=> " Error while updating"]);
        };
    }

   

}
