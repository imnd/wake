<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BouquetTypeSeeder extends Seeder
{
    private int $priority = 0;

    public function run(): void
    {
        DB::table('bouquet_types')->insert([
            $this->getData('White Lilies', 'w-l.png', 15),
            $this->getData('Blue Campanula', 'b-c.png', 15),
            $this->getData('Orange Gerbera', 'o-g.png', 15),
            $this->getData('Lily of the Valley', 'l-o-v.png', 15),

            $this->getData('White Eustomas', 'w-e.png', 20),
            $this->getData('Freesia', 'f.png', 20),
            $this->getData('Peony Barbara', 'p-b.png', 20),
            $this->getData('White Tulips', 'w-t.png', 20),

            $this->getData('White Roses', 'w-r.png', 30),
            $this->getData('Birds of Paradise', 'b-o-p.png', 30),
            $this->getData('Calla Lily', 'c-l.png', 30),
            $this->getData('Orchid Flower', 'o-f.png', 30),
            $this->getData('Rose Peonies', 'r-p.png', 30),
        ]);
    }

    private function getData($name, $image, $price): array
    {
        $this->priority += 10;

        return array_merge(compact('name', 'image', 'price'), [
            'priority' => $this->priority,
        ]) ;
    }
}
