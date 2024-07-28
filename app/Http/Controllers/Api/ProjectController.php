<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects=Project::with('category')->orderBy('id','desc')->paginate(20);
            return response()->json($projects);
    }
    public function edit($id)
    {
        $project=Project::where('id',$id)->with('category')->first();
            return response()->json($project);
    }
    public function store(ProjectRequest $request){
       
      
        return $this->saveProject($request);
    }
    public function update(ProjectRequest $request, $id)
    {
        return $this->saveProject($request,$id);
    }
    private function saveProject( $request,$id=null)
    {

      

        $projectData = [
            'name' => $request->name,
            'content' =>$request->content,
            'category_id' =>$request->category_id,
            'finish_time'=>Carbon::parse($request->finish_time)->format('Y-m-d'),
            'link'=>$request->link,
            'tags'=>$request->tags,
            'status' => $request->status ?? 1
        ];
        

        $project = !empty($id) ? Project::find($id) : Project::create($projectData);

        if(empty($project)) {
            return response()->json(['message' => 'Project Bulunamadı!'], 404);
        }
        if($request->hasFile('file'))
        {
            $uploadedImages=$this->saveImageUpload($request,$project);
            $project->image=$uploadedImages;
          
        }
        $project->slug=null;
        $project->update($projectData);

        return response()->json(['message'=>!empty($id) ? 'Başarı ile Güncellendi': 'Başarıyla Project Oluşturuldu.', 'data'=> $project], 200);

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
            $image->move('upload/project',$imageName);
            /* Resim üzerinde işlem yapma */
            $imgManager=new ImageManager(new Driver());
            /* Yüklenen resmi okuma */
            $thumbImage=$imgManager->read('upload/project/'.$imageName);
            /* resmi boyutlandırma */
            $thumbImage=$thumbImage->resize(200,200);
            /* resmi croplanması */
            //$thumbImage=$thumbImage->cover(200,200);
            /* düzenlenen resimin kaydedilmesi */
            $thumbImage->save(base_path('public/upload/project/'.$imageName));
            $save_url='upload/project/'.$imageName;

        return $save_url;
    }
}
