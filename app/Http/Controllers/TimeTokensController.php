<?php

namespace App\Http\Controllers;

use App\Models\timeTokens;
use App\Http\Requests\StoretimeTokensRequest;
use App\Http\Requests\UpdatetimeTokensRequest;

class TimeTokensController extends Controller
{
    public function index()
    {
        $timeToken  = timeTokens::all();
        if ($timeToken->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron registros.'
            ], 200);
        }else {
            return response()->json([
                'timeToken' => $timeToken
            ], 200);
        }
    }
    public function store(StoretimeTokensRequest $request)
    {
        //
    }
}
