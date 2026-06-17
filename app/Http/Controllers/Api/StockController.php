<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $limit = min((int) $request->query('limit', 500), 500);

        $query = Stock::query();

        if ($request->query('dateFrom')) {
            $query->where('date', '>=', $request->query('dateFrom'));
        }

        return response()->json($query->paginate($limit));
    }
}
