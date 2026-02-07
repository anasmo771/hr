<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vacations', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');

            $table->foreignId('emp_id')
                  ->constrained('employees')
                  ->onDelete('cascade')
                  ->comment('الربط مع الموظف');

            // محذوف: years / months
            $table->integer('days')->nullable()->comment('عدد الايام');

            $table->string('reason')->nullable()->comment('سبب الاجازة');
            $table->boolean('companion')->default(false)->comment('مرافق او لا');
            $table->string('type')->comment('نوع الاجازة');
            $table->boolean('accept')->default(false)->comment('حالة القبول');

            $table->foreignId('created_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('الربط مع المستخدم المنشئ');

            $table->date('start_date')->comment('تاريخ بداية الاجازة');
            $table->date('end_date')->comment('تاريخ نهاية الاجازة');

            // لدعم "الرجوع من الإجازة"
            $table->date('actual_end_date')->nullable()->comment('تاريخ نهاية الأجازة الفعلي');

            $table->timestamp('delete_at')->nullable();
            $table->timestamps();

            // فهارس مفيدة للاستعلامات الشائعة
            $table->index(['emp_id', 'accept'], 'vacations_emp_accept_idx');
            $table->index(['emp_id', 'start_date'], 'vacations_emp_start_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacations');
    }
}
