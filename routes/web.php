<?php

// use App\Http\Controllers\ProfileController;

use App\Http\Controllers\admin\BrandsController;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
// use Intervention\Image\Image;
// use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Interfaces\ImageInterface;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\admin\TempImagesController;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => ['admin.guest']], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });
    Route::group(['middleware' => ['admin.auth']], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // Products Sub Category Routes
        Route::resource('/product-subcategories', ProductSubCategoryController::class);
        
        // Products Routes
        Route::resource('/products', ProductController::class);

        // Route::get('/products', [ProductController::class, 'index'])->name('products.index');

        // Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create');
        // Route::post('/products', [ProductsController::class, 'store'])->name('products.store');

        
        // Route::get('/products/{product}/edit/', [ProductsController::class, 'edit'])->name('products.edit');
        // Route::put('/products/{product}', [ProductsController::class, 'update'])->name('products.update');

        // Route::delete('/products/{product}', [ProductsController::class, 'destroy'])->name('products.delete');


        // Brands Routes
        Route::get('/brands', [BrandsController::class, 'index'])->name('brands.index');

        Route::get('/brands/create', [BrandsController::class, 'create'])->name('brands.create');
        Route::post('/brands', [BrandsController::class, 'store'])->name('brands.store');

        
        Route::get('/brands/{brand}/edit/', [BrandsController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brand}', [BrandsController::class, 'update'])->name('brands.update');

        Route::delete('/brands/{brand}', [BrandsController::class, 'destroy'])->name('brands.delete');

        // Sub Category Routes
        Route::get('/subcategories', [SubCategoryController::class, 'index'])->name('subcategories.index');
        
        Route::get('/subcategories/create', [SubCategoryController::class, 'create'])->name('subcategories.create');
        Route::post('/subcategories', [SubCategoryController::class, 'store'])->name('subcategories.store');

        Route::get('/subcategories/{category}/edit/', [SubCategoryController::class, 'edit'])->name('subcategories.edit');
        Route::put('/subcategories/{category}', [SubCategoryController::class, 'update'])->name('subcategories.update');

        Route::delete('/subcategories/{category}', [SubCategoryController::class, 'destroy'])->name('subcategories.delete');

        // Category Routes
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

        Route::get('/categories/{category}/edit/', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.delete');


        // Image Route
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');

        // Slug Route
        Route::get('/getSlug', function (Request $request) {
            $slug = '';
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('get.slug');
    });
});
