<?php

namespace App\Http\Controllers;

use App\Models\campeonatos;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ApiControllerCampeonatos extends Controller
{
    public function getAll()
    {
        $campeonatos = campeonatos::get()->toJson(JSON_PRETTY_PRINT);
        return response($campeonatos, 200);
    }
    public function get($id)
    {
        if (campeonatos::where('id', $id)->exists()) {
            $campeonato = campeonatos::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($campeonato, 200);
        } else {
            return response()->json([
                "message" => "campeonato not found"
            ], 404);
        }
    }

    public function getAllatu(){
        $campeonatos = campeonatos::where('atu', 1)->get()->toJson(JSON_PRETTY_PRINT);
        return response($campeonatos, 200);
    }

    public function create(Request $request)
    {
        if (campeonatos::where('link', $request->link)->exists()) {
            return response()->json([
                "message" => "campeonato exists"
            ], 200);
        } else {
            $client = new Client();
            $crawler = $client->request('GET', $request->link);
            $campeonato = new campeonatos;
            $campeonato->nome = $crawler->filter('h1')->text();
            $campeonato->link = $request->link;
            $campeonato->save();
            Artisan::call('command:resetCrawlerByID '. $campeonato->id);
            return response()->json([
                "message" => "campeonato record created",
            ], 201);
        }
    }
    public function update(Request $request, $id)
    {
        if (campeonatos::where('id', $id)->exists()) {
            $campeonato = campeonatos::firstWhere('id', $id);
            $campeonato->atu = is_null($request->atu) ? $campeonato->atu : $request->atu;
            $campeonato->save();

            return response()->json([
                "message" => "records updated successfully",
            ], 200);
        } else {
            return response()->json([
                "message" => "campeonato not found"
            ], 404);
        }
    }
    public function reset($id)
    {
        if (campeonatos::where('id', $id)->exists()) {
            Artisan::call('command:resetCrawlerByID '. $id);
            return response()->json([
                "message" => "reset successfully",
            ], 200);
        } else {
            return response()->json([
                "message" => "campeonato not found"
            ], 404);
        }
    }
    public function delete($id)
    {
        if (campeonatos::where('id', $id)->exists()) {
            $campeonato = campeonatos::firstWhere('id', $id);
            $campeonato->delete();

            return response()->json([
                "message" => "records deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "campeonato not found"
            ], 404);
        }
    }
}
