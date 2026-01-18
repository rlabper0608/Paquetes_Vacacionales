<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FotoCreateRequest extends FormRequest {
    function attributes(): array {
        return [
            'idvacacion'    => 'id del paquete vacacional',
            'ruta'          => 'la ruta de la imagen',
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
            // Reglas de vacacion
            'idvacacion.required'       => $required,
            'idvacacion.exists'         => 'El paquete vacacional no es válido.',

            // Reglas de ruta
            'ruta.required'             => $required,
            'ruta.image'                => 'La ruta tiene que ser una imagen',
            'ruta.unique'               => $min,
        ];
    }

    public function rules(): array {
        return [
            'idvacacion'    => 'required|exists:vacacion,id',
            'ruta'          => 'required|image|unique',
        ];
    }
}
