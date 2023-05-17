<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Users;
use App\Models\Survey;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SurveyQuestion;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use App\Http\Resources\SurveyResource;
use App\Http\Requests\StoreSurveyRequest;
use Illuminate\Support\Facades\Validator;
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
        
       // create a new Question
        foreach($data['questions'] as $question){

            $question['survey_id'] = $survey->id;
            $this->createQuestion($question);
        }

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

        //get ids as plain array of existing questions
        $existingIds = $survey->questions()->pluck('id')->toArray();
        //get ids as plain array to new questions
        $newIds = Arr::pluck($data['questions'],'id');
        //find question to delete 
        $toDelete = array_diff($existingIds,$newIds);

        //find questions to add 
        $toAdd = array_diff($newIds,$existingIds);

        //delete question by $toDelete Array 
        SurveyQuestion::destroy($toDelete);

        //create a new Question 
        foreach($data['questions'] as $question){
            if(in_array($question['id'], $toAdd)){
                $question['survey_id'] = $survey->id;
                $this->createQuestion($question);

            }
        }


        //update existing questions
        $questionMap = collect($data['questions'])->keyBy('id');
        foreach ($survey->questions as $question){
            if(isset($questionMap[$question->id])) {
                $this->updateQuestion($question, $questionMap[$question->id]);
            }
        }
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

        if ($survey->image) {
            $absolutePath = public_path($survey->image);
            File::delete($absolutePath);
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

    public function createQuestion($data){
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']); //check if data is present 
        }
    
        $validator = Validator::make($data, [
            'question' => 'required|string',
            'type' => ['required', Rule::in([
                Survey::TYPE_TEXT,
                Survey::TYPE_TEXTAREA,
                Survey::TYPE_SELECT,
                Survey::TYPE_RADIO,
                Survey::TYPE_CHECKBOX,
            ])],
            'description' => 'nullable|string',
            'data' => 'nullable',
            'survey_id' => 'exists:App\Models\Survey,id',
        ]);
    
        if ($validator->fails()) {
            // Handle validation errors
        }
    
        return SurveyQuestion::create($validator->validated());
    }

    public function updateQuestion(SurveyQuestion $question, $data)
    {
        //check if data is present 
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }

        $validator = validator::make($data,[
            'id'=> 'exists:App\Models\SurveyQuestion,id',
            'question'=>'required|string',
            'type'=>['required',Rule::in([
                Survey::TYPE_TEXT,
                Survey::TYPE_TEXTAREA,
                Survey::TYPE_SELECT,
                Survey::TYPE_RADIO,
                Survey::TYPE_CHECKBOX,
            ])],
            'description' => 'nullable|string',
            'data' => 'nullable',
        ]);

        return $question->update($validator->validated());
        
    }
    
}
