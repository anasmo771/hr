<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');
            $table->foreignId('emp_id')->constrained('employees')->onDelete('cascade')->comment('الربط مع الموظف');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('الربط مع المستخدم المنشئ');
            $table->integer('year')->comment('سنة التقييم');
            $table->unique(['emp_id', 'year'], 'emp_id_year_unique');

            $table->integer('grade')->comment('الدرجة الكلية');

            $table->string('text_grade')->comment('مبررات تعديل درجة كفاية الموظف (الرئيس الاعلي)');
            $table->integer('grade11')->comment('أداء الـواجـب درجة كفاية الموظف (الرئيس المباشر)');
            $table->integer('grade12')->comment('درجة الكفاية المعدلة (الرئيس الاعلي)');

            $table->string('textGrade1')->nullable()->comment('مبررات تعديل درجة كفاية الموظف (الرئيس الاعلي)');
            $table->integer('grade21')->comment('المواظبة علي العمل درجة كفاية الموظف (الرئيس المباشر)');
            $table->integer('grade22')->comment('درجة الكفاية المعدلة (الرئيس الاعلي)');

            $table->string('textGrade2')->nullable()->comment('مبررات تعديل درجة كفاية الموظف (الرئيس الاعلي)');
            $table->integer('grade31')->comment('القدرات والأستعداد الذاتي درجة كفاية الموظف (الرئيس المباشر)');
            $table->integer('grade32')->comment('درجة الكفاية المعدلة (الرئيس الاعلي)');

            $table->string('textGrade3')->nullable()->comment('مبررات تعديل درجة كفاية الموظف (الرئيس الاعلي)');
            $table->integer('grade41')->comment('العلاقات الأنسانية درجة كفاية الموظف (الرئيس المباشر)');
            $table->integer('grade42')->comment('درجة الكفاية المعدلة (الرئيس الاعلي)');

            $table->string('textGrade4')->nullable()->comment('الدرجة لفظيا');
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
        Schema::dropIfExists('feedback');
    }
}
