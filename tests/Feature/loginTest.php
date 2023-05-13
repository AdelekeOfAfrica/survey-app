<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class loginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_login(){
        $user =[
            'email'=> 'adelekeofafrica@gmail.com',
            'password'=>bcrypt('Africa_2023'), 
        ];
        $response = $this->post('api/login',$user);
        
        $this->assertDatabaseHas('users', [
            'email' => 'adelekeofafrica@gmail.com',
        ]);
         
    }
}
