<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
    * @OA\Info(
    *     title="Marketplace APIs documentation",
    *     version="1.0.0"
    * )
    */

    // -------------------------------------------------//
    /**
     * Get Detail User
     * @OA\Get (
     *     path="/api/user/{id}",
     *     tags={"User"},
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
     *              @OA\Property(property="_id", type="string", example="65464441"),
     *              @OA\Property(property="email", type="string", example="a@a.com"),
     *              @OA\Property(property="role", type="string", example="consommateur"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *         )
     *     )
     * )
     */

      /**
     * Delete User
     * @OA\Delete (
     *     path="/api/user/{id}",
     *     tags={"User"},
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
     *             @OA\Property(property="message", type="string", example="delete User success")
     *         )
     *     )
     * )
     */

    //--------------------------------------------------------------------------------------------//

    /**
     * Get all Consumers
     * @OA\Get (
     *     path="/api/consommateurs",
     *     tags={"Consommateur"},
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
     *                         property="civilisation",
     *                         type="string",
     *                         example="example civilisation"
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
     *                     ),
     *                     @OA\Property(
     *                         property="adresse",
     *                         type="string",
     *                         example="example adresse"
     *                     ),
     *                     @OA\Property(
     *                         property="ville",
     *                         type="string",
     *                         example="example ville"
     *                     ),
     *                     @OA\Property(
     *                         property="pays",
     *                         type="string",
     *                         example="example pays"
     *                     ),
     *                     @OA\Property(
     *                         property="favoris",
     *                         type="string",
     *                         example="[]"
     *                     ),
     *                     @OA\Property(
     *                         property="panier",
     *                         type="string",
     *                         example="[]"
     *                     ),
     *                     @OA\Property(
     *                         property="id_user",
     *                         type="string",
     *                         example="66656565fg446fg464674f"
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

    /**
     * Get all Consumer by Id
     * @OA\Get (
     *     path="/api/consommateur/{id}",
     *     tags={"Consommateur"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         description="Insert the consumer Id here", 
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
     *                         example="000000"
     *                     ),
     *                     @OA\Property(
     *                         property="civilisation",
     *                         type="string",
     *                         example="example civilisation"
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
     *                     ),
     *                     @OA\Property(
     *                         property="adresse",
     *                         type="string",
     *                         example="example adresse"
     *                     ),
     *                     @OA\Property(
     *                         property="ville",
     *                         type="string",
     *                         example="example ville"
     *                     ),
     *                     @OA\Property(
     *                         property="pays",
     *                         type="string",
     *                         example="example pays"
     *                     ),
     *                     @OA\Property(
     *                         property="favoris",
     *                         type="string",
     *                         example="[]"
     *                     ),
     *                     @OA\Property(
     *                         property="panier",
     *                         type="string",
     *                         example="[]"
     *                     ),
     *                     @OA\Property(
     *                         property="id_user",
     *                         type="string",
     *                         example="66656565fg446fg464674f"
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
    
     /**
     * Get the chart of a Consumer by its Id
     * @OA\Get (
     *     path="/api/consommateur/{id}/panier",
     *     tags={"Consommateur"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         description="Insert the consumer Id here to get the chart", 
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
     *                         property="panier",
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
    
    /**
     * Get the Grouped chart of a Consumer by its Id
     * @OA\Get (
     *     path="/api/consommateur/{id}/panierGroupes",
     *     tags={"Consommateur"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         description="Insert the consumer Id here to get the grouped chart", 
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
     *                         property="panier",
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
    /**
     * Get the favorites of a Consumer by its Id
     * @OA\Get (
     *     path="/api/consommateur/{id}/favoris",
     *     tags={"Consommateur"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         description="Insert the consumer Id here to get the grouped chart", 
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
     *                         property="panier",
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

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
