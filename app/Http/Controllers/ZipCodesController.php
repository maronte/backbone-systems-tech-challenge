<?php

namespace App\Http\Controllers;

use App\Models\ZipCode;
use Illuminate\Http\Request;

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
        $zipCodeModels = ZipCode::where("id", $id)->with([
            "federalEntity",
            "municipality",
            "settlements.settlementType"
        ])->get()->first();
        
        return $zipCodeModels;
    }
}
