<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLogs;

class GenericObserver
{
    //
    public function created(Model $model)
    {
        $this->log($model, 'created', null, $model->toArray());
    }

    public function updated(Model $model)
    {
        $changes = $model->getChanges();
        
        // No auditamos la fecha de actualización por sí sola
        unset($changes['updated_at']);

        if (count($changes) > 0) {
            // Obtenemos los valores originales solo de lo que cambió
            $oldValues = array_intersect_key($model->getOriginal(), $changes);
            $this->log($model, 'updated', $oldValues, $changes);
        }
    }

    public function deleted(Model $model)
    {
        $this->log($model, 'deleted', $model->toArray(), null);
    }

    protected function log(Model $model, string $action, ?array $old, ?array $new)
    {
        // Evitar que el log se audite a sí mismo (bucle infinito)
        if ($model instanceof AuditLogs) return;

        AuditLogs::create([
            'entity'     => get_class($model),
            'entity_id'  => $model->getKey(),
            'action'     => $action,
            'old_values' => $this->filterFields($old),
            'new_values' => $this->filterFields($new),
            'user_id'    => Auth::id() ?? ($action === 'updated' && method_exists($model, 'id') ? $model->id : null),
            'ip_address' => request()->ip(),
        ]);
    }

    protected function filterFields(?array $data): ?array
    {
        if (!$data) return null;
        // Excluimos campos sensibles de todos los modelos
        $exclude = ['password', 'id_token', 'remember_token'];
        return array_diff_key($data, array_flip($exclude));
    }
}
