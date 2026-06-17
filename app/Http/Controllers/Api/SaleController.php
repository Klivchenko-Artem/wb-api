<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $limit = min((int) $request->query('limit', 500), 500);

        $query = Sale::query();

        if ($request->query('dateFrom')) {
            $query->where('date', '>=', $request->query('dateFrom'));
        }

        if ($request->query('dateTo')) {
            $query->where('date', '<=', $request->query('dateTo'));
        }

        return response()->json($query->paginate($limit));
    }
}
