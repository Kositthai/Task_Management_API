<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class TaskTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    protected function setUp(): void
    {
      // set-up is used to set global variable or global setting for all the method inside of your test case
      parent::setUp();
      $this->user = $this->createUser();
    }

    public function createUser(): User 
    {
      return User::factory()->create();
     
    }
   
    public function test_create(): void
    {
       // 1. Define the goal: test if create() will actually create a record in the DB

       // 2. Replicate the env
       $user = $this->app->make(User::class);

       // 3. Define the source of truth 
       $expected = [
        'name' => 'hello',  
        'email' => 'email', 
        'password' => 'password', 
        'confirm_password' => 'confirm_password'  
       ]; 
      
       // 4. Compare the results 
       $actual = $user->create($expected);

       $this->assertSame($expected['name'], $actual->name, "actual value is not same as expected value");
    }

    public function test_update(): void
    {
       // 1. Define the goal: test if create() will actually create a record in the DB

       // 2. Replicate the env
       $user = $this->app->make(User::class);

       // 3. Define the source of truth 
       $expected = [
        'name' => 'hello',  
        'email' => 'email', 
        'password' => 'password', 
        'confirm_password' => 'confirm_password'  
       ]; 
      
       // 4. Compare the results 
       $actual = $user->create($expected);

       $this->assertSame($expected['name'], $actual->name, "actual value is not same as expected value");
    }

    public function test_unauthenticated_user_cannot_access_taks(): void 
    { 
      
      $response = $this->getJson('/api/tasks');

      $response->assertStatus(401); 
    }


    public function test_all_tasks(): void 
    { 
      $response = $this->actingAs($this->user)->getJson('/api/tasks');
      $response->assertStatus(200); 
    }
  
}
