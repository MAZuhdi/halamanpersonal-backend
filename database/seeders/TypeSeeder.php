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
            'name' => 'Education'
        ]);
        Type::create([
            'name' => 'Portfolio'
        ]);
        Type::create([
            'name' => 'Completion'
        ]);
        Type::create([
            'name' => 'Experience'
        ]);
    }
}
