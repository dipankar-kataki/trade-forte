<?php

namespace App\Http\Controllers\HsnCode;

use App\Http\Controllers\Controller;
use App\Models\HsnTable;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HsnController extends Controller
{
    use ApiResponse;
    public function list(Request $request)
    {
        try {
            $searchTerm = $request->hsn;
            $length = strlen($searchTerm);

            // Validate the length of the HSN code in the request
            if ($length % 2 == 0 && $length >= 2 && $length <= 8) {
                $searchResults = HsnTable::where('hsn_code', 'LIKE', $searchTerm . '%')
                    ->whereRaw('CHAR_LENGTH(hsn_code) IN (?, ?, ?)', [$length, $length + 2, $length + 4])
                    ->orderBy('hsn_code')
                    ->limit(8)
                    ->get();

                return $this->success("Hsn List.", $searchResults, null, 200);
            } else {
                return $this->error('Invalid HSN code length in the request.', null, null, 400);
            }
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }


}
