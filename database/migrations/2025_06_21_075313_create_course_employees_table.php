<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseEmployeesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_employees', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');
            $table->foreignId('emp_id')->constrained('employees')->onDelete('cascade')->comment('الربط مع الموظف');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade')->comment('الربط مع الدورة');
            $table->string('result')->nullable()->comment('النتيجة');
            $table->string('notes')->nullable()->comment('ملاحظات');
            $table->timestamp('delete_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_employees');
    }
}
