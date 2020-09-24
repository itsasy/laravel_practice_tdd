<?php

namespace Tests\Feature\Companies;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SortCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_sort_articles_by_name_asc()
    {
        $company1 = factory(Company::class)->create(
            ['name' => 'C name', 'logo' => null]
        );

        $company2 = factory(Company::class)->create(
            ['name' => 'B name', 'logo' => null]
        );

        $company3 = factory(Company::class)->create(
            ['name' => 'A name', 'logo' => null]
        );

        $url = route('company.index', ['sort' => 'name']);

        $response = $this->get($url)->assertSeeInOrder([
            'A name',
            'B name',
            'C name'
        ]);

        $response->assertStatus(200);
    }

     /** @test */
     public function it_can_sort_articles_by_name_desc()
     {
         $company1 = factory(Company::class)->create(
             ['name' => 'B name', 'logo' => null]
         );

         $company2 = factory(Company::class)->create(
             ['name' => 'A name', 'logo' => null]
         );

         $company3 = factory(Company::class)->create(
             ['name' => 'C name', 'logo' => null]
         );

         $url = route('company.index', ['sort' => '-name']);

         $response = $this->get($url)->assertSeeInOrder([
             'C name',
             'B name',
             'A name'
         ]);

     }

}
