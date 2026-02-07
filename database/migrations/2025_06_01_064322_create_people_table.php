<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');
            $table->string('name')->comment('اسم الموظف');
            $table->string('N_id',13)->nullable()->comment('الرقم الوطني');
            $table->string('non_citizen_ref_no')->nullable()->comment('الرقم الإداري أو الإقامة أو الجواز لغير الليبيين');
            $table->date('birth_date')->comment('تاريخ الميلاد');
            $table->string('country')->comment('الجنسية');
            $table->string('city')->comment('المدينة');
            $table->string('street_address')->nullable()->comment('المنطقة');
            $table->enum('gender', ['ذكر', 'انثي']);
            $table->enum('marital_status', ['أعزب', 'متزوج', 'مطلق','أرمل']);
            $table->string('email')->nullable()->comment('البريد الالكتروني');
            $table->string('phone')->nullable()->comment('رقم الهاتف');
            $table->string('image')->nullable()->comment('صورة الموظف');
            $table->boolean('enabled')->default(true)->comment('حالة الموظف');
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
        Schema::dropIfExists('people');
    }
}
