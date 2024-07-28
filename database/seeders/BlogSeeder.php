<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Blog::create([
            
            'name'=>'Merhaba Dünya',
            'content'=>'Bu yer blog yazısı',
            'category_id'=>1,
        ]);
    }
    
}
