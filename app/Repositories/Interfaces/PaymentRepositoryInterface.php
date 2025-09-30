<?php

namespace App\Repositories\Interfaces;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

interface PaymentRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Payment;
    public function create(array $data): Payment;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function paid(): Collection;
    public function pending(): Collection;
    public function overdue(): Collection;
    public function findByCustomer(int $customerId): Collection;
    public function findByBranch(?int $branchId): Collection;
    public function getRevenueByPeriod(string $startDate, string $endDate): float;
    public function getRevenueByBranch(int $branchId, string $startDate, string $endDate): float;
}
