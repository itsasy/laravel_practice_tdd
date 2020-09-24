<?php

namespace Tests\Feature\Companies;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_single_company()
    {
        $this->withoutExceptionHandling();
        $company = factory(Company::class)->create(['logo' => null]);

        $response = $this->getJson('api/company/' . $company->getRouteKey());

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $company->getRouteKey(),
                    'atributes' => [
                        'name' => $company->name,
                        'slug' => $company->slug,
                        'mail' => $company->mail,
                    ],
                    'links' => [
                        'self' => route('company.show', $company->getRouteKey()),
                        'logo' => $company->logo,
                        'website' => $company->website,
                    ]
                ]
            ]);
    }

    /** @test */
    public function can_fetch_all_companies()
    {
        $companies = factory(Company::class)->times(2)->create(['logo' => null]);

        $response = $this->getJson('api/company');

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => [
                        [
                            'id' => $companies[0]->getRouteKey(),
                            'atributes' => [
                                'name' => $companies[0]->name,
                                'slug' => $companies[0]->slug,
                                'mail' => $companies[0]->mail,
                            ],
                            'links' => [
                                'self' => url('api/company/' . $companies[0]->getRouteKey()),
                                'logo' => $companies[0]->logo,
                                'website' => $companies[0]->website,
                            ]

                        ],
                        [
                            'id' => $companies[1]->getRouteKey(),
                            'atributes' => [
                                'name' => $companies[1]->name,
                                'slug' => $companies[1]->slug,
                                'mail' => $companies[1]->mail,
                            ],
                            'links' => [
                                'self' => url('api/company/' . $companies[1]->getRouteKey()),
                                'logo' => $companies[1]->logo,
                                'website' => $companies[1]->website,
                            ]
                        ]
                    ],
                    'links' => [
                        'self' => route('company.index'),
                    ],
                    'meta' =>[
                        'companies_count' => 2
                    ]
                ]
            );
    }
}
