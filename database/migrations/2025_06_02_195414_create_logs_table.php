<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->comment('الربط مع المستخدم');
            $table->integer('type')->comment('نوع الحركة');
            $table->foreignId('emp_id')->nullable()->constrained('employees')->onDelete('cascade')->comment('الربط مع الموظف');
            $table->string('title')->comment('العنوان');
            $table->text('log')->comment('تفاصيل');
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
        Schema::dropIfExists('logs');
    }
}
