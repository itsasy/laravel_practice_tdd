<?php

namespace Tests\Feature\Companies;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class CreateCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_a_company_no_image()
    {
        $company = [
            'name' => 'Company One',
            'mail' => 'ale.maldo097@gmail.com',
            'website' => 'aea.com'
        ];

        $response = $this->post('api/company', $company)->dump();

        /* $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $company->name,
        ]); */

        $response->assertJson([
            'data' => [
                'id' => 1,
                'atributes' => [
                    'name' => $company['name'],
                    'mail' => $company['mail'],
                    'slug' => Str::slug($company['name'])
                ],
                'links' => [
                    'self' => route('company.show', 1),
                    'logo' => null,
                    'website' => $company['website'],
                ]
            ]
        ]);
    }
}
