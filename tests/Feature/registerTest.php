<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class registerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_register()
    {
        $userData = [
            'email' => 'testo0@example.com',
            'password' => 'Africa_2023',
            'password_confirmation' => 'Africa_2023',
            'name' => 'Test User',
        ];
    
        $response = $this->post('api/register', $userData);
    
        $response->assertStatus(200); 
        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'name' => $userData['name'],
        ]);
    }
    
}
