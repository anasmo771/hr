<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');
            $table->foreignId('emp_id')->constrained('employees')->onDelete('cascade')->comment('الربط مع الموظف');
            $table->string('title')->comment('الغرض من التكليف');
            $table->text('note')->nullable()->comment('ملاحظات');
            $table->string('number')->comment('الرقم الاشاري');
            $table->string('task_res')->nullable()->comment('مصدر التكليف'); // <-- مضاف
            $table->date('date')->comment('تاريخ التكليف');
            $table->foreignId('created_id')->nullable()->constrained('users')->onDelete('set null')->comment('الربط مع المستخدم المنشئ');
            $table->timestamp('delete_at')->nullable();
            $table->timestamps();

            // (اختياري) فهارس بسيطة لتحسين الاستعلامات الشائعة
            // $table->index(['emp_id', 'date']);
            // $table->index('number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
