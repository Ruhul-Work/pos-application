<?php

// database/migrations/2025_10_09_100001_create_stock_tables.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {

    // বর্তমান স্টক (fast read)
    Schema::create('stock_currents', function (Blueprint $t) {
      $t->id();
      $t->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();
      $t->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete()->cascadeOnUpdate(); // future-ready
      $t->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete()->cascadeOnUpdate(); // না থাকলে nullable রাখুন
      $t->decimal('quantity', 18, 3)->default(0);
      $t->timestamps();

      $t->unique(['product_id','product_variant_id','warehouse_id'], 'stock_currents_unique');
      $t->index(['warehouse_id']);
    });

    // মুভমেন্ট/লেজার (auditable history)
    Schema::create('stock_ledgers', function (Blueprint $t) {
      $t->id();
      $t->dateTime('txn_date')->useCurrent();
      $t->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();
      $t->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete()->cascadeOnUpdate();
      $t->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete()->cascadeOnUpdate();

      // রেফারেন্স: কোন ট্রানজেকশন থেকে এন্ট্রি
      $t->string('ref_type', 32)->nullable();   // e.g. 'purchase','sale','adjust','transfer'
      $t->unsignedBigInteger('ref_id')->nullable();

      // দিক ও পরিমাণ
      $t->enum('direction', ['in','out']);
      $t->decimal('quantity', 18, 3);
      $t->decimal('unit_cost', 12, 4)->nullable(); // FIFO/Landed cost ইত্যাদি চাইলে

      $t->string('note', 255)->nullable();
      $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

      $t->timestamps();
      $t->index(['product_id','product_variant_id','warehouse_id']);
      $t->index(['ref_type','ref_id']);
    });
  }

  public function down(): void {
    Schema::dropIfExists('stock_ledgers');
    Schema::dropIfExists('stock_currents');
  }
};

