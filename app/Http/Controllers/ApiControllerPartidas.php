<?php

namespace App\Http\Controllers;

use App\Models\partidas;
use Illuminate\Http\Request;

class ApiControllerPartidas extends Controller
{
    public function getAll()
    {
        $partidas = partidas::where(
            [
                ['data', '=', date('d-m-Y', strtotime('+1 days', strtotime(date("d.m.y"))))]
            ]
        )->orwhere(
            [
                ['data', '=', date('d-m-Y')]
            ]
        )->get()->toJson(JSON_PRETTY_PRINT);
        return response($partidas, 200);
    }

    public function get($token)
    {
        if (partidas::where('token', $token)->exists()) {
            $partida = partidas::where('token', $token)->get()->toJson(JSON_PRETTY_PRINT);
            return response($partida, 200);
        } else {
            return response()->json([
                "message" => "partida not found"
            ], 404);
        }
    }
    public function getByCampeonato($id)
    {
        if (partidas::where('campeonato_id', $id)->exists()) {
            $partidas = partidas::where([
                ['campeonato_id', '=', $id],
                ['data', '=', date('d-m-Y', strtotime('+1 days', strtotime(date("d.m.y"))))]
            ])->orwhere(
                [
                    ['campeonato_id', '=', $id],
                    ['data', '=', date('d-m-Y')]
                ]
            )->get()->toJson(JSON_PRETTY_PRINT);
            return response($partidas, 200);
        } else {
            return response()->json([
                "message" => "campeonato id not found"
            ], 404);
        }
    }
    public function create(Request $request)
    {
        if (partidas::where('token', md5($request->link))->exists()) {
            return response()->json([
                "message" => "partida exists"
            ], 200);
        } else {
            $partida = new partidas;
            $partida->casa = $request->casa;
            $partida->fora = $request->fora;
            $partida->status_jogo = $request->status_jogo;
            $partida->link = $request->link;
            $partida->hora_inicio = $request->hora_inicio;
            $partida->token = md5($request->link);
            $partida->save();

            return response()->json([
                "message" => "partida record created"
            ], 201);
        }
    }
    public function update(Request $request, $token)
    {
        if (partidas::where('token', $token)->exists()) {
            $partida = partidas::firstWhere('token', $token);
            $partida->gols_casa = is_null($request->gols_casa) ? $partida->gols_casa : $request->gols_casa;
            $partida->gols_fora = is_null($request->gols_fora) ? $partida->gols_fora : $request->gols_fora;
            $partida->penaltis_casa = is_null($request->penaltis_casa) ? $partida->penaltis_casa : $request->penaltis_casa;
            $partida->penaltis_fora = is_null($request->penaltis_fora) ? $partida->penaltis_fora : $request->penaltis_fora;
            $partida->status_jogo = is_null($request->status_jogo) ? $partida->status_jogo : $request->status_jogo;
            $partida->intervalo = is_null($request->intervalo) ? $partida->intervalo : $request->intervalo;
            $partida->penalti = is_null($request->penalti) ? $partida->penalti : $request->penalti;
            $partida->prorrogacao = is_null($request->prorrogacao) ? $partida->prorrogacao : $request->prorrogacao;
            $partida->encerrado = is_null($request->encerrado) ? $partida->encerrado : $request->encerrado;
            $partida->save();

            return response()->json([
                "message" => "records updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "partida not found"
            ], 404);
        }
    }
    public function delete($token)
    {
        if (partidas::where('token', $token)->exists()) {
            $partida = partidas::firstWhere('token', $token);
            $partida->delete();

            return response()->json([
                "message" => "records deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "partida not found"
            ], 404);
        }
    }
}
