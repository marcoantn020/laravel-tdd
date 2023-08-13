<?php

namespace Tests\Feature\App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Eloquent\UserRepository;
use App\Repository\Exceptions\NotFoundException;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    protected UserRepositoryInterface $repository;
    protected function setUp(): void
    {
        $this->repository = new UserRepository(new User());
        parent::setUp();
    }

    public function test_implements_interface_repository()
    {
        $this->assertInstanceOf(
            UserRepositoryInterface::class,
            $this->repository
        );
    }

    public function test_find_all_empty(): void
    {
        $response = $this->repository->findAll();

        $this->assertIsArray($response);
        $this->assertCount(0, $response);
    }

    public function test_find_all(): void
    {
        User::factory(3)->create();
        $response = $this->repository->findAll();

        $this->assertIsArray($response);
        $this->assertCount(3, $response);
    }

    public function test_create()
    {
        $response = $this->repository->create(data:[
            'name' => 'Marco',
            'email' => 'marco@marco.com',
            'password' => '12345678',
        ]);

        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'email' => 'marco@marco.com'
        ]);
    }

    public function test_create_exception()
    {
        $this->expectException(QueryException::class);
        $this->repository->create(data:[
            'name' => 'Marco',
            'password' => '12345678',
        ]);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'new name'
        ];

        $response = $this->repository->update($user->id, $data);
        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'name' => 'new name'
        ]);
    }

    public function test_update_exception()
    {
        $this->expectException(NotFoundException::class);
        $data = [
            'name' => 'new name'
        ];
        $this->repository->update('fake_id', $data);
    }

    public function test_delete()
    {
        $user = User::factory()->create();
        $this->repository->delete($user->id);

        $this->assertDatabaseMissing('users', [
            'email' => $user->email
        ]);
    }

    public function test_delete_not_found_user_email()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->delete("faker_id");
    }

    public function test_find()
    {
        $user = User::factory()->create();
        $response = $this->repository->find($user->id);

        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'email' => $user->email
        ]);
    }

    public function test_find_not_found_user_email()
    {
        $this->expectException(NotFoundException::class);
        $response = $this->repository->find("faker_id");
    }
}
