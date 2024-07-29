<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialMediaRequest;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SocialMediaController extends Controller
{
    public function index(){
        $socialMedias=SocialMedia::orderBy('order_no')->paginate(20);
        return response()->json($socialMedias);
    }
    public function order(Request $request){
       

        foreach($request->order as $key=>$id)
        {
            SocialMedia::where('id',$id)->update(['order_no'=>$key]);
            
        }
        return response()->json(['message' => 'Sosyal Medya Sırası Güncellendi!','data'=> SocialMedia::orderBy('order_no')->get()], 200);
    }

    public function edit($id)
    {
        $socialMedia=SocialMedia::where('id',$id)->first();
            return response()->json($socialMedia);
    }
    public function store(SocialMediaRequest $request){
       
        return $this->saveSocialMedia($request);
    }
    public function update(SocialMediaRequest $request, $id)
    {
        return $this->saveSocialMedia($request,$id);
    }
    private function saveSocialMedia($request,$id=null)
    {
       
        $socialMediaData = [
            'title' =>$request->title,
            'link'=>$request->link,
            'icon'=>$request->icon,
            'status' => $request->status ?? 1,
        ];
        

        $socialMedia = !empty($id) ? SocialMedia::find($id) : SocialMedia::create($socialMediaData);

        
        if(empty($socialMedia)) {
            return response()->json(['message' => 'Sosyal Medya Bulunamadı!'], 404);
        }
        
        $socialMedia->update($socialMediaData);

        return response()->json(['message'=>!empty($id) ? 'Başarı ile Güncellendi': 'Başarıyla Sosyal Medya Oluşturuldu.', 'data'=> $socialMedia], 200);

    }

    
}
