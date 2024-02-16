<?php

namespace App\Http\Controllers\HsnCode;

use App\Http\Controllers\Controller;
use App\Models\HsnTable;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HsnController extends Controller
{
    use ApiResponse;
    public function list(Request $request)
    {
        try {
            $searchTerm = $request->hsn;
            $length = strlen($searchTerm);
    
            if ($length == 1) {
                $query = "SELECT * FROM hsn_table WHERE hsn_code LIKE ? AND CHAR_LENGTH(hsn_code) = 2 ORDER BY hsn_code LIMIT 8";
                $searchResults = DB::select($query, [$searchTerm . '%']);
            } elseif ($length <= 4) {
                $query = "SELECT * FROM hsn_table WHERE hsn_code LIKE ? AND CHAR_LENGTH(hsn_code) = 4 ORDER BY hsn_code LIMIT 8";
                $searchResults = DB::select($query, [$searchTerm . '%']);
            } elseif ($length <= 6) {
                $query = "SELECT * FROM hsn_table WHERE hsn_code LIKE ? AND CHAR_LENGTH(hsn_code) = 6 ORDER BY hsn_code LIMIT 8";
                $searchResults = DB::select($query, [$searchTerm . '%']);
            } elseif ($length <= 8) {
                $query = "SELECT * FROM hsn_table WHERE hsn_code LIKE ? AND CHAR_LENGTH(hsn_code) = 8 ORDER BY hsn_code LIMIT 8";
                $searchResults = DB::select($query, [$searchTerm . '%']);
            } else {
                return $this->error('Invalid HSN code length in the request.', null, null, 400);
            }
    
            return $this->success("Hsn List.", $searchResults, null, 200);
        } catch (\Exception $e) {
            return $this->error('Oops! Something Went Wrong.' . $e->getMessage(), null, null, 500);
        }
    }
    


}
