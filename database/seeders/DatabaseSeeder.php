<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\TestDataSeeder;
use Database\Seeders\PlanosSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\EmpresaSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
        ]);
    }
}
