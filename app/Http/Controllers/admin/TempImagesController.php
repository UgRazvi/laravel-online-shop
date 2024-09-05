<?php

namespace App\Http\Controllers\admin;

use App\Models\TempImage;
use Illuminate\Http\Request;
// use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Controllers\Controller;

class TempImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $image = $request->image; // Getting Temporary Image Stored By Request Method

        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension(); // Finding File Extension 
            $newName = time() . '.' . $ext; // Renaming - "Time.old_extension"

            $tempImage = new TempImage(); // Model Object
            $tempImage->name = $newName; // Assigning Name
            $tempImage->save(); // Saving Details

            $image->move(public_path() . '/tempImgs', $newName); // Moving the saved file to "Public/TempImgs" Folder


            return response()->json([
                'status' => 'true',
                'image_id' => $tempImage->id,
                'imagePath' => asset('/tempImgs/'. $newName),
                'message' => "Image has Been Uploaded Successfully."
            ]);
        }
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
