<?php
use App\Http\Controllers\backend\BrandController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\CategoryTypeController;
use App\Http\Controllers\backend\ColorController;
use App\Http\Controllers\backend\CountryController;
use App\Http\Controllers\backend\DistrictController;
use App\Http\Controllers\backend\DivisionController;
use App\Http\Controllers\backend\PaperQualityController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\ProductTypeController;
use App\Http\Controllers\backend\SizeController;
use App\Http\Controllers\backend\StockAdjustmentController;
use App\Http\Controllers\backend\StockOpeningController;
use App\Http\Controllers\backend\StockTransferController;
use App\Http\Controllers\backend\SubCategoryController;
use App\Http\Controllers\backend\UnitController;
use App\Http\Controllers\backend\UpazilaController;
use App\Http\Controllers\backend\WarehouseController;
use Illuminate\Support\Facades\Route;

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

        // Route::get('category-types/select2', [CategoryTypeController::class, 'select2'])->name('category-types.select2');
        // Route::get('category-types/select2', [CategoryTypeController::class, 'select2'])->name('types.select2');

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
        Route::get('category-types/select2', [CategoryTypeController::class, 'select2'])->name('types.select2');
        Route::get('categories/select2/type', [CategoryController::class, 'select2'])->name('cat.select2');
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

        Route::get('subcategories/select2/type', [SubCategoryController::class, 'select2'])->name('select2');
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
        Route::get('brand/select2/type', [BrandController::class, 'select2'])->name('select2');
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
        Route::get('unit/select2/type', [UnitController::class, 'select2'])->name('select2');
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
        Route::get('colors/select2/type', [ColorController::class, 'select2'])->name('select2');
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
        Route::get('size/select2/type', [SizeController::class, 'select2'])->name('select2');
    });
    // paper quality Management
    Route::prefix('paper_quality')->name('paper_quality.')->group(function () {

        Route::get('/', [PaperQualityController::class, 'index'])->name('index');
        Route::post('paper_quality/list', [PaperQualityController::class, 'listAjax'])->name('list.ajax');
        Route::get('paper_quality/create-modal', [PaperQualityController::class, 'createModal'])->name('createModal');
        Route::get('paper_quality/{paperQuality}/edit-modal', [PaperQualityController::class, 'editModal'])->whereNumber('size')->name('editModal');
        Route::post('/paper_quality', [PaperQualityController::class, 'store'])->name('store');
        Route::put('paper_quality/{paperQuality}', [PaperQualityController::class, 'update'])->whereNumber('paperQuality')->name('update');
        Route::delete('paper_quality/{paperQuality}', [PaperQualityController::class, 'destroy'])->whereNumber('paperQuality')->name('destroy');
        Route::get('paper_quality/select2/type', [PaperQualityController::class, 'select2'])->name('select2');
    });

    // Product Management
    Route::prefix('product')->name('product.')->group(function () {

        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        // Route::get('product/view/{product}', [ProductController::class, 'index'])->name('products.view');
        Route::get('products/create', [ProductController::class, 'createModal'])->name('products.create');
        Route::post('products/list', [ProductController::class, 'listAjax'])->name('products.list.ajax');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/edit-modal/{product}', [ProductController::class, 'editModal'])->whereNumber('product')->name('products.editModal');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('products/category', [ProductController::class, 'select2Category'])->name('products.category');
        Route::get('products/subcategory', [ProductController::class, 'select2Subcategory'])->name('products.subcategory');
        Route::get('products/brand', [ProductController::class, 'select2Brand'])->name('products.brand');
        Route::get('products/color', [ProductController::class, 'select2Color'])->name('products.color');
        Route::get('products/size', [ProductController::class, 'select2Size'])->name('products.size');
        Route::get('products/product-type', [ProductController::class, 'select2ProductType'])->name('products.product-type');
        Route::get('products/select2/all', [ProductController::class, 'select2'])->name('select2');
        // Parent list
        Route::get('/products/parents/get', [ProductController::class, 'parentsIndex'])->name('parents.index');
        Route::get('/products/parents/select2', [ProductController::class, 'parentsSelect2'])->name('parents.select2');
        Route::get('/products/{product}/variants', [ProductController::class, 'variants'])->name('variants');

    });

    // Product type Management
    Route::prefix('product-type')->name('product-type.')->group(function () {

        Route::get('product-types', [ProductTypeController::class, 'index'])->name('product-types.index');
        Route::get('product-types/create-modal', [ProductTypeController::class, 'createModal'])->name('product-types.createModal');
        Route::post('product-types/list', [ProductTypeController::class, 'listAjax'])->name('product-types.list.ajax');
        Route::post('product-types', [ProductTypeController::class, 'store'])->name('product-types.store');
        Route::get('product-types/edit-modal/{productType}', [ProductTypeController::class, 'editModal'])->whereNumber('productType')->name('product-types.editModal');
        Route::put('product-types/{productType}', [ProductTypeController::class, 'update'])->name('product-types.update');
        Route::get('product-types/{productType}', [ProductTypeController::class, 'show'])->name('product-types.show');
        Route::delete('product-types/{productType}', [ProductTypeController::class, 'destroy'])->name('product-types.destroy');
        Route::get('product-types/select2/type', [ProductTypeController::class, 'select2'])->name('select2');
    });

    // Inventory Opening Stock
    Route::prefix('inventory')->name('inventory.')->group(function () {

        // Warehouse Management
        Route::get('warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
        Route::post('warehouses/list', [WarehouseController::class, 'listAjax'])->name('warehouses.list.ajax');
        Route::get('warehouses/create-modal', [WarehouseController::class, 'createModal'])->name('warehouses.createModal');
        Route::get('warehouses/{warehouse}/edit-modal', [WarehouseController::class, 'editModal'])->name('warehouses.editModal');
        Route::post('warehouses/', [WarehouseController::class, 'store'])->name('warehouses.store');
        Route::put('warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update');
        Route::delete('warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');
        Route::get('warehouses/select2', [WarehouseController::class, 'select2'])->name('warehouses.select2');

        // Opening Stock Management

        Route::get('openingStock', [StockOpeningController::class, 'index'])->name('openingStock.index');
        Route::post('openingStock/list', [StockOpeningController::class, 'listAjax'])->name('openingStock.list');
        Route::get('openingStock/create-modal', [StockOpeningController::class, 'createModal'])->name('openingStock.createModal');
        Route::post('openingStock', [StockOpeningController::class, 'store'])->name('openingStock.store');

        // Transfer Stock Management
        Route::get('transfers', [StockTransferController::class, 'index'])->name('transfers.index');
        Route::post('transfers/list', [StockTransferController::class, 'listAjax'])->name('transfers.list');
        Route::get('transfers/create-modal', [StockTransferController::class, 'createModal'])->name('transfers.createModal');
        Route::post('transfers', [StockTransferController::class, 'store'])->name('transfers.store');

        // Adjust Stock Management
        Route::get('adjustments', [StockAdjustmentController::class, 'index'])->name('adjustments.index');
        Route::post('adjustments/list', [StockAdjustmentController::class, 'listAjax'])->name('adjustments.list');
        Route::get('adjustments/create-modal', [StockAdjustmentController::class, 'createModal'])->name('adjustments.createModal');
        Route::get('adjustments/create', [StockAdjustmentController::class, 'create'])->name('adjustments.create');
        Route::post('adjustments', [StockAdjustmentController::class, 'store'])->name('adjustments.store');
        // NEW:
        // Route::get('adjustments/{ledger}/edit-modal', [StockAdjustmentController::class, 'editModal'])->name('adjustments.editModal');
        // Route::put('adjustments/{ledger}', [StockAdjustmentController::class, 'update'])->name('adjustments.update');
        
        Route::get('adjustments/parent/{parent}/edit', [StockAdjustmentController::class, 'editParent'])
            ->name('adjustments.parent.edit');
        Route::put('adjustments/parent/{parent}', [StockAdjustmentController::class, 'updateParent'])
            ->name('adjustments.parent.update');
        Route::get('adjustments/parent-variants', [StockAdjustmentController::class, 'ajaxParentVariants'])
            ->name('adjustments.parent.variants');
        Route::delete('adjustments/{ledger}/delete', [StockAdjustmentController::class, 'destroy'])->name('adjustments.destroy');


    });

});
