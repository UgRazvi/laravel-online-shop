<?php

namespace App\Http\Controllers\admin;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // If Category Id is Selected Then Show Sub Categories Respective To It.
        if (!empty($request->category_id)) {
            $subCategories = SubCategory::where('category_id', $request->category_id)->orderBy('name', 'asc')->get();
            return response()->json([
                'status' => true,
                'SubCategories' => $subCategories
            ]);
        } else {
            return response()->json([
                'status' => false,
                'SubCategories' => [] // Empty Array
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
