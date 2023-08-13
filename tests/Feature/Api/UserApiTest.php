<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    protected string $endpoint = '/api/users';

    /**
     * @dataProvider dataProviderPagination
     */
    public function test_get_paginate(
        int $total,
        int $page,
        int $perPage
    ): void
    {
        User::factory()->count($total)->create();

        $response = $this->getJson("{$this->endpoint}?page={$page}");
        $response->assertOk();
        $response->assertJsonCount($perPage, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page'
            ],
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email'
                ]
            ]
        ]);
        $response->assertJsonFragment([
            'current_page' => $page,
            'total' => $total
        ]);
    }

    public static function dataProviderPagination(): array
    {
        return [
            'test empty paginate' => ['total' => 0,'page' => 1,'perPage' => 0],
            'test total 40 users page 1' => ['total' => 40,'page' => 1,'perPage' => 15],
            'test total 5 users page 2' => ['total' => 20,'page' => 2,'perPage' => 5],
            'test total 10 users page 2' => ['total' => 10,'page' => 2,'perPage' => 0],
        ];
    }

    /**
     * @dataProvider dataProviderCreateUser
     */
    public function test_create_user(
        array $payload,
        int $statusCode,
        array $structureResponse
    ): void
    {
        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus($statusCode);
        $response->assertJsonStructure($structureResponse);
    }

    public static function dataProviderCreateUser(): array
    {
        return [
            'Should return a new user' => [
                'payload' => [
                    'name' => 'Marco Antonio',
                    'email' => 'marco@marco.com',
                    'password' => '123456'
                ],
                'statusCode' => Response::HTTP_CREATED,
                'structureResponse' => [
                    'data' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]
            ],
            'Should return an error name required' => [
                'payload' => [
                    'name' => '',
                    'email' => 'marco@marco.com',
                    'password' => '123456'
                ],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'structureResponse' => [
                    'message',
                    'errors'
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderFindUser
     */
    public function test_find_user(
        string $id,
        int $statusCode,
        array $structureResponse
    )
    {
        $user = User::factory()->create();
        $searchID = ($id === '') ? $user->id : $id;

        $response = $this->getJson("{$this->endpoint}/{$searchID}");

        $response->assertStatus($statusCode);
        $response->assertJsonStructure($structureResponse);
    }

    public static function dataProviderFindUser(): array
    {
        return [
            'must return an user' => [
                'id' => '',
                'statusCode' => Response::HTTP_OK,
                'structureResponse' => [
                    'data' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]
            ],
            'must return an error' => [
                'id' => 'faker_id',
                'statusCode' => Response::HTTP_NOT_FOUND,
                'structureResponse' => [
                    'errors'
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderUpdateUser
     */
    public function test_update(
        string $id,
        int $statusCode,
        array $payload,
        array $structureResponse
    )
    {
        $user = User::factory()->create();
        $searchID = ($id === '') ? $user->id : $id;

        $response = $this->putJson("{$this->endpoint}/{$searchID}", $payload);
        $response->assertStatus($statusCode);
        $response->assertJsonStructure($structureResponse);
    }

    public static function dataProviderUpdateUser(): array
    {
        return [
            'should return user updated' => [
                'id' => '',
                'statusCode' => Response::HTTP_OK,
                'payload' => [
                    'name' => "new name"
                ],
                'structureResponse' => [
                    'data' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]
            ],
            'should return user not found' => [
                'id' => 'faker_id',
                'statusCode' => Response::HTTP_NOT_FOUND,
                'payload' => [
                    'name' => "new name"
                ],
                'structureResponse' => [
                    'errors'
                ]
            ],
            'should return self user if payload empty' => [
                'id' => 'email@email.com',
                'statusCode' => Response::HTTP_NOT_FOUND,
                'payload' => [],
                'structureResponse' => [
                    'errors'
                ]
            ],
            'should return error 422' => [
                'id' => 'email@email.com',
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'payload' => [
                    'password' => '12'
                ],
                'structureResponse' => [
                    'message',
                    'errors'
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderDeleteUser
     */
    public function test_delete(
        string $id,
        int $statusCode
    )
    {
        $user = User::factory()->create();
        $searchID = ($id === '') ? $user->id : $id;
        $response = $this->deleteJson("{$this->endpoint}/{$searchID}");

        $response->assertStatus($statusCode);
    }

    public static function dataProviderDeleteUser(): array
    {
        return [
            'should return ok' => [
                'id' => '',
                'statusCode' => Response::HTTP_NO_CONTENT
            ],
            'should return not found ' => [
                'id' => 'faker_id',
                'statusCode' => Response::HTTP_NOT_FOUND
            ],
        ];
    }
}
