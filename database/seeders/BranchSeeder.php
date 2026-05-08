<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::updateOrCreate(['name' => 'Laudendang'], ['address' => 'Jl. Laudendang No. 1']);
        Branch::updateOrCreate(['name' => 'Tuasan'],     ['address' => 'Jl. Tuasan No. 1']);
    }
}
