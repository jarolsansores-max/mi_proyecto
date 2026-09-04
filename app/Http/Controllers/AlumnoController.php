<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Aspirante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AlumnoController extends Controller
{
    private function messages(): array
    {
        return [
            'matricula.required' => 'La matrícula es obligatoria.',
            'matricula.unique' => 'La matrícula ya está registrada.',
            'curp.required' => 'La CURP es obligatoria.',
            'curp.size' => 'La CURP debe tener exactamente 18 caracteres.',
            'curp.regex' => 'La CURP solo puede contener letras y números.',
            'curp.unique' => 'La CURP ya está registrada.',
            'nombre.required' => 'El nombre es obligatorio.',
            'ap_paterno.required' => 'El apellido paterno es obligatorio.',
            'id_carrera.required' => 'Debe seleccionar una carrera.',
            'id_carrera.exists' => 'La carrera seleccionada no es válida.',
            'estatus.required' => 'El estatus es obligatorio.',
        ];
    }

    private function rules(?Alumno $alumno = null): array
    {
        $matriculaRule = $alumno
            ? [Rule::unique('alumnos', 'matricula')->ignore($alumno->getKey(), $alumno->getKeyName())]
            : [Rule::unique('alumnos', 'matricula')];

        $curpRule = $alumno
            ? [Rule::unique('alumnos', 'curp')->ignore($alumno->getKey(), $alumno->getKeyName())]
            : [Rule::unique('alumnos', 'curp')];

        return [
            'matricula' => array_merge(['required', 'string', 'max:255'], $matriculaRule),
            'curp' => array_merge(['required', 'string', 'size:18', 'regex:/^[A-Z0-9]{18}$/i'], $curpRule),
            'nombre' => ['required', 'string', 'max:255'],
            'ap_paterno' => ['required', 'string', 'max:255'],
            'ap_materno' => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'id_carrera' => ['required', 'exists:carreras,id_carrera'],
            'estatus' => ['required', 'string', 'max:50'],
            'aspirante_id' => ['nullable', 'integer', 'exists:aspirantes,id_aspirantes'],
        ];
    }

    public function index()
    {
        $relations = ['carrera'];
        if (Schema::hasColumn('alumnos', 'id_aspirante')) {
            $relations[] = 'aspirante';
        }

        $alumnos = Alumno::with($relations)->orderBy('id', 'desc')->get();

        return response()->json([
            'alumnos' => $alumnos,
            'aspirantes' => Aspirante::with('carrera')->orderBy('id_aspirantes', 'desc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        $validated['curp'] = strtoupper($validated['curp']);

        $aspiranteId = $validated['aspirante_id'] ?? null;
        unset($validated['aspirante_id']);

        if ($aspiranteId && Schema::hasColumn('alumnos', 'id_aspirante')) {
            $validated['id_aspirante'] = $aspiranteId;
        }

        $alumno = Alumno::create($validated);

        if ($aspiranteId) {
            Aspirante::whereKey($aspiranteId)->update(['estatus' => 'Aceptado']);
        }

        return response()->json([
            'message' => 'Alumno registrado correctamente',
            'alumno' => $alumno->load(array_filter([
                'carrera',
                Schema::hasColumn('alumnos', 'id_aspirante') ? 'aspirante' : null,
            ])),
        ], 201);
    }

    public function update(Request $request, Alumno $alumno)
    {
        $validated = $request->validate($this->rules($alumno), $this->messages());
        $validated['curp'] = strtoupper($validated['curp']);
        unset($validated['aspirante_id']);

        $alumno->update($validated);

        return response()->json([
            'message' => 'Alumno actualizado correctamente',
            'alumno' => $alumno->load(array_filter([
                'carrera',
                Schema::hasColumn('alumnos', 'id_aspirante') ? 'aspirante' : null,
            ])),
        ]);
    }

    public function destroy(Alumno $alumno)
    {
        $alumno->delete();

        return response()->json(['message' => 'Alumno eliminado correctamente']);
    }
}
