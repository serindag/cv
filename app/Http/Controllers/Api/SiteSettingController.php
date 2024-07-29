<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiteSettingRequest;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class SiteSettingController extends Controller
{
    public function index(){
        $siteSettings=SiteSetting::paginate(20);
        return response()->json($siteSettings);
    }
   

    public function edit($id)
    {
        $siteSetting=SiteSetting::where('id',$id)->first();
            return response()->json($siteSetting);
    }
    public function store(SiteSettingRequest $request){
       
       
        return $this->saveSiteSetting($request);
    }
    public function update(SiteSettingRequest $request, $id)
    {
        return $this->saveSiteSetting($request,$id);
    }
    private function saveSiteSetting($request,$id=null)
    {
       
       
        $siteSettingData = [
            'setting_key' =>$request->setting_key,
            'setting_value'=>$request->setting_value,
            'setting_type'=>$request->setting_type,
           
        ];
        

        $siteSetting = !empty($id) ? SiteSetting::find($id) : SiteSetting::create($siteSettingData);

        
        if(empty($siteSetting)) {
            return response()->json(['message' => 'Site Ayar Bulunamadı!'], 404);
        }
        
        $siteSetting->update($siteSettingData);

        return response()->json(['message'=>!empty($id) ? 'Başarı ile Güncellendi': 'Başarıyla Site Ayar Oluşturuldu.', 'data'=> $siteSetting], 200);

    }
}
