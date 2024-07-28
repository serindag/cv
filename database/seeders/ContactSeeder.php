<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contact::create([
            'name'=>'ibrahim1',
            'email'=>'iserindag@msn.com',
            'phone'=>'0415888582',
            'subject'=>'ne var1',
            'body'=>'iyi senden1',
            'status'=>1,
            'ip'=>'deneme1',
        ]);
    }
}
