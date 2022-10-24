<?php

namespace App\Http\Controllers;

use App\Http\Resources\ZipCodeResource;
use App\Models\ZipCode;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @OA\Info(title="Zip Codes API", version="1.0")
 */
class ZipCodesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/zip-codes/{id}",
     *     summary="Get Zip Code By Id",
     *     tags={"Zip Code"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Zip code id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              minLength=5,
     *              minLength=5
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Show zip code",
     *         @OA\JsonContent(ref="#components/schemas/ZipCodeResource")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Server Error"
     *     )
     * )
     *
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Validate error route param with 500 as expeted example.
        $isValidParam = preg_match('/^\d{5}$/', $id);
        if (! $isValidParam) {
            return abort(500);
        }

        $zipCodeModels = ZipCode::where('id', $id)->with([
            'federalEntity',
            'settlements.settlementType',
            'municipality',
        ])->get()->first();

        if (is_null($zipCodeModels)) {
            throw new NotFoundHttpException();
        }

        return new ZipCodeResource($zipCodeModels);
    }
}
