<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('products', function (Blueprint $t) {
      $t->id();

      // Identity
      $t->string('name');
      $t->string('slug')->unique();
      $t->string('sku')->unique();           // human SKU / code
      $t->string('barcode')->nullable()->unique(); // EAN/UPC/QR (nullable)

      // Relations (nullable where appropriate)
      $t->foreignId('unit_id')->constrained('units')->cascadeOnUpdate()->restrictOnDelete();
      $t->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
      $t->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
      $t->foreignId('subcategory_id')->nullable()->constrained('subcategories')->nullOnDelete();
      $t->foreignId('tax_id')->nullable()->constrained('taxes')->nullOnDelete(); // default tax (optional)

      // Pricing
      $t->decimal('price', 12, 2)->default(0);        // selling/base price
      $t->decimal('cost_price', 12, 2)->nullable();   // average/last cost
      $t->decimal('mrp', 12, 2)->nullable();          // optional MRP/compare-at
      $t->boolean('tax_included')->default(false);    // price includes tax?

      // Inventory behavior (ledger will track qty)
      $t->boolean('track_stock')->default(true);      // false => ignore stock checks
      $t->decimal('reorder_level', 12, 3)->nullable();// low-stock threshold (per unit precision)

      // Content / Media
      $t->string('image')->nullable();                // primary image path
      // SEO / Meta
      $t->string('meta_title')->nullable();
      $t->text('meta_description')->nullable();
      $t->text('meta_keywords')->nullable();
      $t->string('meta_image')->nullable();

      // Descriptions
      $t->string('short_description')->nullable();
      $t->longText('description')->nullable();

      // Future-proof flags
      $t->boolean('has_variants')->default(false);    // later enable variant module
      $t->boolean('is_active')->default(true);

      // Optional physical attributes (for shipping/valuation)
      $t->decimal('weight', 12, 3)->nullable();       // kg or unit's meaning
      $t->decimal('width', 12, 3)->nullable();
      $t->decimal('height', 12, 3)->nullable();
      $t->decimal('length', 12, 3)->nullable();

      $t->softDeletes();
      $t->timestamps();

      // Helpful indexes for filters/search
      $t->index(['brand_id', 'category_id', 'subcategory_id', 'is_active']);
      $t->index(['name']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
