<?php

namespace Tests\Feature\Companies;

use App\Models\Company;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class CompaniesTest extends TestCase
{
    /** @test */
    public function a_user_can_be_register_a_companie_no_image()
    {
        $this->acting_as_user();

        $company = [
            'name' => 'Company One',
            'mail' => 'ale.maldo097@gmail.com',
            'website' => 'aea.com'
        ];

        $response = $this->post('api/company', $company);

        $response->assertStatus(201)
            ->assertJson([
                'Company' => [
                    'name' => 'Company One',
                    'mail' => 'ale.maldo097@gmail.com',
                    'logo' => [
                        'name' => null,
                        'url' => null
                    ],
                    'website' => 'aea.com'
                ],
                'message' => 'Created successfully'
            ]);
    }

    /** @test */
    public function a_user_can_be_register_a_companie_with_image()
    {
        $this->acting_as_user();

        $company = factory(Company::class)->make();

        $response = $this->post('api/company', $company->toArray());

        Storage::disk('')->assertExists('image.png');

        $response->assertStatus(201)
            ->assertJsonStructure([
                'Company' => [
                    'name',
                    'mail',
                    'website',
                    'logo' => [
                        'name',
                        'url'
                    ],
                ]
            ]);

        $response->assertJson([
            'Company' => [
                'name' => $company['name'],
                'mail' => $company['mail'],
                'website' => $company['website'],
                'logo' => [
                    'name' => 'image.png',
                    'url' => 'http://localhost/storage/image.png'
                ],
            ]
        ]);

        $this->assertDatabaseHas('company', [
            'name' => $company['name'],
            'mail' => $company['mail'],
            'logo' => 'image.png',
            'website' => $company['website'],
        ]);
    }

    /** @test */
    public function a_user_can_see_list_of_company_with_image()
    {
        $this->acting_as_user();

        $company = factory(Company::class, 3)->create();

        $response = $this->get('api/company');

        $response->assertStatus(200)
            ->assertJson([
                'Companies' => [
                    [
                        'id' => $company[0]->id,
                        'name' => $company[0]->name,
                        'mail' => $company[0]->mail,
                        'website' => $company[0]->website,
                        'logo' => [
                            'name' => $company[0]->logo,
                            'url' => 'http://localhost/storage/' . $company[0]->logo
                        ],
                    ]
                ],
                'message' => 'Retrieved successfully'
            ]);
    }

    public function a_user_can_see_list_of_company_no_image()
    {
        $this->acting_as_user();

        $company = factory(Company::class, 3)->create(['logo' => null]);

        $response = $this->get('api/company');

        $response->assertStatus(200)
            ->assertJson([
                'Companies' => [
                    [
                        'id' => $company[0]->id,
                        'name' => $company[0]->name,
                        'mail' => $company[0]->mail,
                        'website' => $company[0]->website,
                        'logo' => [
                            'name' => $company[0]->logo,
                            'url' => null
                        ]
                    ]
                ],
                'message' => 'Retrieved successfully'
            ]);
    }

    /** @test */
    public function a_user_can_see_a_company()
    {
        $company = factory(Company::class, 3)->create();

        $response = $this->get('api/company/' . $company[0]->id);

        $response->assertStatus(200)
            ->assertJson([
                'Company' => [
                    'id' => $company[0]->id,
                    'name' => $company[0]->name,
                    'mail' => $company[0]->mail,
                    'website' => $company[0]->website,
                    'logo' => [
                        'name' => $company[0]->logo,
                        'url' => 'http://localhost/storage/' . $company[0]->logo
                    ],
                ],
                'message' => 'Retrieved successfully'
            ]);
    }

    /** @test */
    public function a_user_can_update_an_image_to_a_company_without_an_image ()
    {
        $company = factory(Company::class, 3)->create(['logo' => null]);

        $this->assertDatabaseHas('company', [
            'name' => $company[0]->name,
            'mail' => $company[0]->mail,
            'logo' => $company[0]->logo,
            'website' => $company[0]->website
        ]);

        $update = [
            'name' => 'aeaman',
            'logo' => UploadedFile::fake()->image('image.png', 100, 100),
        ];

        $response = $this->put('api/company/' . $company[0]->id, $update);

        $response->assertStatus(201)
            ->assertJson([
                'Company' => [
                    'id' => $company[0]->id,
                    'name' => 'aeaman',
                    'mail' => $company[0]->mail,
                    'website' => $company[0]->website,
                    'logo' => [
                        'name' => 'image.png',
                        'url' => storage_path() . $company[0]->logo
                    ],
                    'logo' => [
                        'name' => 'image.png',
                        'url' => 'http://localhost/storage/image.png',
                    ],
                ],
                'message' => 'Update successfully'
            ]);

        $this->assertDatabaseHas('company', [
            'name' => 'aeaman',
            'mail' => $company[0]->mail,
            'logo' => 'image.png',
            'website' => $company[0]->website
        ]);
    }

    /** @test */
    public function a_user_can_update_and_replace_a_company_image()
    {
        $company = factory(Company::class, 3)->create(['logo' => 'imageWithoutUpdate.png']);

        $this->assertDatabaseHas('company', [
            'name' => $company[0]->name,
            'mail' => $company[0]->mail,
            'logo' => $company[0]->logo,
            'website' => $company[0]->website
        ]);

        $update = [
            'name' => 'John Wick',
            'logo' => UploadedFile::fake()->image('image.png', 100, 100),
        ];

        $response = $this->put('api/company/' . $company[0]->id, $update);

        $response->assertStatus(201)
            ->assertJson([
                'Company' => [
                    'id' => $company[0]->id,
                    'name' => 'John Wick',
                    'mail' => $company[0]->mail,
                    'website' => $company[0]->website,
                    'logo' => [
                        'name' => 'image.png',
                        'url' => 'http://localhost/storage/' . 'image.png'
                    ]
                ],
                'message' => 'Update successfully'
            ]);

        $this->assertDatabaseHas('company', [
            'name' => 'John Wick',
            'mail' => $company[0]->mail,
            'logo' => 'image.png',
            'website' => $company[0]->website
        ]);
    }
    /** @test */
    public function a_user_can_delete_a_company()
    {
        $company = factory(Company::class, 3)->create();

        $this->assertDatabaseHas('company', [
            'name' => $company[0]->name,
            'mail' => $company[0]->mail,
            'logo' => $company[0]->logo,
            'website' => $company[0]->website
        ]);

        $response = $this->delete('api/company/' . $company[0]->id);

        $this->assertDatabaseMissing('company', [
            'id' => $company[0]->id
        ]);

        $response->assertStatus(204);
    }

    //Sin completar
    public function a_user_send_a_invalid_id_to_delete()
    {
        $this->withoutExceptionHandling();

        $response = $this->delete('api/company/' . 4);

        $response->assertStatus(403);
    }
}
