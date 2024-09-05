<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Models\TempImage;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // using Table Join (LeftJoin) To Get Combined Data From Multiple tables. 
        $subcategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
            ->latest('sub_categories.id')->leftJoin('categories', 'categories.id', 'sub_categories.category_id');

        if (!empty($request->get("keyword"))) {
            $subcategories = $subcategories
                ->where("sub_categories.name", "like", "%" . $request->get("keyword") . "%")
                ->orWhere("categories.name", "like", "%" . $request->get("keyword") . "%");
        }
        $subcategories = $subcategories->paginate(10);

        // dd($categories); // For Debugging Purpose Only (-  #Removable-Content )
        return view("admin.subcategories.list", compact("subcategories")); // Sending Data To View Using "compact()" function.
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy("name", "asc")->get();
        return view("admin.subcategories.create", compact("categories"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Laravel Validation Rules
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required|in:0,1', // Ensure status is either 0 or 1
        ]);
        if ($validator->passes()) {
            $subCategory = new SubCategory(); // Making a new Instance of Category Model.
            $subCategory->name = $request->name; // Getting Name (Values) From The Form Using Request Method
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            // Save Temporary Image Permanently In Dtabase Here.
            if (!empty($request->image_id)) {

                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);
                $newImageName = $subCategory->id . '.' . $ext;
                $sPath = public_path("/tempImgs/$tempImage->name");
                $dPath = public_path("/uploads/subcategory/$newImageName");
                // File::copy($sPath, $dPath);
                File::move($sPath, $dPath);
                $subCategory->image = $newImageName;
                $subCategory->save();
            }

            $request->session()->flash('success', 'Sub Category Has Been Successfully Added ');
            return response()->json([
                'status' => true,
                'message' => 'The Sub Category Has Been Successfully Added.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => 'Sorry Sub Category Could Not Be Added.'
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
     * Show the form for editing the specified resource.
     */

    public function edit($id, Request $request)
    {
        $subcategory = SubCategory::find($id);
        if (empty($subcategory)) {
            $request->session()->flash('error', 'Record Not Found !!!');
            return redirect()->route('subcategories.index');
        }
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update($id, Request $request)
    {
        $subcategory = SubCategory::find($id);
        if (empty($subcategory)) {
            $request->session()->flash('error', 'Record Not Found !!!');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Sub Category Not Updated'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,' . $subcategory->id . ',id',
            'category' => 'required',
            'status' => 'required',
        ]);
        if ($validator->passes()) {
            $subcategory->name = $request->name; // Getting Name (Values) From The Form Using Request Method
            $subcategory->slug = $request->slug;
            $subcategory->status = $request->status;
            $subcategory->category_id = $request->category;
            $subcategory->save();

            $oldImage = $subcategory->image;

            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $subcategory->id . '-' . time() . '.' . $ext;

                $sPath = public_path("/tempImgs/$tempImage->name");
                $dPath = public_path("/uploads/subcategory/$newImageName");

                // File::copy($sPath, $dPath);
                File::move($sPath, $dPath);

                $subcategory->image = $newImageName;
                $subcategory->save();

                // Deleting Old Image, To Reduce Server Load.
                // File::delete(public_path("/tempImg/$tempImage->name"));
                File::delete(public_path("/uploads/subcategory/$oldImage"));
            }

            $request->session()->flash('success', 'Sub Category Has Been Updated Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Sub Category Has Been Updated Successfully'
            ]);
             
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Sorry. The Sub Category Could Not Be Updated.',
                'errors' => $validator->errors(), // Include validation errors
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($subcategoryId, Request $request)
    {
        $subcategory = SubCategory::find($subcategoryId); // TO Find Category By Using ID
        if (empty($subcategory)) {
            // return redirect()->route('categories.index');

            $request->session()->flash("error", "Sub Category Could Not Be Found.");

            return response()->json([
                "status" => false,
                "message" => "Sub Category Could Not Be Deleted Successfully."
            ]);
        }

        File::delete(public_path("/uploads/subcategory/$subcategory->image"));
        File::delete(public_path("/tempImgs/$subcategory->image"));

        $subcategory->delete();


        $request->session()->flash("success", "Sub Category Has Been Successfully Deleted.");

        return response()->json([
            "status" => true,
            "message" => "Sub Category Has Been Deleted Successfully !",
        ]);
    }
}
