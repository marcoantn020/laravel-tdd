<?php

namespace App\Repository\Contracts;

interface UserRepositoryInterface
{
    public function findAll(): array;
    public function paginate(int $page = 1): PaginationInterface;
    public function create(array $data): object;
//    public function update(string $id, array $data): object;
    public function update(string $id, array $data): object;
    public function delete(string $id): void;
    public function find(string $id): object;
}
