<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $categories = Category::latest();

        if (!empty($request->get("keyword"))) {
            $categories = $categories->where("name", "like", "%" . $request->get("keyword") . "%");
        }
        $categories = $categories->paginate(10);

        // dd($categories); // For Debugging Purpose Only (-  #Removable-Content )
        return view("admin.categories.list", compact("categories")); // Sending Data To View Using "compact()" function.
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // echo "<h1> Category Create Page </h1>";
        return view("admin.categories.create"); // Form To Add Categories
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'status' => 'required|in:0,1', // Ensure status is either 0 or 1
        ]);

        if ($validator->passes()) {
            $category = new Category(); // Making a new Instance of Category Model.
            $category->name = $request->name; // Getting Name (Values) From The Form Using Request Method
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();
            // Save Temporary Image Permanently In Dtabase Here.
            if (!empty($request->image_id)) {

                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);
                $newImageName = $category->id . '.' . $ext;
                $sPath = public_path("/tempImgs/$tempImage->name");
                $dPath = public_path("/uploads/category/$newImageName");
                // File::copy($sPath, $dPath);
                File::move($sPath, $dPath);
                $category->image = $newImageName;
                $category->save();
            }
            $request->session()->flash('success', 'Category Has Been Added Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category Has Been Added Successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Sorry Category Could Not Be Added. It Might Be Already Present. Please Check Once Againg'
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the composer require intervention/image for editing the specified resource.
     */
    public function edit($categoryId, Request $request)
    {
        // echo $categoryId; // Testing Purpose
        $category = Category::find($categoryId);
        if (empty($category)) {
            return redirect()->route('categories.index');
        }
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category Not Found'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
        ]);
        if ($validator->passes()) {
            $category->name = $request->name; // Getting Name (Values) From The Form Using Request Method
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            $oldImage = $category->image;
            
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '-' . time() . '.' . $ext;

                $sPath = public_path("/tempImgs/$tempImage->name");
                $dPath = public_path("/uploads/category/$newImageName");

                // File::copy($sPath, $dPath);
                File::move($sPath, $dPath);

                $category->image = $newImageName;
                $category->save();

                // Deleting Old Image, To Reduce Server Load.
                // File::delete(public_path("/tempImg/$tempImage->name"));
                File::delete(public_path("/uploads/category/$oldImage"));
            }

            // return redirect()->route('categories.index');

            $request->session()->flash('success', 'Category Has Been Updated Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category Has Been Updated Successfully'
            ]);
            
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Sorry. The Category Could Not Be Updated.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($categoryId, Request $request)
    {
        $category = Category::find($categoryId); // TO Find Category By Using ID
        if (empty($category)) {
            // return redirect()->route('categories.index');

            $request->session()->flash("error", "Category Could Not Be Found.");

            return response()->json([
                "status" => false,
                "message" => "Category Could Not Be Deleted Successfully."
            ]);
        }

        File::delete(public_path("/uploads/category/$category->image"));
        File::delete(public_path("/tempImgs/$category->image"));

        $category->delete();


        $request->session()->flash("success", "Category Has Been Successfully Deleted.");

        return response()->json([
            "status" => true,
            "message" => "Category Has Been Deleted Successfully !",
        ]);
    }
}
