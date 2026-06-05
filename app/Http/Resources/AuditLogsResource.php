<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'entidad'    => str_replace('App\\Models\\', '', $this->entity), // Limpia el nombre
            'id_registro'=> $this->entity_id,
            'accion'     => $this->action,
            'antes'      => $this->old_values,
            'despues'    => $this->new_values,
            //'hecho_por'  => $this->user_id ?? 'Sistema/Usuario',
            'hecho_por'   => $this->user ? $this->user->name : 'Sistema/Auto',
            'ip'         => $this->ip_address,
            'fecha'      => $this->created_at->format('d/m/Y H:i:s'),
        ];
    }
}
