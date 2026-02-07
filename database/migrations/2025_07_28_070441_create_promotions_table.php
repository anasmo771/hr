<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');

            $table->foreignId('emp_id')
                  ->constrained('employees')
                  ->onDelete('cascade')
                  ->comment('الربط مع الموظف');

            $table->string('num')->nullable()->comment('رقم القرار');

            // جديد: نوع الترقية (عادي/استثنائي/تكليف)
            $table->string('type')->nullable()->comment('regular|exceptional|acting');

            // جديد: العلاوات المستهلكة بهذه الترقية (IDs لجدول العلاوات)
            $table->json('consumed_bonus_ids')->nullable()->comment('مُعرفات العلاوات المستهلكة');

            $table->integer('prev_degree')->comment('الدرجة السابقة');
            $table->integer('new_degree')->comment('الدرجة الجديدة');

            // عدم تكرار نفس الدرجة الجديدة لنفس الموظف
            $table->unique(['emp_id', 'new_degree'], 'emp_id_new_degree_unique');

            $table->foreignId('created_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('الربط مع المستخدم المنشئ');

            // تاريخ منح/استحقاق الترقية
            $table->date('date')->comment('تاريخ الاستحقاق/المنح');

            // تاريخ آخر ترقية سابقة (اختياري)
            $table->date('last_date')->nullable()->comment('تاريخ اخر ترقية');

            $table->boolean('accept')->default(true)->comment('حالة القبول');

            $table->timestamp('delete_at')->nullable();
            $table->timestamps();

            // فهارس مساعدة (اختياري)
            // $table->index(['emp_id', 'date'], 'promotions_emp_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}
