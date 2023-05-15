<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Survey;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class createSurveyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function test_survey_save()
    {
        // create a user to authenticate with
        $user = User::factory()->create([
            'email' => 'tintinki0o000opnodo0@example.com',
            'password' => bcrypt('password')
        ]);
    
        // login the user
        $this->actingAs($user);
    
        // create a survey for the authenticated user
        $survey = Survey::create([
            'title' => 'Test ksdfbSurvey',
            'user_id'=>"1",
            // 'image' => 'data:image/png;base64,iVBORw0KGg...',
            'status' => 1,
            'description' => 'This is a test survey',
            'expiry_date' => '2023-05-31',
        ]);
    
        // submit the survey form
        $response = $this->post('/api/survey', $survey->toArray());
    
        // assert that the response was successful
        $response->assertStatus(201);
    
        // assert that the survey was saved to the database
        $this->assertDatabaseHas('surveys', [
            'title' => 'Test Survey',    
        ]);
    }
    
}
