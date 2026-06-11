<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User; 
use Illuminate\Support\Facades\Hash; 

class NotUsedPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Usamos el usuario inyectado desde el Request
        $targetUser = $this->user;

        // Si por alguna razón no hay usuario, no podemos validar historial
        if (!$targetUser) {
            return;
        }

        // Buscamos en el historial del usuario editado
        $histories = $targetUser->passwordHistories()
            ->latest()
            ->take(5)
            ->get();

        foreach ($histories as $history) {
            // Comparamos el texto plano enviado por el Admin ($value) 
            // contra los hashes del historial ($history->password)
            if (Hash::check($value, $history->password)) {
                $fail('Esta contraseña ya ha sido utilizada por el usuario anteriormente.');
                return; // Detener validación al encontrar coincidencia
            }
        }
    }
}
