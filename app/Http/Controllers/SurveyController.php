<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Users;
use Illuminate\Http\Request;
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
       $result = Survey::create($request->validated());

       return new SurveyResource($result);
    }

    /**
     * Display the specified resource.
     * please note , you are using the slug to fetch the details 
     */
    public function show(Survey $survey, Request $request)
    {
        //
        $user = $request->user();
       
        if($user->id !== $survey->user_id){
            return abort(403 ,'unauthorized action');
        }
        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        //
        $survey->update($request->validated());

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
}
