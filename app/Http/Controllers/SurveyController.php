<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Users;
use App\Models\Survey;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Resources\SurveyResource;
use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $user = $request->user();
        return SurveyResource::collection(Survey::where('user_id',$user->id)->paginate(5)); //collection is being used cause of multiple data 

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyRequest $request)
    {
        //
        $data = $request ->validated();

        if(isset($data['image'])){
            $relativePath = $this->saveImage($data['image']); //saveimage is a function scroll down
            $data['image'] = $relativePath;

        }

        $survey =  Survey::Create($data);

       return new SurveyResource($survey);
    }

    /**
     * Display the specified resource.
     * please note , you are using the slug to fetch the details 
     */
    public function show($id, Request $request)
    {
        $user = $request->user();
        
        // Fetch survey by ID
        $survey = Survey::find($id);
    
        if (!$survey) {
            return abort(404, 'Survey not found');
        }
    
        // Check if the authenticated user is the owner of the survey
        if ($user->id !== $survey->user_id) {
            return abort(403, 'Unauthorized action');
        }
    
        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveyRequest $request, $id)
    {
        $survey = Survey::findOrFail($id);
    
        $data = $request->validated();
    
        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
    
            if ($survey->image) {
                $absolutePath = public_path($survey->image);
                File::delete($absolutePath);
            }
        }
    
        $survey->update($data);
    
        return new SurveyResource($survey);
    }
    
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey, Request $request)
    {
        //
        $user = $request->user();
        if($user->id !== $survey->user_id){
            return abort ('403', 'unauthorized action');
        }
        $survey->delete();
        return response('',204);
    }

    public function saveImage($image)
    {
        //check if image is valid base 64 string
        if(preg_match('/^data:image\/(\w+);base64,/', $image, $type)){
            //take out the base64 encoded text without mime type 
            $image = substr($image, strpos($image, ',') +1);

            //getFile Extension
            $type = strtolower($type[1]); //jpg,png,gif

            //check if file is an image 
            if(!in_array($type,['jpg','jpeg','gif','png'])) {
                throw new \Exception('invalid image Type');
            }
            $image = str_replace('', '+', $image);
            $image = base64_decode($image);

            if($image == false){
                throw new Exception('base 64_decode failed');
            }


        }else  {
            throw new  Exception('did not match data url with image data ');
        }

        $dir = 'images/';
        $file = Str::random() . '.' . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;

        if(!File::exists($absolutePath)){
            File::makeDirectory($absolutePath, 0755, true);

        }
        file_put_contents($relativePath, $image);

        return $relativePath;
    }
}
