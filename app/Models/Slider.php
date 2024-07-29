<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Slider extends Model
{
    use HasFactory,Sluggable;
    protected $fillable=[
        'title',
        'content',
        'image',
        'link',
        'video_link',
        'status'
    ];
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
