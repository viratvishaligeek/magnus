<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageApiController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->tenant_id;
        if (! $tenantId) {
            return response()->json(['status' => 400, 'message' => 'Tenant Details is required'], 400);
        }
        $perPage = (int) ($request->per_page ?? 20);
        $page = (int) ($request->page ?? 1);

        $cacheKey = "pages_tenant_{$tenantId}_page_{$page}_per_{$perPage}";

        $data = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($tenantId, $perPage) {
            return Page::withoutGlobalScope('tenant_filter')
                ->where('tenant_id', $tenantId)
                ->where('status', 'active')
                ->with([
                    'seo',
                ])
                ->orderBy('updated_at', 'desc')
                ->paginate($perPage);
        });

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $data->items(),
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
            ],
        ], 200);
    }

    public function pageDetail(Request $request, string $slug)
    {
        $tenantId = $request->tenant_id;
        if (! $tenantId) {
            return response()->json(['status' => 400, 'message' => 'Tenant Details is required'], 400);
        }
        $cacheKey = "page_detail_tenant_{$tenantId}_slug_{$slug}";
        $page = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($tenantId, $slug) {
            return Page::withoutGlobalScope('tenant_filter')
                ->where('tenant_id', $tenantId)
                ->where('slug', $slug)
                ->where('status', 'active')
                ->with(['seo'])
                ->first();
        });
        if (! $page) {
            return response()->json(['status' => 404, 'message' => 'Page not found'], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $page,
        ], 200);

    }
}
