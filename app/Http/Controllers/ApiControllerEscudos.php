<?php

namespace App\Http\Controllers;

use App\Models\escudos;
use Illuminate\Http\Request;

class ApiControllerEscudos extends Controller
{
    public function getAll()
    {
        $escudos = escudos::get()->toJson(JSON_PRETTY_PRINT);
        return response($escudos, 200);
    }

    public function get($id)
    {
        if (escudos::where('id', $id)->exists()) {
            $escudo = escudos::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($escudo, 200);
        } else {
            return response()->json([
                "message" => "escudo not found"
            ], 404);
        }
    }
    public function update(Request $request, $id)
    {
        if (escudos::where('id', $id)->exists()) {
            $escudo = escudos::find($id);
            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                $extension = $request->img->extension();
                if ($extension == "png") {
                    $bannerName = md5($request->img->getClientOriginalName()) . '-' . strtotime("now") . "." . $extension;
                    $request->img->move(public_path('img/escudos'), $bannerName);
                    $escudo->img = $bannerName;
                    $escudo->save();
                    return response()->json([
                        "message" => "records updated successfully"
                    ], 200);
                } else {
                    return response()->json([
                        "message" => "require png file"
                    ], 404);
                }
            } else {
                return response()->json([
                    "message" => "require file"
                ], 404);
            }
        } else {
            return response()->json([
                "message" => "escudo not found"
            ], 404);
        }
    }
}
