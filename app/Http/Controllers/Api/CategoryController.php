<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class CategoryController extends Controller
{
    public function index()
    {
        $categories=Category::orderBy('id','desc')->paginate(20);
            return response()->json($categories);
    }
    public function store(CategoryRequest $request){
       
        return $this->saveCategory($request);
    }
    public function update(CategoryRequest $request, $id)
    {
        return $this->saveCategory($request,$id);
    }
    private function saveCategory( $request,$id=null)
    {
        $categoryData = [
            'name' => $request->name,
            'status' => $request->status ?? 1
        ];
        

        $category = !empty($id) ? Category::find($id) : Category::create($categoryData);

        if(empty($category)) {
            return response()->json(['message' => 'Kategori Bulunamadı!'], 404);
        }
        if($request->hasFile('file'))
        {
            $uploadedImages=$this->saveImageUpload($request,$category);
            $category->image=$uploadedImages;
          
        }
        $category->slug=null;
        $category->update($categoryData);

        return response()->json(['message'=>!empty($id) ? 'Başarı ile Güncellendi': 'Başarıyla Kategori Oluşturuldu.', 'data'=> $category], 200);

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
            $image->move('upload/category',$imageName);
            /* Resim üzerinde işlem yapma */
            $imgManager=new ImageManager(new Driver());
            /* Yüklenen resmi okuma */
            $thumbImage=$imgManager->read('upload/category/'.$imageName);
            /* resmi boyutlandırma */
            $thumbImage=$thumbImage->resize(200,200);
            /* resmi croplanması */
            //$thumbImage=$thumbImage->cover(200,200);
            /* düzenlenen resimin kaydedilmesi */
            $thumbImage->save(base_path('public/upload/category/'.$imageName));
            $save_url='upload/category/'.$imageName;

        return $save_url;
    }
}
