<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeederV2 extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Électronique',
            'Mode & Vêtements',
            'Maison & Jardin',
            'Véhicules',
            'Loisirs',
            'Immobilier',
            'Services',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
        }
    }
}
