<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $modules = array (
            'Users',
            'Roles',
            'Warehouse',
            'Category',
            'Brand',
            'Supplier',

        );

        foreach ($modules as $row) {
            Module::firstOrCreate([
                'name' => $row,
            ]);
        }
    }
}
