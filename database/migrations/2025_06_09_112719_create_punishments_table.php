<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePunishmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('punishments', function (Blueprint $table) {
            $table->id()->comment('رقم الصف');

            $table->foreignId('emp_id')
                  ->constrained('employees')
                  ->onDelete('cascade')
                  ->comment('الربط مع الموظف');

            $table->string('reason')->comment('سبب العقوبة');

            // أزلنا "إيقاف مؤقت" و"فصل"
            $table->enum('pun_type', [
                'الإنذار',
                'اللوم',
                'لفت نظر',
                'الخصم من المرتب',
                'الحرمان من العلاوات',
                'خفض الدرجة',
                'العزل من الخدمة',
                'بلا عقوبة',
            ])->comment('نوع العقوبة');

            $table->date('pun_date')->comment('تاريخ العقوبة');

            // رجعنا book_num بدل res_num
            $table->string('book_num')->comment('رقم الكتاب');

            $table->string('index')->comment('الرقم الاشاري');
            $table->string('penaltyName')->comment('اسم من اوصى بالعقوبة');

            // حُذفت: start_date و end_date

            $table->text('notes')->nullable()->comment('ملاحظات');

            $table->foreignId('created_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('الربط مع المستخدم المنشئ');

            $table->timestamp('delete_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punishments');
    }
}
