<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $technologies = ['JS', 'HTML', 'VueJS', 'CSS', 'SASS', 'Laravel', 'PHP'];
        Schema::disableForeignKeyConstraints();  //per poter svuotare la tabella devo prima disabilito la key
        Technology::truncate();
        Schema::enableForeignKeyConstraints(); //riabilito la key

        foreach ($technologies as $technology) {

            $newTechnology = new Technology();
            $newTechnology->name = $technology;
            $newTechnology->slug = Str::slug($newTechnology->name);
            $newTechnology->save();
    }
    }
}
