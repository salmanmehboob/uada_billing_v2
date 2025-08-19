<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\ClientRepository;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            ModuleSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
            SectorSeeder::class,
            ChargeSeeder::class,
             SizeSeeder::class,
            MonthSeeder::class,
             BankSeeder::class,
            TypeSeeder::class

        ]);

//         $this->runPermissionUpdateCommand();


    }



    private function runPermissionUpdateCommand(): void
    {
        Artisan::call('permission:update');
        $this->command->info('Permissions updated successfully.');
    }

}
