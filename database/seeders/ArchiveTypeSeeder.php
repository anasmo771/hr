<?php

namespace Database\Seeders;

use App\Models\ArchiveType;
use Illuminate\Database\Seeder;

class ArchiveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ArchiveType::insert([
            ['name' => 'البيانات الشخصية'],
            ['name' => 'البيانات الصحية'],
            ['name' => 'البيانات المالية'],
            ['name' => 'الشهادات'],
        ]
    );
    }
}
