<?php

namespace Tests\Feature\Employee;

use App\Models\Employee;
use App\Models\User;
use Tests\TestCase;

class EmployeesTest extends TestCase
{
    /** @test */
    public function a_user_can_be_register_a_employee()
    {
        $this->acting_as_user();

        $employee = $this->user_valid_data();

        $response = $this->post('api/employee', $employee);

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

        /* $this->assertDatabaseHas('employees', $employee); */
    }

    /** @test */
    public function a_user_submit_erroneous_data_to_register_a_employee()
    {
        $this->acting_as_user();

        $employee = $this->user_valid_data([
            'name' => 'ale',
            'mail' => 'ale@'
        ]);

        $response = $this->post('api/employee', $employee);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error',
                'message'
            ]);
    }

    /** @test */
    public function a_user_can_see_list_of_employees()
    {
        $this->acting_as_user();

        $employee = factory(Employee::class, 3)->create();

        $response = $this->get('api/employee');

        $response->assertStatus(200)
            ->assertJson([
                "Employees" => [
                    [
                        'id' => $employee[0]->id,
                        'name' => $employee[0]->name,
                        'lastname' => $employee[0]->lastname,
                        'company' => $employee[0]->company,
                        'mail' => $employee[0]->mail,
                        'phone' => $employee[0]->phone,
                    ]
                ],
                'message' => 'Retrieved successfully'
            ]);

        $this->assertEquals(3, Employee::all()->count());
    }

    /** @test */
    public function a_user_can_see_a_employee()
    {
        $this->acting_as_user();

        $employee = factory(Employee::class, 3)->create();

        $response = $this->get('api/employee/' . $employee[1]->id);

        $response->assertStatus(200)
            ->assertJson([
                "Employee" => [
                    'id' => $employee[1]->id,
                    'name' => $employee[1]->name,
                    'lastname' => $employee[1]->lastname,
                    'company' => $employee[1]->company,
                    'mail' => $employee[1]->mail,
                    'phone' => $employee[1]->phone,
                ],
                'message' => 'Retrieved successfully'
            ]);
    }

    /** @test */
    public function a_user_can_edit_a_employee()
    {
        $this->withoutExceptionHandling();
        $this->acting_as_user();

        $employee = factory(Employee::class, 3)->create();

        $payload = [
            'name' => 'Juancito'
        ];

        $response = $this->put(
            'api/employee/' . $employee[2]->id,
            $payload,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(201)
            ->assertJson([
                "Employee" => [
                    'id' => $employee[2]->id,
                    'name' => 'Juancito',
                    'lastname' => $employee[2]->lastname,
                    'company' => $employee[2]->company,
                    'mail' => $employee[2]->mail,
                    'phone' => $employee[2]->phone,
                ],
                'message' => 'Updated successfully'
            ]);
    }

    /** @test */
    public function a_user_can_delete_employee()
    {
        $this->withoutExceptionHandling();
        $this->acting_as_user();
        $employee = factory(Employee::class, 3)->create();

        $response = $this->delete(
            'api/employee/' . 1,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(204);
    }
    public function acting_as_user()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
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
}
