<?php

use App\Http\Controllers\backend\BrandController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\CategoryTypeController;
use App\Http\Controllers\backend\ColorController;
use App\Http\Controllers\backend\CompanySettingController;
use App\Http\Controllers\backend\CountryController;
use App\Http\Controllers\backend\CustomerController;
use App\Http\Controllers\backend\DistrictController;
use App\Http\Controllers\backend\DivisionController;
use App\Http\Controllers\backend\ExpenseCategoryController;
use App\Http\Controllers\backend\ExpenseController;
use App\Http\Controllers\backend\PaperQualityController;
use App\Http\Controllers\backend\PaymentTypeController;
use App\Http\Controllers\backend\PosController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\ProductTypeController;
use App\Http\Controllers\backend\PurchaseController;
use App\Http\Controllers\backend\PurchasePaymentController;
use App\Http\Controllers\backend\PurchaseReceiptController;
use App\Http\Controllers\backend\SizeController;
use App\Http\Controllers\backend\StockAdjustmentController;
use App\Http\Controllers\backend\StockOpeningController;
use App\Http\Controllers\backend\StockTransferController;
use App\Http\Controllers\backend\SubCategoryController;
use App\Http\Controllers\backend\SupplierController;
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
        Route::get('products/import_csv/file', [ProductController::class, 'importCsvModal'])->name('import_csv');
        Route::post('products/handle/upload_csv', [ProductController::class, 'importCsv'])->name('handle_csv');

        Route::get('products/product-list/allProducts', [ProductController::class, 'productList'])->name('productsList');
        Route::get('products/childProductList/{product}', [ProductController::class, 'childProductList'])->name('childProductList');
        Route::get('products/product-search/{name}', [ProductController::class, 'productSearch'])->name('productsSearch');
        Route::get('products/product-byCategory/{category}', [ProductController::class, 'productByCategory'])->name('productsByCategory');
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
        Route::get('warehouses/{warehouse}/ajax', [WarehouseController::class, 'showForAjax'])->name('warehouses.showForAjax');

        // Opening Stock Management

        Route::get('openingStock', [StockOpeningController::class, 'index'])->name('openingStock.index');
        Route::post('openingStock/list', [StockOpeningController::class, 'listAjax'])->name('openingStock.list');
        Route::get('openingStock/create-modal', [StockOpeningController::class, 'createModal'])->name('openingStock.createModal');
        Route::post('openingStock', [StockOpeningController::class, 'store'])->name('openingStock.store');

        // Transfer Stock Management
        Route::get('transfers', [StockTransferController::class, 'index'])->name('transfers.index');
        Route::post('transfers/list', [StockTransferController::class, 'listAjax'])->name('transfers.list');
        Route::get('transfers/create', [StockTransferController::class, 'create'])->name('transfers.create');
        Route::post('transfers/store', [StockTransferController::class, 'store'])->name('transfers.store');
        Route::delete('transfers/{transfer}/delete', [StockTransferController::class, 'destroy'])->name('transfers.destroy');
        Route::get('transfers/{transfer}', [StockTransferController::class, 'show'])->name('transfers.show');
        Route::post('transfers/{transfer}/post', [StockTransferController::class, 'post'])->name('transfers.post');

        Route::get('transfers/{transfer}/edit', [StockTransferController::class, 'edit'])->name('transfers.edit');
        Route::post('transfers/{transfer}', [StockTransferController::class, 'update'])->name('transfers.update');

        // Adjust Stock Management
        Route::get('adjustments', [StockAdjustmentController::class, 'index'])->name('adjustments.index');
        Route::post('adjustments/list', [StockAdjustmentController::class, 'listAjax'])->name('adjustments.list');
        Route::get('adjustments/create', [StockAdjustmentController::class, 'create'])->name('adjustments.create');
        Route::post('adjustments', [StockAdjustmentController::class, 'store'])->name('adjustments.store');

        Route::POST('adjustments/{id}/post', [StockAdjustmentController::class, 'post'])->name('adjustments.post');
        Route::get('adjustments/{adjustment}', [StockAdjustmentController::class, 'show'])->name('adjustments.show');
        Route::get('adjustments/{ledger}/edit', [StockAdjustmentController::class, 'edit'])->name('adjustments.edit');
        Route::POST('adjustments/{ledger}/cancel', [StockAdjustmentController::class, 'cancel'])->name('adjustments.cancel');
        Route::put('adjustments/{ledger}', [StockAdjustmentController::class, 'update'])->name('adjustments.update');
        Route::delete('adjustments/{ledger}/delete', [StockAdjustmentController::class, 'destroy'])->name('adjustments.destroy');

        // bulk system qty endpoint
        Route::post('adjustments/stock-currents/bulk', [StockAdjustmentController::class, 'systemQtyBulk'])->name('adjustments.stock.currents.bulk');

        // store already exists per your previous code

        Route::get('adjustments/parent/{parent}/edit', [StockAdjustmentController::class, 'editParent'])
            ->name('adjustments.parent.edit');

        // product variants endpoint (if not already present)
        Route::get('product/{parent}/variants', [StockAdjustmentController::class, 'ajaxParentVariants'])->name('product.variants');

        Route::put('adjustments/parent/{parent}', [StockAdjustmentController::class, 'updateParent'])
            ->name('adjustments.parent.update');
        Route::get('adjustments/parent-variants', [StockAdjustmentController::class, 'ajaxParentVariants'])
            ->name('adjustments.parent.variants');

    });

    Route::prefix('supplier')->name('supplier.')->group(function () {

        Route::get('suppliers', [SupplierController::class, 'index'])->name('index');
        Route::get('suppliers/create', [SupplierController::class, 'create'])->name('create');
        Route::get('suppliers/createModal', [SupplierController::class, 'createModal'])->name('createModal');
        Route::post('suppliers/list', [SupplierController::class, 'listAjax'])->name('list.ajax');
        Route::post('suppliers', [SupplierController::class, 'store'])->name('store');
        Route::get('suppliers/edit/{supplier}', [SupplierController::class, 'editModal'])->whereNumber('supplier')->name('edit');
        Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->name('update');
        Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->name('show');
        Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('destroy');
        Route::get('suppliers/select2/type', [SupplierController::class, 'select2'])->name('select2');
        Route::get('suppliers/import_csv/file', [SupplierController::class, 'importCsvModal'])->name('import_csv');
        Route::post('suppliers/handle/upload_csv', [SupplierController::class, 'importCsv'])->name('handle_csv');
        Route::get('suppliers/recent/supplier', [SupplierController::class, 'recent_record'])->name('recent');
    });

    Route::prefix('customer')->name('customer.')->group(function () {

        Route::get('customers', [CustomerController::class, 'index'])->name('index');
        Route::get('customers/create', [CustomerController::class, 'createModal'])->name('create');
        Route::post('customers/list', [CustomerController::class, 'listAjax'])->name('list.ajax');
        Route::post('customers', [CustomerController::class, 'store'])->name('store');
        Route::get('customers/edit/{customer}', [CustomerController::class, 'editModal'])->whereNumber('supplier')->name('edit');
        Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::get('customers/import_csv/file', [CustomerController::class, 'importCsvModal'])->name('import_csv');
        Route::post('customers/handle/upload_csv', [CustomerController::class, 'importCsv'])->name('handle_csv');
        Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::get('customers/select2/type', [CustomerController::class, 'select2'])->name('select2');
    });

    Route::prefix('pos')->name('pos.')->group(function () {

        Route::get('pos', [PosController::class, 'index'])->name('index');

    });

    Route::prefix('purchase')->name('purchase.')->group(function () {

        Route::get('purchase', [PurchaseController::class, 'index'])->name('index');
        Route::get('purchase/create', [PurchaseController::class, 'create'])->name('orders.create');
        Route::post('purchase/list', [PurchaseController::class, 'listAjax'])->name('list.ajax');
        Route::post('purchase', [PurchaseController::class, 'store'])->name('orders.store');
        Route::get('purchase/edit/{purchase}', [PurchaseController::class, 'edit'])->whereNumber('purchase')->name('orders.edit');

        Route::put('purchase/{purchase}', [PurchaseController::class, 'update'])->name('orders.update');
        Route::delete('purchase/{purchase}', [PurchaseController::class, 'destroy'])->name('orders.destroy');
        Route::get('purchase/select2/type', [PurchaseController::class, 'select2'])->name('orders.select2');
        Route::get('purchase/orders/{order}', [PurchaseController::class, 'show'])->name('orders.show');

        Route::get('purchase/orders/{order}/payment-modal',[PurchaseController::class, 'paymentModal'])->name('orders.payment.modal');
        Route::post('purchase/orders/{order}/payments', [PurchasePaymentController::class, 'storeForOrder'])
            ->name('orders.payments.store');
        
        Route::get('purchase/orders/{order}/receive-all-modal', [PurchaseReceiptController::class, 'receiveAllModal'])
    ->name('orders.receive-all.modal');
        Route::post('purchase/receipts', [PurchaseReceiptController::class, 'store'])
    ->name('receipts.store');
         

    });

    Route::prefix('paymentTypes')->name('paymentTypes.')->group(function () {

        Route::get('paymentType', [PaymentTypeController::class, 'index'])->name('index');
        Route::get('paymentType/create', [PaymentTypeController::class, 'createModal'])->name('create');
        Route::post('paymentType/list', [PaymentTypeController::class, 'listAjax'])->name('list.ajax');
        Route::post('paymentType', [PaymentTypeController::class, 'store'])->name('store');
        Route::get('paymentType/edit/{paymentType}', [PaymentTypeController::class, 'editModal'])->whereNumber('paymentType')->name('edit');
        Route::put('paymentType/{paymentType}', [PaymentTypeController::class, 'update'])->name('update');
        Route::get('paymentType/{paymentType}', [PaymentTypeController::class, 'show'])->name('show');
        Route::delete('paymentType/{paymentType}', [PaymentTypeController::class, 'destroy'])->name('destroy');
        Route::get('paymentType/select2/type', [PaymentTypeController::class, 'select2'])->name('select2');
    });

    Route::prefix('expenseCategories')->name('expenseCategories.')->group(function () {

        Route::get('expenseCategories', [ExpenseCategoryController::class, 'index'])->name('index');
        Route::get('expenseCategories/create', [ExpenseCategoryController::class, 'createModal'])->name('create');
        Route::post('expenseCategories/list', [ExpenseCategoryController::class, 'listAjax'])->name('list.ajax');
        Route::post('expenseCategories', [ExpenseCategoryController::class, 'store'])->name('store');
        Route::get('expenseCategories/edit/{expenseCategory}', [ExpenseCategoryController::class, 'editModal'])->whereNumber('expenseCategory')->name('edit');
        Route::put('expenseCategories/{expenseCategory}', [ExpenseCategoryController::class, 'update'])->name('update');
        Route::get('expenseCategories/{expenseCategory}', [ExpenseCategoryController::class, 'show'])->name('show');
        Route::delete('expenseCategories/{expenseCategory}', [ExpenseCategoryController::class, 'destroy'])->name('destroy');
        Route::get('expenseCategories/select2/type', [ExpenseCategoryController::class, 'select2'])->name('select2');
    });

    Route::prefix('expenses')->name('expenses.')->group(function () {

        Route::get('expense', [ExpenseController::class, 'index'])->name('index');
        Route::get('expense/create', [ExpenseController::class, 'createModal'])->name('createModal');
        Route::post('expense/list', [ExpenseController::class, 'listAjax'])->name('list.ajax');
        Route::post('expense', [ExpenseController::class, 'store'])->name('store');
        Route::get('expense/edit/{expense}', [ExpenseController::class, 'editModal'])->whereNumber('expense')->name('editModal');
        Route::put('expense/{expense}', [ExpenseController::class, 'update'])->name('update');
        Route::get('expense/{expense}', [ExpenseController::class, 'show'])->name('show');
        Route::delete('expense/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
        Route::get('expense/select2/type', [ExpenseController::class, 'select2'])->name('select2');
    });

    Route::prefix('company_setting')->name('company_setting.')->group(function () {
        Route::get('company_settings', [CompanySettingController::class, 'index'])->name('index');
        Route::get('company_settings/create', [CompanySettingController::class, 'create'])->name('create');
        Route::post('company_settings/list', [CompanySettingController::class, 'listAjax'])->name('list.ajax');
        Route::post('company_settings', [CompanySettingController::class, 'store'])->name('store');
        Route::get('company_settings/edit/{company_setting}', [CompanySettingController::class, 'edit'])->whereNumber('company_setting')->name('edit');
        Route::put('company_settings/{company_setting}', [CompanySettingController::class, 'update'])->name('update');
        Route::delete('company_settings/{company_setting}', [CompanySettingController::class, 'destroy'])->name('destroy');
    });

});
