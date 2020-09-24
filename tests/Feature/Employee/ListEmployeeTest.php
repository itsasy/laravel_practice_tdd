<?php

namespace Tests\Feature\Employee;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListEmployeeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_single_employee()
    {
        $this->withoutExceptionHandling();
        $employee = factory(Employee::class)->create();

        $response = $this->getJson('api/employee/' . $employee->getRouteKey());

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $employee->getRouteKey(),
                    'atributes' => [
                        'name' => $employee->name,
                        'lastname' => $employee->lastname,
                        'phone' => $employee->phone,
                        'mail' => $employee->mail,
                        'slug' => $employee->slug,
                    ],
                    'links' => [
                        'self' => route('employee.show', $employee->getRouteKey()),
                        'company' => route('company.show', $employee->company_id),
                    ]
                ]
            ]);
    }

    /** @test */
    public function can_fetch_all_employees()
    {
        $this->withoutExceptionHandling();
        $employees = factory(Employee::class)->times(2)->create();

        $response = $this->getJson('api/employee');

        $response->assertStatus(200)
            ->assertJson(
                [
                    'data' => [
                        [
                            'id' => $employees[0]->getRouteKey(),
                            'atributes' => [
                                'name' => $employees[0]->name,
                                'lastname' => $employees[0]->lastname,
                                'phone' => $employees[0]->phone,
                                'mail' => $employees[0]->mail,
                                'slug' => $employees[0]->slug,
                            ],
                            'links' => [
                                'self' => route('employee.show', $employees[0]->getRouteKey()),
                                'company' => route('company.show', $employees[0]->company_id),
                            ]
                        ],
                        [
                            'id' => $employees[1]->getRouteKey(),
                            'atributes' => [
                                'name' => $employees[1]->name,
                                'lastname' => $employees[1]->lastname,
                                'phone' => $employees[1]->phone,
                                'mail' => $employees[1]->mail,
                                'slug' => $employees[1]->slug,
                            ],
                            'links' => [
                                'self' => route('employee.show', $employees[1]->getRouteKey()),
                                'company' => route('company.show', $employees[1]->company_id),
                            ]
                        ]
                    ],
                    /* 'links' => [
                        'self' => route('employee.index'),
                    ], */
                    'meta' => [
                        'employees_count' => 2
                    ]
                ]
            );
    }
}
