<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');
            $table->enum('type', ['vacation', 'absent', 'course','attention','feedback','promotion','bonus','punishment','task','employee','archive','bank','ndb','retire','resignation','report','settlement','model'])->comment('نوع الملف حسب الجدول');
            $table->unsignedBigInteger('procedure_id')->comment('الربط مع الجدول الخاص بالملف');
            $table->string('path')->comment('مسار الملف');
            $table->string('archive_file')->nullable()->comment('الملف المؤرشف');
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
        Schema::dropIfExists('files');
    }
}
