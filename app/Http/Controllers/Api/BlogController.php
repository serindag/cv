<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BlogController extends Controller
{
    public function index()
    {
        $blogs=Blog::with('category')->orderBy('id','desc')->paginate(20);
            return response()->json($blogs);
    }
    public function edit($id)
    {
        $blog=Blog::where('id',$id)->with('category')->first();
        $expiresAt = now()->addHours(3);
        views($blog)
            ->cooldown($expiresAt)
            ->record();
            return response()->json($blog);
    }
    public function store(BlogRequest $request){
       
      
        return $this->saveBlog($request);
    }
    public function update(BlogRequest $request, $id)
    {
        return $this->saveBlog($request,$id);
    }
    private function saveBlog( $request,$id=null)
    {

      

        $blogData = [
            'name' => $request->name,
            'content' =>$request->content,
            'category_id' =>$request->category_id,
            'status' => $request->status ?? 1
        ];
        

        $blog = !empty($id) ? Blog::find($id) : Blog::create($blogData);

        if(empty($blog)) {
            return response()->json(['message' => 'Blog Bulunamadı!'], 404);
        }
        if($request->hasFile('file'))
        {
            $uploadedImages=$this->saveImageUpload($request,$blog);
            $blog->image=$uploadedImages;
          
        }
        $blog->slug=null;
        $blog->update($blogData);

        return response()->json(['message'=>!empty($id) ? 'Başarı ile Güncellendi': 'Başarıyla Blog Oluşturuldu.', 'data'=> $blog], 200);

    }

    private function saveImageUpload($request,$data)
    {

        $imageDelete= $data->image;
        if($imageDelete!=null)
        {
            if(file_exists($imageDelete))
            {
                unlink($imageDelete);
            }
        }
        $image=$request->file('file'); 
         //resime yeni isim verme
            /* $image->getClientOriginalExtension() uzantısını bulur. */
            $imageName=time().'.'.$image->getClientOriginalExtension(); 
            //resmi yükleme
            $image->move('upload/blog',$imageName);
            /* Resim üzerinde işlem yapma */
            $imgManager=new ImageManager(new Driver());
            /* Yüklenen resmi okuma */
            $thumbImage=$imgManager->read('upload/blog/'.$imageName);
            /* resmi boyutlandırma */
            $thumbImage=$thumbImage->resize(200,200);
            /* resmi croplanması */
            //$thumbImage=$thumbImage->cover(200,200);
            /* düzenlenen resimin kaydedilmesi */
            $thumbImage->save(base_path('public/upload/blog/'.$imageName));
            $save_url='upload/blog/'.$imageName;

        return $save_url;
    }
}
