<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNdbDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ndb__details', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');
            $table->foreignId('emp_id')->constrained('employees')->onDelete('cascade')->comment('الربط مع الموظف');
            $table->string('ndb_transfer_decision')->comment('رقم قرار الندب');
            $table->date('ndb_start')->comment('تاريخ بداية الندب');
            $table->date('ndb_end')->comment('تاريخ نهاية الندب');
            $table->string('dec_source')->comment('مصدر القرار');
            $table->string('dec_constraints')->nullable()->comment('قيود القرار');
            $table->string('ndb_workplace')->comment('مكان العمل');
            $table->boolean('is_ndb')->comment('للتوضيح ندب او إعارة');
            $table->unique(['emp_id', 'ndb_transfer_decision'], 'emp_id_ndb_transfer_decision_unique');
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
        Schema::dropIfExists('ndb__details');
    }
}
