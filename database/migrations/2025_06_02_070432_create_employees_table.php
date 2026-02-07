<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');

            // روابط أساسية
            $table->foreignId('person_id')
                  ->constrained('people')
                  ->onDelete('cascade')
                  ->comment('الربط مع جدول البيانات الشخصية');

            $table->foreignId('unit_staffing_id')
                  ->nullable()
                  ->constrained('unit_staffings')
                  ->onDelete('set null')
                  ->comment('الربط مع الوحدة الوظيفية (المقعد)');

            // بيانات التوظيف
            $table->enum('type', ['عقد', 'تعيين', 'إعارة','ندب'])->comment('نوع التوظبف');
            $table->unsignedInteger('degree')->nullable()->comment('الدرجة الوظيفية');
            $table->date('degree_date')->nullable()->comment('تاريخ الحصول علي الدرجة');
            $table->enum('status', ['يعمل', 'مفصول', 'مستقيل','متقاعد','موقوف','منقطع','منتقل'])->comment('حالة الموظف');

            $table->unsignedSmallInteger('vacation_balance_days')
                  ->default(0)
                  ->comment('رصيد الإجازات بالأيام');

            $table->date('due')->nullable()->comment('تاريخ المؤهل العلمي');
            $table->string('qualification')->nullable()->comment('المؤهل العلمي في القرار');
            $table->date('start_date')->comment('تاريخ المباشرة');
            $table->string('res_num')->nullable()->comment('رقم القرار');

            $table->foreignId('sub_section_id')
                  ->nullable()
                  ->constrained('sub_sections')
                  ->onDelete('set null')
                  ->comment('الربط مع القسم التابع له');

            $table->foreignId('specialty_id')
                  ->nullable()
                  ->constrained('specialties')
                  ->onDelete('set null')
                  ->comment('الربط مع التخصصات');

            $table->foreignId('created_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('الربط مع المستخدم الدي ادخل الموظف');

            $table->date('startout_data')->nullable()->comment('تاريخ الأستقالة');
            $table->string('archive_char')->nullable()->comment('الحرف المؤرشف للأستقالة');
            $table->integer('archive_num')->nullable()->comment('الرقم المؤرشف للأستقالة');

            $table->date('futurepromotion')->nullable()->comment('تاريخ استحقاق الترقية القادمة ');
            $table->date('futureBonus')->nullable()->comment('تاريخ استحقاق العلاوة القادمة ');

            $table->timestamp('delete_at')->nullable();
            $table->timestamps();

            // فهارس مساعدة
            $table->index(['sub_section_id', 'unit_staffing_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
