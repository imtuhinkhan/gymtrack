<?php

namespace App\Repositories\Interfaces;

use App\Models\Trainer;
use Illuminate\Database\Eloquent\Collection;

interface TrainerRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Trainer;
    public function create(array $data): Trainer;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function active(): Collection;
    public function findByBranch(?int $branchId): Collection;
    public function findBySpecialization(string $specialization): Collection;
    public function search(string $query): Collection;
    public function getAvailableTrainers(): Collection;
}
