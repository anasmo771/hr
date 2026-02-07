<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionTableSeeder::class);
        $this->call(SpecialtySeeder::class);
        $this->call(AdminUserSeeder::class);
        // $this->call(SubsectionSeeder::class);
        $this->call([
            PeopleSeeder::class,
            EmployeesSeeder::class,]);
        $this->call(ArchiveTypeSeeder::class);
        $this->call(AttendanceSeeder::class);
        $this->call(CollegeOrgSeeder::class);
    }
}
