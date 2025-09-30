<?php

namespace App\Repositories\Interfaces;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

interface CustomerRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Customer;
    public function create(array $data): Customer;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function active(): Collection;
    public function expired(): Collection;
    public function findByBranch(?int $branchId): Collection;
    public function findByTrainer(int $trainerId): Collection;
    public function search(string $query): Collection;
    public function getWithActiveSubscription(): Collection;
    public function getExpiringSoon(int $days = 7): Collection;
}
