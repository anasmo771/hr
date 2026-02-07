<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');
            $table->string('name')->comment('الجهة التابعة للارشيف');
            $table->foreignId('type_id')->nullable()->constrained('archive_types')->onDelete('set null')->comment('الربط مع نوع الارشيف');
            $table->foreignId('emp_id')->constrained('employees')->onDelete('cascade')->comment('الربط مع الموظف');
            $table->string('desc')->nullable()->comment('تفاصيل');
            $table->date('date')->nullable()->comment('التاريخ');
            // $table->boolean('check')->default(0)->comment('');
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
        Schema::dropIfExists('archives');
    }
}
