<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testUserRegistration()
    {
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 'Password_123',
            'password_confirmation' =>'Password_123'
        ];

        $response = $this->post('/api/register', $userData);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        
        ]);
    }

    public function testUserLogin(){
        $userData = [
            'email'=>'adelekeofafrica@gmail.com',
            'password'=>bcrypt('Africa_2023')
        ];

        $response =$this->post('/api/login', $userData);
          
        $this->assertDatabaseHas('users', [
            'email' => 'adelekeofafrica@gmail.com',
        ]);

    }
}
