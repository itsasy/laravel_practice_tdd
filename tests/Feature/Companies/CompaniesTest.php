<?php

namespace Tests\Feature\Companies;

use App\Models\Company;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class CompaniesTest extends TestCase
{
    public function company_valid_data($overrides = []): array
    {
        return array_merge([
            'name' => 'Company One',
            'mail' => 'ale.maldo097@gmail.com',
            'logo' => UploadedFile::fake()->image('image.png', 100, 100),
            'website' => 'aea.com'
        ], $overrides);
    }

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
        $this->withoutExceptionHandling();

        $this->acting_as_user();


        $response = $this->post('api/company', $this->company_valid_data());

        //Storage::fake('');
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
                'name' => 'Company One',
                'mail' => 'ale.maldo097@gmail.com',
                'website' => 'aea.com',
                'logo' => [
                    'name' => 'image.png',
                    'url' => 'http://localhost/storage/image.png'
                ],
            ]
        ]);

        $this->assertDatabaseHas('company', [
            'name' => 'Company One',
            'mail' => 'ale.maldo097@gmail.com',
            'logo' => 'image.png',
            'website' => 'aea.com'
        ]);
    }

    /** @test */
    public function a_user_can_see_list_of_company_with_image()
    {

        $this->acting_as_user();

        $data = factory(Company::class, 3)->create();

        $response = $this->get('api/company');

        $response->assertStatus(200)
            ->assertJson([
                'Companies' => [
                    [
                        'id' => $data[0]->id,
                        'name' => $data[0]->name,
                        'mail' => $data[0]->mail,
                        'website' => $data[0]->website,
                        'logo' => [
                            'name' => $data[0]->logo,
                            'url' => 'http://localhost/storage/' . $data[0]->logo
                        ],
                    ]
                ],
                'message' => 'Retrieved successfully'
            ]);
    }


    public function a_user_can_see_list_of_company_no_image()
    {
        $this->withExceptionHandling();

        $this->acting_as_user();

        $data = factory(Company::class, 3)->create(['logo' => null]);

        $response = $this->get('api/company');

        $response->assertStatus(200)
            ->assertJson([
                'Companies' => [
                    [
                        'id' => $data[0]->id,
                        'name' => $data[0]->name,
                        'mail' => $data[0]->mail,
                        'website' => $data[0]->website,
                        'logo' => [
                            'name' => $data[0]->logo,
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
        $this->withoutExceptionHandling();

        $data = factory(Company::class, 3)->create();

        $response = $this->get('api/company/' . $data[0]->id);

        $response->assertStatus(200)
            ->assertJson([
                'Company' => [
                    'id' => $data[0]->id,
                    'name' => $data[0]->name,
                    'mail' => $data[0]->mail,
                    'website' => $data[0]->website,
                    'logo' => [
                        'name' => $data[0]->logo,
                        'url' => 'http://localhost/storage/' . $data[0]->logo
                    ],
                ],
                'message' => 'Retrieved successfully'
            ]);
    }

    /** @test */
    public function a_user_can_edit_a_company()
    {
        $this->withoutExceptionHandling();

        $data = factory(Company::class, 3)->create();

        $this->assertDatabaseHas('company', [
            'name' => $data[0]->name,
            'mail' => $data[0]->mail,
            'logo' => $data[0]->logo,
            'website' => $data[0]->website
        ]);

        $actually = [
            'name' => 'aeaman',
            'logo' => UploadedFile::fake()->image('image.png', 100, 100),
        ];

        $response = $this->put('api/company/' . $data[0]->id, $actually);

        $response->assertStatus(201)
            ->assertJson([
                'Company' => [
                    'id' => $data[0]->id,
                    'name' => 'aeaman',
                    'mail' => $data[0]->mail,
                    'website' => $data[0]->website,
                    'logo' => [
                        'name' => 'image.png',
                        'url' => storage_path() . $data[0]->logo
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
            'mail' => $data[0]->mail,
            'logo' => 'image.png',
            'website' => $data[0]->website
        ]);
    }

    /** @test */
    public function a_user_can_delete_a_company()
    {
        $this->withoutExceptionHandling();
        $data = factory(Company::class, 3)->create();

        $this->assertDatabaseHas('company', [
            'name' => $data[0]->name,
            'mail' => $data[0]->mail,
            'logo' => $data[0]->logo,
            'website' => $data[0]->website
        ]);

        $response = $this->delete('api/company/' . $data[0]->id);

        $this->assertDatabaseMissing('company', [
            'name' => $data[0]->name
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
