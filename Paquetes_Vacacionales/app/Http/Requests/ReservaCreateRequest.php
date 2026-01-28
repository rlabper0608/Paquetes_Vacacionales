<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservaCreateRequest extends FormRequest {
    function attributes(): array {
        return [
            'idvacacion'        => 'id del paquete vacacional',
            'iduser'            => 'id del usuario',
            'fecha_reserva'     => 'fecha de la reserva'
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

            // Reglas de fecha_reserva
            'fecha_reserva.required'             => $required,
            'fecha_reserva.date'                => 'La fecha tiene que ser del tipo fecha',
        ];
    }

    public function rules(): array {
        return [
            'idvacacion'        => 'required|exists:vacacion,id',
            'fecha_reserva'     => 'required|date',
        ];
    }
}
