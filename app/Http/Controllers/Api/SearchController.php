<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    //

    public function search(Request $request)
    {
        $search = $request->query('search');

        $q = $request->query('q');
        $type = $request->query('type'); 
        $sort = $request->query('sort', 'score'); 
        $perPage = (int)$request->query('per_page', 10);

        $cacheKey = 'search:' .$q . '|' . $type . '|' . $sort . '|' . $perPage . '|' . $request->query('page', 1);

        $results = Cache::store('redis')->remember($cacheKey, now()->addMinutes(5), function () use ($q, $type, $sort, $perPage) {
            $query = Content::query();

            if ($type) {
                $query->where('type', $type);
            }

            if ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%$q%");
                });
            }

            if ($sort === 'score') {
                $query->orderByDesc('score');
            } elseif ($sort === 'relevance' && $q) {
                //todo: implement relevance
                $query->orderByDesc('score');
            }

            return $query->paginate($perPage);
        });
        return response()->json(['results' => $results]);
    }
}
