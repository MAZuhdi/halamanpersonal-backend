<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::create([
            'type_name' => 'Education'
        ]);
        Type::create([
            'type_name' => 'Portfolio'
        ]);
        Type::create([
            'type_name' => 'Completion'
        ]);
        Type::create([
            'type_name' => 'Experience'
        ]);
    }
}
