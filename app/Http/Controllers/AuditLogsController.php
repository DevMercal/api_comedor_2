<?php

namespace App\Http\Controllers;

use App\Models\AuditLogs;
use App\Http\Requests\StoreAuditLogsRequest;
use App\Http\Requests\UpdateAuditLogsRequest;
use App\Http\Resources\AuditLogsResource;
use Illuminate\Http\Request;

class AuditLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $logs = AuditLogs::with('user')
            ->when($request->entity, function ($query) use ($request) {
                // Permite buscar por "User", "DocLegal", etc.
                return $query->where('entity', 'LIKE', '%' . $request->entity . '%');
            })
            ->latest()
            ->paginate(30);

        return AuditLogsResource::collection($logs);
    }

    public function show($id)
    {
        $log = AuditLogs::findOrFail($id);
        return new AuditLogsResource($log);
    }
}
