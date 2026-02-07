<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('unit_staffings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unit_id')
                  ->constrained('sub_sections')
                  ->cascadeOnDelete();

            $table->foreignId('staffing_id')
                  ->constrained('staffings')
                  ->cascadeOnDelete();

            $table->string('title')->nullable();              // تسمية مخصّصة إن احتجت (وإلا نعرض اسم staffing)
            $table->unsignedSmallInteger('quota')->default(1); // عدد الملاك/الشواغر
            $table->boolean('is_manager')->default(false);     // هل وظيفة قيادية داخل الوحدة؟
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_staffings');
    }
};
