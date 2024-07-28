<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TagController extends Controller
{
    public function index(){
        $tags=Tag::orderBy('id','desc')->paginate(20);
        return response()->json($tags);
    }

    public function edit($id)
    {
        $tag=Tag::where('id',$id)->first();
            return response()->json($tag);
    }
    public function store(TagRequest $request){
       
        return $this->saveTag($request);
    }
    public function update(TagRequest $request, $id)
    {
        return $this->saveTag($request,$id);
    }
    private function saveTag($request,$id=null)
    {

      
        

       
      

       

        $tagData = [
            'name' =>$request->name,
            'status' => $request->status ?? 1
        ];
        

        $tag = !empty($id) ? Tag::find($id) : Tag::create($tagData);

        if(empty($tag)) {
            return response()->json(['message' => 'Etiket Bulunamadı!'], 404);
        }
        if($request->hasFile('file'))
        {
            $uploadedImages=$this->saveImageUpload($request,$tag);
            $tag->image=$uploadedImages;
          
        }

        $tag->slug=null;
        $tag->update($tagData);

        return response()->json(['message'=>!empty($id) ? 'Başarı ile Güncellendi': 'Başarıyla Etiket Oluşturuldu.', 'data'=> $tag], 200);

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
            $image->move('upload/tag',$imageName);
            /* Resim üzerinde işlem yapma */
            $imgManager=new ImageManager(new Driver());
            /* Yüklenen resmi okuma */
            $thumbImage=$imgManager->read('upload/tag/'.$imageName);
            /* resmi boyutlandırma */
            $thumbImage=$thumbImage->resize(200,200);
            /* resmi croplanması */
            //$thumbImage=$thumbImage->cover(200,200);
            /* düzenlenen resimin kaydedilmesi */
            $thumbImage->save(base_path('public/upload/tag/'.$imageName));
            $save_url='upload/tag/'.$imageName;

        return $save_url;
    }
}
