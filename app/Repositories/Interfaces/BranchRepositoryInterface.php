<?php

namespace App\Repositories\Interfaces;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;

interface BranchRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Branch;
    public function findByBranch(?int $branchId): Collection;
    public function create(array $data): Branch;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function active(): Collection;
    public function findByCity(string $city): Collection;
    public function search(string $query): Collection;
}
