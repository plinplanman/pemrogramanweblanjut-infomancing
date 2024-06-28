<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class lokasisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lokasis')->insert([
            'nama_lokasi' => 'Pemancingan Bumdes Gemilang Mranak',
            'slug' => Str::slug('Tempat Mancing'),
            'koordinat' => '-6.88478001853341, 110.63500313450399',
            'gambar_lokasi'=>'',
            'deskripsi' => 'Tempat Mancing dengan suasana Tradisional dan Pedesaan.',
            'harga'=>'10000',
            'user_id'=>'1'

            ]);
    }
}
