<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{

    /** @test */
    public function a_user_can_be_authenticate()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function a_user_can_register()
    {
        $Employee = $this->user_valid_data();

        $response = $this->post('api/employee', $Employee);

        $response->assertStatus(201)
            ->assertJson([
                "Employee" => [
                    'name' => 'Alex',
                    'lastname' => 'maldonado',
                    'company' => 'company1',
                    'mail' => 'ale.maldo097@gmail.com',
                    'phone' => '933530122',
                ],
                'message' => "Created successfully"
            ]);

        $this->assertDatabaseHas('employees', $Employee);
    }

    /** @test */
    public function a_user_can_see_profile()
    {
    }


    public function user_valid_data($overrides = []): array
    {
        return array_merge([
            'name' => 'Alex',
            'lastname' => 'maldonado',
            'company' => 'company1',
            'mail' => 'ale.maldo097@gmail.com',
            'phone' => '933530122',
        ], $overrides);
    }


    //** $this->withoutExceptionHandling();*/
}
