<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function all(): Collection
    {
        return Payment::with(['customer', 'subscription', 'branch', 'receivedBy'])->get();
    }

    public function find(int $id): ?Payment
    {
        return Payment::with(['customer', 'subscription', 'branch', 'receivedBy'])->find($id);
    }

    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Payment::where('id', $id)->update($data) > 0;
    }

    public function delete(int $id): bool
    {
        return Payment::destroy($id) > 0;
    }

    public function paid(): Collection
    {
        return Payment::paid()->with(['customer', 'subscription', 'branch', 'receivedBy'])->get();
    }

    public function pending(): Collection
    {
        return Payment::pending()->with(['customer', 'subscription', 'branch', 'receivedBy'])->get();
    }

    public function overdue(): Collection
    {
        return Payment::overdue()->with(['customer', 'subscription', 'branch', 'receivedBy'])->get();
    }

    public function findByCustomer(int $customerId): Collection
    {
        return Payment::where('customer_id', $customerId)
            ->with(['customer', 'subscription', 'branch', 'receivedBy'])
            ->get();
    }

    public function findByBranch(?int $branchId): Collection
    {
        if ($branchId === null) {
            return Payment::with(['customer', 'subscription', 'branch', 'receivedBy'])->get();
        }
        return Payment::where('branch_id', $branchId)
            ->with(['customer', 'subscription', 'branch', 'receivedBy'])
            ->get();
    }

    public function getRevenueByPeriod(string $startDate, string $endDate): float
    {
        return Payment::where('status', 'paid')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');
    }

    public function getRevenueByBranch(int $branchId, string $startDate, string $endDate): float
    {
        return Payment::where('status', 'paid')
            ->where('branch_id', $branchId)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');
    }
}
