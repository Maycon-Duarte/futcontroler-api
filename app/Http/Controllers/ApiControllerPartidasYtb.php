<?php

namespace App\Http\Controllers;

use App\Models\ytbpartida;
use Illuminate\Http\Request;

class ApiControllerytbpartida extends Controller
{
    public function getAll()
    {
        $ytbpartida = ytbpartida::get()->toJson(JSON_PRETTY_PRINT);
        return response($ytbpartida, 200);
    }

    public function get($ytb_id)
    {
        if (ytbpartida::where('ytb_id', $ytb_id)->exists()) {
            $ytbpartida = ytbpartida::where('ytb_id', $ytb_id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($ytbpartida, 200);
        } else {
            return response()->json([
                "message" => "not found"
            ], 404);
        }
    }
    public function update(Request $request, $ytb_id)
    {
        if (ytbpartida::where('ytb_id', $ytb_id)->exists()) {
            $ytbpartida = ytbpartida::where('ytb_id', $ytb_id);
            $ytbpartida->partida_id = is_null($request->partida_id)? $ytbpartida->partida_id: $request->partida_id;
            $ytbpartida->tags = is_null($request->tags)? $ytbpartida->partida_id: $request->tags;
            $ytbpartida->radio = is_null($request->radio)? $ytbpartida->partida_id: $request->radio;
            $ytbpartida->obs = is_null($request->obs)? $ytbpartida->obs: $request->obs;
            $ytbpartida->data = is_null($request->data)? $ytbpartida->data: $request->data;
            $ytbpartida->hora_inicio = is_null($request->hora_inicio)? $ytbpartida->hora_inicio: $request->hora_inicio;
            $ytbpartida->save();
        } else {
            return response()->json([
                "message" => "not found"
            ], 404);
        }
    }
    public function create(Request $request)
    {
        if (ytbpartida::where('ytb_id', $request->ytb_id)->exists()) {
            return response()->json([
                "message" => "ytbpartida exists"
            ], 200);
        } else {
            $ytbpartida = new ytbpartida;
            $ytbpartida->usuario = $request->usuario;
            $ytbpartida->partida_id = $request->partida_id;
            $ytbpartida->tags = $request->tags;
            $ytbpartida->radio = $request->radio;
            $ytbpartida->obs = $request->obs;
            $ytbpartida->data = $request->data;
            $ytbpartida->hora_inicio = $request->hora_inicio;
            $ytbpartida->save();

            return response()->json([
                "message" => "ytbpartida record created",
            ], 201);
        }
    }
}
