<?php

namespace Tests\Feature\Employee;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SortEmployeeTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function Example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
