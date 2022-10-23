<?php

namespace App\Http\Controllers;

use App\Http\Resources\ZipCodeResource;
use App\Models\ZipCode;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ZipCodesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
