<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Specialty::create([
            'name' => 'أدارة أعمال',
        ]);
        Specialty::create([
            'name' => 'محاسبة',
        ]);
        Specialty::create([
            'name' => 'علم الحاسوب',
        ]);
        Specialty::create([
            'name' => 'هندسة',
        ]);
        Specialty::create([
            'name' => 'طب',
        ]);
        Specialty::create([
            'name' => 'الصيدلة',
        ]);
        Specialty::create([
            'name' => 'الكيمياء',
        ]);
        Specialty::create([
            'name' => 'الفيزياء',
        ]);
        Specialty::create([
            'name' => 'الأحياء',
        ]);
        Specialty::create([
            'name' => 'قانون',
        ]);
                Specialty::create([
            'name' => 'سياسة',
        ]);        Specialty::create([
            'name' => 'الرياضيات',
        ]);
        Specialty::create([
            'name' => 'تقنية معلومات',
        ]);
        Specialty::create([
            'name' => 'الهندسة الطبية والحيوية',
        ]);
        Specialty::create([
            'name' => 'هندسة الطيران',
        ]);
        Specialty::create([
            'name' => 'الهندسة النووية',
        ]);
        Specialty::create([
            'name' => 'هندسة الإلكترونيات',
        ]);
        Specialty::create([
            'name' => 'الهندسة الصناعية',
        ]);
        Specialty::create([
            'name' => 'الهندسة الكهربائية',
        ]);
        Specialty::create([
            'name' => 'الهندسة الكيميائية',
        ]);
        Specialty::create([
            'name' => 'الهندسة المدنية',
        ]);
        Specialty::create([
            'name' => 'الهندسة الميكانيكية',
        ]);
        Specialty::create([
            'name' => 'هندسة الحاسوب',
        ]);
        Specialty::create([
            'name' => 'هندسة العمارة',
        ]);
        Specialty::create([
            'name' => 'هندسة النفطية',
        ]);
        Specialty::create([
            'name' => 'التسويق',
        ]);
        Specialty::create([
            'name' => 'الفنون ',
        ]);
        Specialty::create([
            'name' => 'اللغات',
        ]);
    }
}
