<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;

class UserController extends Controller
{
    public function image()
    {   $content=About::first();
        if(file_exists($content->image))
        {
            unlink($content->image);
        }
        dd("başarılı");
       
        $user = About::whereId($content->id)->update([
            'title'=>'Hakkımda',
            'content'=>'Merhaba, benim adım İbrahim. Laravel uzmanı olacağım. İnşallah',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ]);

       
        return view('image');

        
    }
    public function uploadImages(Request $request)
    {
       if($request->file('image')){
            


            $manager=new ImageManager(new Driver());
            $name_gen=hexdec(uniqid()).'.'. $request->file('image')->getClientOriginalExtension();
            $img=$manager->read($request->file('image'));
            $img=$img->resize(370,246);

            $img->toJpeg(80)->save(base_path('public/upload/about/'.$name_gen));
             $save_url='upload/about/'.$name_gen;



             $user = About::insert([
                'title'=>'Hakkımda',
                'content'=>'Merhaba, benim adım İbrahim. Laravel uzmanı olacağım.',
                'image' => $save_url,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);

           return redirect()->route('image');
       }
       else 
       {
        dd("dosya yok");
       }
    }
}
