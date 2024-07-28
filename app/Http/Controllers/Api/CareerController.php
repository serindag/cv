<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CareerRequest;
use App\Models\Career;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CareerController extends Controller
{
    public function index(){
        $careers=Career::all();
        return response()->json($careers);
    }

    public function store(CareerRequest $request)
    {
      
        return $this->saveCareer($request);
    }
    public function update(CareerRequest $request,$id)
    {
        
        return $this->saveCareer($request,$id);
    }
    private function saveCareer( $request,$id=null)
    {


       $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
        $endDate = isset($request->end_date) ? Carbon::parse($request->end_date)->format('Y-m-d') : null;

       
       
        $careerData = [
            'title' => $request->title,
            'company' => $request->company,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'description' =>  $request->description,
            'status' => $request->status ?? !empty($endDate) ? 0 : 1
        ];
        

        $career = !empty($id) ? Career::find($id) : Career::create($careerData);

        if(empty($career)) {
            return response()->json(['message' => 'Kariyer Bulunamadı!'], 404);
        }
        
        

        if($request->hasFile('file'))
        {
            $uploadedImages=$this->saveImageUpload($request,$career);
            $career->image=$uploadedImages;
          
        }

        $career->update($careerData);
        return response()->json(['message'=>!empty($id) ? 'Başarı ile Güncellendi': 'Başarıyla Kariyer Oluşturuldu.', 'data'=> $career], 200);

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
            $image->move('upload/career',$imageName);
            /* Resim üzerinde işlem yapma */
            $imgManager=new ImageManager(new Driver());
            /* Yüklenen resmi okuma */
            $thumbImage=$imgManager->read('upload/career/'.$imageName);
            /* resmi boyutlandırma */
            $thumbImage=$thumbImage->resize(200,200);
            /* resmi croplanması */
            //$thumbImage=$thumbImage->cover(200,200);
            /* düzenlenen resimin kaydedilmesi */
            $thumbImage->save(base_path('public/upload/career/'.$imageName));
            $save_url='upload/career/'.$imageName;

        return $save_url;
    }

}
