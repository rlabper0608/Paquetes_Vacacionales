<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VacacionCreateRequest extends FormRequest  {
    function attributes(): array {
        return [
            'titulo'        => 'titulo del paquete vacacional',
            'descripcion'   => 'descripcion del paquete vacacional',
            'precio'        => 'precio del paquete vacacional',
            'idtipo'        => 'tipo del paquete vacacional',
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
            // Reglas de idcopia
            'titulo.required'       => $required,
            'titulo.unique'         => "El título debe ser único",
            'titulo.string'         => $string,
            'titulo.min'            => $min,
            'titulo.max'            => $max,
            
            // Reglas de descripcion
            'descripcion.required'  => $required,
            'descripcion.min'       => $min,
            
            // Reglas de precio
            'precio.required'       => $required,
            'precio.integer'        => 'El precio debe ser un valor numérico.',
            'precio.min'            => $min,
            'precio.max'            => $max,

            // Reglas de idtipo
            'idtipo.required'       => $required,
            'idtipo.exists'         => 'El tipo de paquete no es válido.',
        ];
    }

    public function rules(): array {
        return [
            'titulo'        => 'required|unique|string|min:1|max:60',
            'descripcion'   => 'required|min:20',
            'precio'        => 'required|integer|min:1|max:9999',
            'idtipo'        => 'required|exists:tipo,id',
        ];
    }
}
