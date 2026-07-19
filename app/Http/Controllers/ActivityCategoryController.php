<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityCategory;


class ActivityCategoryController extends Controller
{
     public function index()
    {
        $categories = ActivityCategory::where('status', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
