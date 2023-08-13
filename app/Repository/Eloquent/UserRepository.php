<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\PaginationInterface;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Exceptions\NotFoundException;
use App\Repository\Presenters\PaginationPresenter;

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

    public function paginate(int $page = 1): PaginationInterface
    {
        return new PaginationPresenter($this->model->paginate());
    }

    public function create(array $data): object
    {
        $data['password'] = bcrypt($data['password']);
        return $this->model->create($data);
    }

    /**
     * @throws NotFoundException
     */
    public function update(string $id, array $data): object
    {
        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $user = $this->find($id);
        if(!$data) return $user;
        $user->update($data);
        $user->refresh();
        return $user;
    }

    /**
     * @throws NotFoundException
     */
    public function delete(string $id): void
    {
        $user = $this->find($id);
        $user->delete();
    }

    /**
     * @throws NotFoundException
     */
    public function find(string $id): object
    {
        if (!$user = $this->model->find($id)) {
            throw new NotFoundException(message: "user not found");
        }
        return $user;
    }

}
