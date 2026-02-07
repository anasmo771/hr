<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');

            // العلاقات
            $table->foreignId('emp_id')
                  ->constrained('employees')
                  ->onDelete('cascade')
                  ->comment('الربط مع الموظف');

            // بيانات العلاوة
            $table->integer('bonus_num')->comment('رقم العلاوة');
            $table->date('bonus_date')->nullable()->comment('تاريخ العلاوة');
            $table->integer('degree')->comment('الدرجة');

            // قيود فريدة
            $table->unique(['emp_id', 'bonus_num', 'degree'], 'emp_id_bonus_num_degree_unique');

            // معلومات إنشائية
            $table->foreignId('created_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('الربط مع المستخدم المنشئ');

            // تواريخ الاستحقاق
            $table->date('date')->comment('تاريخ الاستحقاق');
            $table->date('last_date')->nullable()->comment('تاريخ اخر علاوة');

            // حقول إضافية
            $table->string('estimate')->nullable()->comment('التقدير');
            $table->boolean('accept')->default(true)->comment('حالة القبول');

            // حذف منطقي يدوي + الطوابع
            $table->timestamp('delete_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonuses');
    }
};
