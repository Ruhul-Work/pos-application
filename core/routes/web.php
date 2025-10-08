<?php

use App\Http\Controllers\backend\BrandController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\CategoryTypeController;
use App\Http\Controllers\backend\ColorController;
use App\Http\Controllers\backend\CountryController;
use App\Http\Controllers\backend\DistrictController;
use App\Http\Controllers\backend\DivisionController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\SubCategoryController;
use App\Http\Controllers\backend\UpazilaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\backend\UnitController;
use App\Http\Controllers\backend\SizeController;


Route::middleware(['web', 'auth', 'perm'])->group(function () {
    Route::prefix('country')->name('country.')->group(function () {

        Route::get('countries', [CountryController::class, 'index'])->name('countries.index');
        Route::get('countries/create-modal', [CountryController::class, 'createModal'])->name('countries.createModal');
        Route::post('countries/list', [CountryController::class, 'listAjax'])->name('countries.list.ajax');
        Route::post('countries', [CountryController::class, 'store'])->name('countries.store');
        Route::get('countries/{country}/edit-modal', [CountryController::class, 'editModal'])->whereNumber('country')->name('countries.editModal');
        Route::get('countries/{country}', [CountryController::class, 'show'])->name('countries.show');
        Route::put('countries/{country}', [CountryController::class, 'update'])->name('countries.update');
        Route::delete('countries/{country}', [CountryController::class, 'destroy'])->name('countries.destroy'); 

    });
   
    // Division Management

    Route::prefix('division')->name('division.')->group(function () {

        Route::get('divisions', [DivisionController::class, 'index'])->name('divisions.index');
        Route::get('divisions/create-modal', [DivisionController::class, 'createModal'])->name('divisions.createModal');
        Route::post('divisions/list', [DivisionController::class, 'listAjax'])->name('divisions.list.ajax');
        Route::post('divisions', [DivisionController::class, 'store'])->name('divisions.store');
        Route::get('divisions/{division}/edit-modal', [DivisionController::class, 'editModal'])->whereNumber('division')->name('divisions.editModal');
        Route::get('divisions/{division}', [DivisionController::class, 'show'])->name('divisions.show');
        Route::put('divisions/{division}', [DivisionController::class, 'update'])->name('divisions.update');
        Route::delete('divisions/{division}', [DivisionController::class, 'destroy'])->name('divisions.destroy');

      

    });

    // District Management

    Route::prefix('district')->name('district.')->group(function () {

        Route::get('districts', [DistrictController::class, 'index'])->name('districts.index');
        Route::get('districts/create-modal', [DistrictController::class, 'createModal'])->name('districts.createModal');
        Route::post('districts/list', [DistrictController::class, 'listAjax'])->name('districts.list.ajax');
        Route::post('districts', [DistrictController::class, 'store'])->name('districts.store');
        Route::get('districts/{district}/edit-modal', [DistrictController::class, 'editModal'])->whereNumber('district')->name('districts.editModal');
        Route::get('districts/{district}', [DistrictController::class, 'show'])->name('districts.show');
        Route::put('districts/{district}', [DistrictController::class, 'update'])->name('districts.update');
        Route::delete('districts/{district}', [DistrictController::class, 'destroy'])->name('districts.destroy');

    

    });

     // Upazila Management

    Route::prefix('upazila')->name('upazila.')->group(function () {

        Route::get('upazilas', [UpazilaController::class, 'index'])->name('upazilas.index');
        Route::get('upazilas/create-modal', [UpazilaController::class, 'createModal'])->name('upazilas.createModal');
        Route::post('upazilas/list', [UpazilaController::class, 'listAjax'])->name('upazilas.list.ajax');
        Route::post('upazilas', [UpazilaController::class, 'store'])->name('upazilas.store');
        Route::get('upazilas/{upazila}/edit-modal', [UpazilaController::class, 'editModal'])->whereNumber('upazila')->name('upazilas.editModal');
        Route::get('upazilas/{upazila}', [UpazilaController::class, 'show'])->name('upazilas.show');
        Route::put('upazilas/{upazila}', [UpazilaController::class, 'update'])->name('upazilas.update');
        Route::delete('upazilas/{upazila}', [UpazilaController::class, 'destroy'])->name('upazilas.destroy');



    });
//category type management
     Route::prefix('category-type')->name('category-type.')->group(function () {

        Route::get('category-types', [CategoryTypeController::class, 'index'])->name('category-types.index');
        Route::get('category-types/create-modal', [CategoryTypeController::class, 'createModal'])->name('category-types.createModal');
        Route::post('category-types/list', [CategoryTypeController::class, 'listAjax'])->name('category-types.list.ajax');
        Route::post('category-types', [CategoryTypeController::class, 'store'])->name('category-types.store');
        Route::get('category-types/{categoryType}/edit-modal', [CategoryTypeController::class, 'editModal'])->whereNumber('categoryType')->name('category-types.editModal');
        Route::put('category-types/{categoryType}', [CategoryTypeController::class, 'update'])->name('category-types.update');
        Route::get('category-types/{CategoryType}', [CategoryTypeController::class, 'show'])->name('category-types.show');
        Route::delete('category-types/{CategoryType}', [CategoryTypeController::class, 'destroy'])->name('category-types.destroy');
        

      

    });

     // Category Management

    Route::prefix('category')->name('category.')->group(function () {

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/create-modal', [CategoryController::class, 'createModal'])->name('categories.createModal');
        Route::post('categories/list', [CategoryController::class, 'listAjax'])->name('categories.list.ajax');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{category}/edit-modal', [CategoryController::class, 'editModal'])->whereNumber('category')->name('categories.editModal');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        //live serach
        // Route::get('categories/select2', [CategoryController::class,'select2'])->name('categories.category-type');
        Route::get('category-types/select2', [CategoryTypeController::class, 'select2'])->name('types.select2');

    
    });

     // Subcategory Management

    Route::prefix('subcategory')->name('subcategory.')->group(function () {

        Route::get('subcategories', [SubCategoryController::class, 'index'])->name('subcategories.index');
        Route::get('subcategories/create-modal', [SubCategoryController::class, 'createModal'])->name('subcategories.createModal');
        Route::post('subcategories/list', [SubCategoryController::class, 'listAjax'])->name('subcategories.list.ajax');
        Route::post('subcategories', [SubCategoryController::class, 'store'])->name('subcategories.store');
        Route::get('subcategories/{subcategory}/edit-modal', [SubCategoryController::class, 'editModal'])->whereNumber('subcategory')->name('subcategories.editModal');
        Route::get('subcategories/{subcategory}', [SubCategoryController::class, 'show'])->name('subcategories.show');
        Route::put('subcategories/{subcategory}', [SubCategoryController::class, 'update'])->name('subcategories.update');
        Route::delete('subcategories/{subcategory}', [SubCategoryController::class, 'destroy'])->name('subcategories.destroy');

      

    });

      // Brands Management

    Route::prefix('brand')->name('brand.')->group(function () {

        Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
        Route::get('brands/create', [BrandController::class, 'createModal'])->name('brands.create');
        Route::post('brands/list', [BrandController::class, 'listAjax'])->name('brands.list.ajax');
        Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
        Route::get('brands/edit/{brand}', [BrandController::class, 'editModal'])->whereNumber('brand')->name('brands.edit');
        Route::put('brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::get('brands/{brand}', [BrandController::class, 'show'])->name('brands.show');
        Route::delete('brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

       

    });


    // Unit Management
    Route::prefix('units')->name('units.')->group(function () {

    Route::get('/', [UnitController::class, 'index'])->name('index');
    Route::post('/list', [UnitController::class, 'listAjax'])->name('list.ajax');
    Route::get('/create-modal', [UnitController::class, 'createModal'])->name('createModal');
    Route::get('/{unit}/edit-modal', [UnitController::class, 'editModal'])
        ->whereNumber('unit')->name('editModal');
    Route::post('/', [UnitController::class, 'store'])->name('store');
    Route::put('/{unit}', [UnitController::class, 'update'])->whereNumber('unit')->name('update');
    Route::delete('/{unit}', [UnitController::class, 'destroy'])->whereNumber('unit')->name('destroy');
    });

    //color management
       Route::prefix('color')->name('color.')->group(function () {

        Route::get('colors', [ColorController::class, 'index'])->name('colors.index');
        Route::get('colors/create-modal', [ColorController::class, 'createModal'])->name('colors.createModal');
        Route::post('colors/list', [ColorController::class, 'listAjax'])->name('colors.list.ajax');
        Route::post('colors', [ColorController::class, 'store'])->name('colors.store');
        Route::get('colors/edit-modal/{color}', [ColorController::class, 'editModal'])->whereNumber('color')->name('colors.editModal');
        Route::put('colors/{color}', [ColorController::class, 'update'])->name('colors.update');
        Route::get('colors/{color}', [ColorController::class, 'show'])->name('colors.show');
        Route::delete('colors/{color}', [ColorController::class, 'destroy'])->name('colors.destroy');

       

    });


    // Size Management
    Route::prefix('sizes')->name('sizes.')->group(function () {

    Route::get('/', [SizeController::class, 'index'])->name('index');
    Route::post('/list', [SizeController::class, 'listAjax'])->name('list.ajax');
    Route::get('/create-modal', [SizeController::class, 'createModal'])->name('createModal');
    Route::get('/{size}/edit-modal', [SizeController::class, 'editModal'])->whereNumber('size')->name('editModal');
    Route::post('/', [SizeController::class, 'store'])->name('store');
    Route::put('/{size}', [SizeController::class, 'update'])->whereNumber('size')->name('update');
    Route::delete('/{size}', [SizeController::class, 'destroy'])->whereNumber('size')->name('destroy');
  });

   // Product Management
    Route::prefix('product')->name('product.')->group(function () {

   Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/create-modal', [ColorController::class, 'createModal'])->name('products.createModal');
        Route::post('products/list', [ColorController::class, 'listAjax'])->name('products.list.ajax');
        Route::post('products', [ColorController::class, 'store'])->name('products.store');
        Route::get('products/edit-modal/{color}', [ColorController::class, 'editModal'])->whereNumber('color')->name('products.editModal');
        Route::put('products/{color}', [ColorController::class, 'update'])->name('products.update');
        Route::get('products/{color}', [ColorController::class, 'show'])->name('products.show');
        Route::delete('products/{color}', [ColorController::class, 'destroy'])->name('products.destroy');

  });


});



