<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');
            $table->foreignId('receive_id')->constrained('users')->onDelete('cascade')->comment('الربط مع المستخدم (مستلم الاشعار)');
            $table->string('title')->comment('عنوان الاشعار');
            $table->text('desc')->nullable()->comment('شرح عن الاشعار');
            $table->boolean('read')->default(0)->comment('هل تمت روية الاشعار');
            $table->boolean('show')->default(0)->comment('هل تم عرض الاشعار');
            $table->integer('num')->comment('رقم الاشعار');
            $table->unsignedTinyInteger('priority')->comment('اهمية الاشعار');
            $table->foreignId('created_id')->nullable()->constrained('users')->onDelete('set null')->comment('الربط مع المستخدم المنشئ للاشعار');
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
        Schema::dropIfExists('notifications');
    }
}
