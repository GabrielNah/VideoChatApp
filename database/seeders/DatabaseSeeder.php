<?php

namespace Database\Seeders;

use App\Models\ChatMessages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         ChatMessages::create([
             'to'=>2,
             'from'=>1,
             'message'=>Str::random(25)
         ]);
    }
}
