<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipoCreateRequest extends FormRequest {
    function attributes(): array {
        return [
            'nombre'    => 'Nombre del tipo',
        ];
    }

    function authorize(): bool {
        return true;
    }

    function messages(): array {
        // Variables para mensajes repetitivos
        $required = 'Es obligatorio introducir :attribute.';
        $min = 'La longitud mínima del campo :attribute es :min.';
        $max = 'La longitud máxima del campo :attribute es :max.';
        $string = "El campo debe ser un string.";

        return [
            // Reglas de nombre
            'nombre.required'       => $required,
            'nombre.unique'         => 'El nombre tiene que ser único',
            'nombre.string'         => $string,
            'nombre.min'            => $min,
            'nombre.max'            => $max,
        ];
    }

    public function rules(): array {
        return [
            'nombre'    => 'required|unique:tipo,nombre|string|min:1|max:60',
        ];
    }
}
