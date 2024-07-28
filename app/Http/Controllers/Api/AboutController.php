<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutRequest;
use App\Models\About;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;


class AboutController extends Controller
{
    public function index()
    {
        return About::firstOrFail();
    }
    public function update(AboutRequest $request)
    {
        $id = About::first()->id;
        $imageDelete = About::first()->image;

        if (file_exists($imageDelete)) {
            unlink($imageDelete);
        }
        $image = $request->file('file');
        if (isset($image)) {
            //resime yeni isim verme
            /* $image->getClientOriginalExtension() uzantısını bulur. */
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            //resmi yükleme
            $image->move('upload/about', $imageName);
            /* Resim üzerinde işlem yapma */
            $imgManager = new ImageManager(new Driver());
            /* Yüklenen resmi okuma */
            $thumbImage = $imgManager->read('upload/about/' . $imageName);
            /* resmi boyutlandırma */
            $thumbImage = $thumbImage->resize(200, 200);
            /* resmi croplanması */
            //$thumbImage=$thumbImage->cover(200,200);
            /* düzenlenen resimin kaydedilmesi */
            $thumbImage->save(base_path('public/upload/about/' . $imageName));
            $save_url = 'upload/about/' . $imageName;

            $about = About::whereId($id)->update([
                'title' => $request->title,
                'image' => $save_url,
                'content' => $request->content,
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
        } else {
            $about = About::whereId($id)->update([
                'title' => $request->title,
                'content' => $request->content,
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
        }

        $content = About::first();
        return response()->json(['message' => 'Başarı ile güncellendi', 'data' => $content], 200);
    }
}
