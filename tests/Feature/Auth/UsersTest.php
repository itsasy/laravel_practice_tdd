<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{
    public function acting_as_user()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
    }

    public function post_request($route, $data)
    {
        return $this->json(
            'POST',
            $route,
            $data,
            ['Accept' => 'application/json']
        );
    }
    public function get_request($route)
    {
        return $this->json(
            'GET',
            $route,
            ['Accept' => 'application/json']
        );
    }

    /** @test */
    public function a_user_can_be_authenticate()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function a_user_logged_can_be_register_a_employee()
    {
        $this->withoutExceptionHandling();
        $this->acting_as_user();

        $Employee = factory(Employee::class)->make();

        $response = $this->post_request('api/employee', $Employee->toArray());

        $response->assertStatus(201)
            ->assertJson([
                "Employee" => [
                    'name' => $Employee->name,
                    'lastname' => $Employee->lastname,
                    'company' => $Employee->company,
                    'mail' => $Employee->mail,
                    'phone' => $Employee->phone,
                ],
                'message' => "Created successfully"
            ]);
        /*
        /* $this->assertDatabaseHas('employees', $Employee); */
    }

    /** @test */
    public function a_usser_logged_submit_erroneous_data_to_register_a_employee()
    {

        $this->withoutExceptionHandling();
        $this->acting_as_user();

        $Employee = factory(Employee::class)->create([
            'name' => 'ale',
            'mail' => 'ale@'
        ]);

        $response = $this->post_request('api/employee', $Employee->toArray());

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error',
                'message'
            ]);
    }
}
