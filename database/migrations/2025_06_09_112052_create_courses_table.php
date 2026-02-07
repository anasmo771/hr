<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table)
        {
            $table->id()->comment('رقم الصف');
            $table->string('name_course')->comment('اسم الدورة');
            $table->string('course_type')->comment('نوع الدورة');
            $table->string('agency')->comment('الجهة المنفدة');
            $table->string('place')->comment('مكان الدورة');
            $table->string('number')->comment('رقم القرار / رقم الكتاب');
            $table->date('from_date')->comment('تاريخ البداية');
            $table->date('to_date')->comment('تاريخ النهاية');
            $table->string('notes')->nullable()->comment('ملاحظات');
            $table->foreignId('created_id')->nullable()->constrained('users')->onDelete('cascade')->comment('الربط مع المستخدم المنشئ');
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
        Schema::dropIfExists('courses');
    }
}
