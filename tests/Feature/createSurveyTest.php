<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Survey;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\StoreSurveyRequest;

class createSurveyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
 

     public function testCreateSurveyWithTextOption()
     {
         $user = User::where('email', 'adelekeofafrica@gmail.com')->first();
     
         // Login the user
         $this->actingAs($user);
     
         // Create a survey for the authenticated user
         $surveyData = [
             'title' => 'Test Survey',
             'user_id' => $user->id,
             'status' => 1,
             'description' => 'This is a test survey',
             'expiry_date' => '2023-05-31',
             'questions' => [
                 [
                     'id' => '13',
                     'type' => 'text',
                     'question' => 'This is a test question',
                     'description' => 'This is a test question description',
                     'data'=>[],
                 ]
             ]
         ];
     
         // Submit the survey form
         $response = $this->post('/api/survey', $surveyData);
     
         // Assert that the response was successful
         $response->assertStatus(201);
     
         // Assert that the survey was saved to the database
         $this->assertDatabaseHas('surveys', [
             'title' => 'Test Survey',
         ]);
     }

     public function testCreateSurveyWithcheckboxOption()
     {
         $user = User::where('email', 'adelekeofafrica@gmail.com')->first();
     
         // Login the user
         $this->actingAs($user);
     
         // Create a survey for the authenticated user
         $surveyData = [
             'title' => 'Test Survey',
             'user_id' => $user->id,
             'status' => 1,
             'description' => 'This is a test survey',
             'expiry_date' => '2023-05-31',
             'questions' => [
                 [
                     'id' => '13',
                     'type' => 'text',
                     'question' => 'This is a test question',
                     'description' => 'This is a test question description',
                     'data'=>[
                        'options'=>[
                            "uuid"=>"9jdfddas7-jhgowng09",
                            "text"=>"testsurvey"
                        ]
                     ],
                 ]
             ]
         ];
     
         // Submit the survey form
         $response = $this->post('/api/survey', $surveyData);
     
         // Assert that the response was successful
         $response->assertStatus(201);
     
         // Assert that the survey was saved to the database
         $this->assertDatabaseHas('surveys', [
             'title' => 'Test Survey',
         ]);
     }
     
     public function testGetSurvey(){
        $user = User::where('email','adelekeofafrica@gmail.com')->first();
        $this->actingAs($user);

        $response= $this->get('/api/survey');
        $response->assertStatus(200);
     }

     public function testGetSurveyById()
     {
         $user = User::where('email', 'adelekeofafrica@gmail.com')->first();
         $this->actingAs($user);
     
         $surveyId = 1; 
     
         $response = $this->get('/api/survey/' . $surveyId);
     
        //  dd($response->getContent()); // Debug the response content
     
         $response->assertStatus(200);
     }
     
     
    
}
