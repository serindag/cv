<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReferanceRequest;
use App\Models\Referance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ReferanceController extends Controller
{
    public function index(){
        $referances=Referance::orderBy('id','desc')->paginate(20);
        return response()->json($referances);
    }

    public function edit($id)
    {
        $referance=Referance::where('id',$id)->first();
            return response()->json($referance);
    }
    public function store(ReferanceRequest $request){
      
        return $this->saveReferance($request);
    }
    public function update(ReferanceRequest $request, $id)
    {
        return $this->saveReferance($request,$id);
    }
    private function saveReferance($request,$id=null)
    {

        $referanceData = [
            'name' =>$request->name,
            'link'=>$request->link ?? '#',
            'status' => $request->status ?? 1
        ];
        

        $referance = !empty($id) ? Referance::find($id) : Referance::create($referanceData);

        if(empty($referance)) {
            return response()->json(['message' => 'Referans Bulunamadı!'], 404);
        }
        if($request->hasFile('file'))
        {
            $uploadedImages=$this->saveImageUpload($request,$referance);
            $referance->image=$uploadedImages;
          
        }

        
        $referance->update($referanceData);

        return response()->json(['message'=>!empty($id) ? 'Başarı ile Güncellendi': 'Başarıyla Referans Oluşturuldu.', 'data'=> $referance], 200);

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
            $image->move('upload/referance',$imageName);
            /* Resim üzerinde işlem yapma */
            $imgManager=new ImageManager(new Driver());
            /* Yüklenen resmi okuma */
            $thumbImage=$imgManager->read('upload/referance/'.$imageName);
            /* resmi boyutlandırma */
            $thumbImage=$thumbImage->resize(200,200);
            /* resmi croplanması */
            //$thumbImage=$thumbImage->cover(200,200);
            /* düzenlenen resimin kaydedilmesi */
            $thumbImage->save(base_path('public/upload/referance/'.$imageName));
            $save_url='upload/referance/'.$imageName;

        return $save_url;
    }
}
