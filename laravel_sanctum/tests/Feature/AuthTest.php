<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use SebastianBergmann\Type\VoidType;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{

    use RefreshDatabase;
    public function test_unauthenticated_user_cannot_access_task(): void
    {
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(401);  
    }

    public function test_login(): void
    {
        // Because refreshDatabase always wipe your migration file 
        // meaning whenever you run the test file again, all of your user data will be remove too
        // in this case, that is why we create a user in for initial and use that user to test test_login
        // create user name, email and password cannot be null but when login, you need only email and password 
        // this rule has set in User Model
        User::create([
            'name' => 'Joe Doe',
            'email' => 'jane@test.com',
            'password'=> bcrypt('12345678'),
        ]); 

        $response =  $this->postJson('/api/login', [
            'email' => 'jane@test.com',
            'password'=> '12345678',
        ]); 

        $response->assertOk();
    }
}
