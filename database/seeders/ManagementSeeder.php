<?php

namespace Database\Seeders;

use App\Models\Management;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ManagementSeeder extends Seeder
{
    public function run(): void
    {
        $json = File::get('database/data/management.json');
        $data = json_decode($json, true);
        foreach ($data as $obj) {
            Management::updateOrCreate([
                'management_name' => $obj['management_name']
            ]);
        }
    }
}
