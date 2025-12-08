<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
     /**
      * Run the database seeds.
      */
     public function run(): void
     {
          Type::create([
               'name' => [
                    'en' => 'Capital',
                    'km' => 'រាជធានី'
               ]
          ]);

          Type::create([
               'name' => [
                    'en' => 'Province',
                    'km' => 'ខេត្ត'
               ]
          ]);

          Type::create([
               'name' => [
                    'en' => 'Municipality',
                    'km' => 'ក្រុង'
               ]
          ]);
          Type::create([
               'name' => [
                    'en' => 'District',
                    'km' => 'ស្រុក'
               ]
          ]);
          Type::create([
               'name' => [
                    'en' => 'Khan',
                    'km' => 'ខណ្ឌ'
               ]
          ]);
          Type::create([
               'name' => [
                    'en' => 'Commune',
                    'km' => 'ឃុំ'
               ]
          ]);
          Type::create([
               'name' => [
                    'en' => 'Sangkat',
                    'km' => 'សង្កាត់'
               ]
          ]);
          Type::create([
               'name' => [
                    'en' => 'Village',
                    'km' => 'ភូមិ'
               ]
          ]);
     }
}
