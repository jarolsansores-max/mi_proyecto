<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CarreraController extends Controller
{
    private function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la carrera es obligatorio.',
            'nombre.unique' => 'El nombre de la carrera ya está registrado.',
            'nombre.max' => 'El nombre de la carrera no puede tener más de 255 caracteres.',
        ];
    }

    public function index()
    {
        return response()->json(['carreras' => Carrera::orderBy('id_carrera', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique('carreras', 'nombre')],
        ], $this->messages());

        $carrera = Carrera::create($validated);

        return response()->json([
            'message' => 'Carrera creada exitosamente',
            'carrera' => $carrera,
        ], 201);
    }

    public function update(Request $request, Carrera $carrera)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('carreras', 'nombre')->ignore($carrera->id_carrera, 'id_carrera'),
            ],
        ], $this->messages());

        $carrera->update($validated);

        return response()->json([
            'message' => 'Carrera actualizada exitosamente',
            'carrera' => $carrera,
        ]);
    }

    public function destroy(Carrera $carrera)
    {
        $carrera->delete();

        return response()->json(['message' => 'Carrera eliminada exitosamente']);
    }
}
