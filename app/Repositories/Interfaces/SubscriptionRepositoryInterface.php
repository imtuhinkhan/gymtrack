<?php

namespace App\Repositories\Interfaces;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;

interface SubscriptionRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Subscription;
    public function create(array $data): Subscription;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function active(): Collection;
    public function expired(): Collection;
    public function findByCustomer(int $customerId): Collection;
    public function findByBranch(?int $branchId): Collection;
    public function getExpiringSoon(int $days = 7): Collection;
    public function getRevenueByPeriod(string $startDate, string $endDate): float;
}
