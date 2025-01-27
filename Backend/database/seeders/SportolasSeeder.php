<?php

namespace Database\Seeders;

use App\Models\Diak;
use App\Models\Sport;
use App\Models\Sportolas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SportolasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['diakokId' => 2, 'sportokId' => 2],
            ['diakokId' => 2, 'sportokId' => 5],
            ['diakokId' => 4, 'sportokId' => 1],
            ['diakokId' => 6, 'sportokId' => 1],
            ['diakokId' => 6, 'sportokId' => 4],
            ['diakokId' => 6, 'sportokId' => 5],
        ];

        // if (Sportolas::count() === 0) {
        //     Sportolas::factory()->createMany($data);
        // }

        if (Sportolas::count() === 0) {
            for ($i=0; $i < 10; $i++) { 
                # code...
                do {
                    $diakokId = Diak::inRandomOrder()->first()->id;
                    $sportokId = Sport::inRandomOrder()->first()->id;
                } while (Sportolas::where('diakokId', $diakokId)->where('sportokId', $sportokId)->exists());

                Sportolas::create([
                    'diakokId' => $diakokId,
                    'sportokId' => $sportokId,
                ]);
            }
            // Sportolas::factory(100)->create()->sync();
        }

        // if (env('APP_ENV') === 'testing') {
        //     if (Sportolas::count() === 0) {
        //         for ($i = 0; $i < 100; $i++) {
        //             do {
        //                 $diakokId = Diak::inRandomOrder()->first()->id;
        //                 $sportokId = Sport::inRandomOrder()->first()->id;
        //             } while (Sportolas::where('diakokId', $diakokId)->where('sportokId', $sportokId)->exists());

        //             Sportolas::create([
        //                 'diakokId' => $diakokId,
        //                 'sportokId' => $sportokId,
        //             ]);
        //         }
        //     }
        // } else {
        //     if (Sportolas::count() === 0) {
        //         Sportolas::factory()->createMany($data);
        //     }
        // }
    }
}