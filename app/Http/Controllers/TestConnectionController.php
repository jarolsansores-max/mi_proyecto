<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestConnectionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            DB::connection()->getPdo();
            $dbName = DB::connection()->getDatabaseName();
            
            return response()->json([
                'status' => 'success',
                'message' => "¡Conexión exitosa a la base de datos: $dbName!",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo conectar a la base de datos. Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
