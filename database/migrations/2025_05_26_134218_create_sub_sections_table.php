<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_sections', function (Blueprint $table) {
            $table->id();

            // اسم الإدارة / القسم
            $table->string('name')->comment('اسم الادارة او القسم التابع لها');

            // نوع الوحدة (كلية، مكتب، إدارة، قسم، وحدة...)
            $table->string('unit_type')
                  ->default('unit')
                  ->comment('college|office|department|section|unit');

            // ترتيب العرض
            $table->unsignedInteger('sort_order')
                  ->default(0)
                  ->comment('ترتيب العرض');

            // كود اختياري للوحدة
            $table->string('code')
                  ->nullable()
                  ->comment('كود الوحدة إن وُجد');

            // علاقة الأب (قسم تابع لإدارة) — مرجع ذاتي
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('sub_sections')
                  ->onDelete('cascade')
                  ->comment('الربط مع الادارة التابع لها القسم');

            // شعار القسم
            $table->string('logo')
                  ->default('logo.png')
                  ->comment('صورة شعار القسم');

            // حذف منطقي يدوي + الطوابع
            $table->timestamp('delete_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_sections');
    }
};
