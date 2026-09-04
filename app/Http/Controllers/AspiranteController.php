<?php

namespace App\Http\Controllers;

use App\Models\Aspirante;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AspiranteController extends Controller
{
    private function messages(): array
    {
        return [
            'folio.required' => 'El folio es obligatorio.',
            'folio.unique' => 'El folio ya está registrado.',
            'curp.required' => 'La CURP es obligatoria.',
            'curp.size' => 'La CURP debe tener exactamente 18 caracteres.',
            'curp.regex' => 'La CURP solo puede contener letras y números.',
            'curp.unique' => 'La CURP ya está registrada.',
            'nombre.required' => 'El nombre es obligatorio.',
            'ap_paterno.required' => 'El apellido paterno es obligatorio.',
            'id_carrera.required' => 'Debe seleccionar una carrera.',
            'id_carrera.exists' => 'La carrera seleccionada no es válida.',
            'estatus.in' => 'El estatus seleccionado no es válido.',
        ];
    }

    private function rules(?Aspirante $aspirante = null): array
    {
        $folioRule = $aspirante
            ? [Rule::unique('aspirantes', 'folio')->ignore($aspirante->id_aspirantes, 'id_aspirantes')]
            : [Rule::unique('aspirantes', 'folio')];

        $curpRule = $aspirante
            ? [Rule::unique('aspirantes', 'curp')->ignore($aspirante->id_aspirantes, 'id_aspirantes')]
            : [Rule::unique('aspirantes', 'curp')];

        return [
            'folio' => array_merge(['required', 'string', 'max:255'], $folioRule),
            'curp' => array_merge(['required', 'string', 'size:18', 'regex:/^[A-Z0-9]{18}$/i'], $curpRule),
            'nombre' => ['required', 'string', 'max:255'],
            'ap_paterno' => ['required', 'string', 'max:255'],
            'ap_materno' => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'id_carrera' => ['required', 'exists:carreras,id_carrera'],
            'promedio_bachillerato' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'estatus' => ['required', 'string', 'in:Pendiente,Aceptado,Rechazado'],
            'documentos_completos' => ['required', 'integer', 'min:0', 'max:1'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function index()
    {
        return response()->json(['aspirantes' => Aspirante::with('carrera')->orderBy('id_aspirantes', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        $validated['curp'] = strtoupper($validated['curp']);

        $aspirante = Aspirante::create($validated);

        return response()->json([
            'message' => 'Aspirante creado exitosamente',
            'aspirante' => $aspirante->fresh('carrera'),
        ], 201);
    }

    public function update(Request $request, Aspirante $aspirante)
    {
        $validated = $request->validate($this->rules($aspirante), $this->messages());
        $validated['curp'] = strtoupper($validated['curp']);

        $aspirante->update($validated);

        return response()->json([
            'message' => 'Aspirante actualizado exitosamente',
            'aspirante' => $aspirante->fresh('carrera'),
        ]);
    }

    public function destroy(Aspirante $aspirante)
    {
        $aspirante->delete();

        return response()->json(['message' => 'Aspirante eliminado exitosamente']);
    }
}
