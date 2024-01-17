<?php

namespace App\Http\Controllers\HsnCode;

use App\Http\Controllers\Controller;
use App\Models\HsnTable;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class HsnController extends Controller
{
    use ApiResponse;
    public function list(Request $request)
    {
        $searchTerm = $request->hsn.;
        var_dump($searchTerm);
        $searchResults = HsnTable::selectRaw(
            "*, MATCH(hsn_code, hsn_description) AGAINST(? IN BOOLEAN MODE) as relevance",
            [$searchTerm]
        )
            ->whereRaw("MATCH(hsn_code, hsn_description) AGAINST(? IN BOOLEAN MODE)", [$searchTerm])
            ->orderByDesc('relevance')
            ->limit(5)
            ->get();

        return $this->success("Hsn List.", $searchResults, null, 200);
    }
}
