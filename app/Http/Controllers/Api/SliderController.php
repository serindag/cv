<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SliderController extends Controller
{
    public function index(){
        $sliders=Slider::orderBy('id','desc')->paginate(20);
        return response()->json($sliders);
    }

    public function edit($id)
    {
        $slider=Slider::where('id',$id)->first();
            return response()->json($slider);
    }
    public function store(SliderRequest $request){
       
        return $this->saveSlider($request);
    }
    public function update(SliderRequest $request, $id)
    {
        return $this->saveSlider($request,$id);
    }
    private function saveSlider($request,$id=null)
    {


        $sliderData = [
            'title' =>$request->title,
            'status' => $request->status ?? 1,
            'link'=>$request->link,
            'video_link'=>$request->video_link,
            'content'=>$request->content,
        ];
        

        $slider = !empty($id) ? Slider::find($id) : Slider::create($sliderData);

        if(empty($slider)) {
            return response()->json(['message' => 'Slider Bulunamadı!'], 404);
        }
        if($request->hasFile('file'))
        {
            $uploadedImages=$this->saveImageUpload($request,$slider);
            $slider->image=$uploadedImages;
          
        }

        $slider->slug=null;
        $slider->update($sliderData);

        return response()->json(['message'=>!empty($id) ? 'Başarı ile Güncellendi': 'Başarıyla Slider Oluşturuldu.', 'data'=> $slider], 200);

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
            $image->move('upload/slider',$imageName);
            /* Resim üzerinde işlem yapma */
            $imgManager=new ImageManager(new Driver());
            /* Yüklenen resmi okuma */
            $thumbImage=$imgManager->read('upload/slider/'.$imageName);
            /* resmi boyutlandırma */
            $thumbImage=$thumbImage->resize(200,200);
            /* resmi croplanması */
            //$thumbImage=$thumbImage->cover(200,200);
            /* düzenlenen resimin kaydedilmesi */
            $thumbImage->save(base_path('public/upload/slider/'.$imageName));
            $save_url='upload/slider/'.$imageName;

        return $save_url;
    }
}
