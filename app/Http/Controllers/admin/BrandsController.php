<?php

namespace App\Http\Controllers\admin;


use App\Models\Brand;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brands = Brand::latest();

        if (!empty($request->get("keyword"))) {
            $brands = $brands->where("name", "like", "%" . $request->get("keyword") . "%");
        }
        $brands = $brands->paginate(10);

        return view("admin.brands.list", compact("brands"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.brands.create");
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
            $brand = new Brand(); // Making a new Instance of Brand Model.
            $brand->name = $request->name; // Getting Name (Values) From The Form Using Request Method
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();
            // Save Temporary Image Permanently In Dtabase Here.
            if (!empty($request->image_id)) {

                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);
                $newImageName = $brand->id . '.' . $ext;
                $sPath = public_path("/tempImgs/$tempImage->name");
                $dPath = public_path("/uploads/Brands/$newImageName");
                // File::copy($sPath, $dPath);
                File::move($sPath, $dPath);
                $brand->image = $newImageName;
                $brand->save();
            }
            $request->session()->flash('success', 'Brand Has Been Added Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brand Has Been Added Successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Sorry Brand Could Not Be Added. It Might Be Already Present. Please Check Once Againg'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($brandId, Request $request)
    {
        // echo $brandId; // Testing Purpose
        $brand = Brand::find($brandId);
        if (empty($brand)) {
            return redirect()->route('brands.index');
        }
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($brandId, Request $request)
    {
        $brand = Brand::find($brandId);
        if (empty($brand)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Brand Not Found'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $brand->id . ',id',
        ]);
        if ($validator->passes()) {
            $brand->name = $request->name; // Getting Name (Values) From The Form Using Request Method
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $oldImage = $brand->image;

            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $brand->id . '-' . time() . '.' . $ext;

                $sPath = public_path("/tempImgs/$tempImage->name");
                $dPath = public_path("/uploads/Brands/$newImageName");

                // File::copy($sPath, $dPath);
                File::move($sPath, $dPath);

                $brand->image = $newImageName;
                $brand->save();

                // Deleting Old Image, To Reduce Server Load.
                // File::delete(public_path("/tempImg/$tempImage->name"));
                File::delete(public_path("/uploads/Brands/$oldImage"));
            }

            // return redirect()->route('categories.index');

            $request->session()->flash('success', 'Brands Has Been Updated Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brands Has Been Updated Successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Sorry. The Brands Could Not Be Updated.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($brandId, Request $request)
    {
        $brand = Brand::find($brandId); // TO Find Brand By Using ID
        if (empty($brand)) {
            // return redirect()->route('brands.index');

            $request->session()->flash("error", "Brand Could Not Be Found.");

            return response()->json([
                "status" => false,
                "message" => "Brand Could Not Be Deleted Successfully."
            ]);
        }

        File::delete(public_path("/uploads/Brands/$brand->image"));
        File::delete(public_path("/tempImgs/$brand->image"));

        $brand->delete();


        $request->session()->flash("success", "Brand Has Been Successfully Deleted.");

        return response()->json([
            "status" => true,
            "message" => "Brand Has Been Deleted Successfully !",
        ]);
    }
}
