<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Exceptions\NotFoundException;

class UserRepository implements UserRepositoryInterface
{
    protected ?User $model = null;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function findAll(): array
    {
        return $this->model->get()->toArray();
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(string $email, array $data): object
    {
        if($user = $this->find($email)) {
            $user->update($data);
            $user->refresh();
        }
        return $user;
    }

    /**
     * @throws NotFoundException
     */
    public function delete(string $email): bool
    {
        if (!$user = $this->find($email)) {
            throw new NotFoundException("user not found.");
        }
        $user->delete();
        return true;
    }

    /**
     * @throws NotFoundException
     */
    public function find(string $email): ?object
    {
        return $this->model->where("email", $email)->first();

    }
}
