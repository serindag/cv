<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationRequest;
use App\Models\Education;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class EducationController extends Controller
{
    public function index(){
        $educations=Education::all();
        return response()->json($educations);
    }

    public function store(EducationRequest $request)
    {
      
        return $this->saveEducation($request);
    }
    public function update(EducationRequest $request,$id)
    {
        
        return $this->saveEducation($request,$id);
    }
    private function saveEducation( $request,$id=null)
    {


       $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
        $endDate = isset($request->end_date) ? Carbon::parse($request->end_date)->format('Y-m-d') : null;

       
       
        $educationData = [
            'title' => $request->title,
            'education_title' => $request->education_title,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'description' =>  $request->description,
            'status' => $request->status ?? !empty($endDate) ? 0 : 1
        ];
        

        $education = !empty($id) ? Education::find($id) : Education::create($educationData);

        if(empty($education)) {
            return response()->json(['message' => 'Eğitim Bulunamadı!'], 404);
        }
        
        

        if($request->hasFile('file'))
        {
            $uploadedImages=$this->saveImageUpload($request,$education);
            $education->image=$uploadedImages;
          
        }

        $education->update($educationData);
        return response()->json(['message'=>!empty($id) ? 'Başarı ile Güncellendi': 'Başarıyla Eğitim Oluşturuldu.', 'data'=> $education], 200);

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
            $image->move('upload/education',$imageName);
            /* Resim üzerinde işlem yapma */
            $imgManager=new ImageManager(new Driver());
            /* Yüklenen resmi okuma */
            $thumbImage=$imgManager->read('upload/education/'.$imageName);
            /* resmi boyutlandırma */
            $thumbImage=$thumbImage->resize(200,200);
            /* resmi croplanması */
            //$thumbImage=$thumbImage->cover(200,200);
            /* düzenlenen resimin kaydedilmesi */
            $thumbImage->save(base_path('public/upload/education/'.$imageName));
            $save_url='upload/education/'.$imageName;

        return $save_url;
    }
}
