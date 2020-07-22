<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'Légumes bulbes',
            'slug' => 'Legumes-bulbes'
        ]);

        Category::create([
            'name' => 'Légumes feuilles',
            'slug' => 'Legumes-feuilles'
        ]);

        Category::create([
            'name' => 'Légumes fleurs',
            'slug' => 'Legumes-fleurs'
        ]);

        Category::create([
            'name' => 'Légumes fruits',
            'slug' => 'Legumes-fruits'
        ]);

        Category::create([
            'name' => 'Autres légumes',
            'slug' => 'Autres-legumes'
        ]);
    }
}
