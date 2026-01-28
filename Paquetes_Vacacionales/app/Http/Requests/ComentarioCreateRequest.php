<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComentarioCreateRequest extends FormRequest {
    function attributes(): array {
        return [
            'idvacacion'        => 'id del paquete vacacional',
            'iduser'            => 'id del usuario',
            'comentario'        => 'comentario'
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
            // Reglas de idvacacion
            'idvacacion.required'       => $required,
            'idvacacion.exists'         => 'El paquete vacacional no es válido.',

            // Reglas de comentario
            'comentariorequired'             => $required,
            'comentario.min'                => $min,
        ];
    }

    public function rules(): array {
        return [
            'idvacacion'        => 'required|exists:vacacion,id',
            'comentario'        => 'required|min:10',
        ];
    }
}
