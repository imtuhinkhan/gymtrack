<?php

namespace App\Repositories\Eloquent;

use App\Models\Subscription;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function all(): Collection
    {
        return Subscription::with(['customer', 'package', 'branch'])->get();
    }

    public function find(int $id): ?Subscription
    {
        return Subscription::with(['customer', 'package', 'branch'])->find($id);
    }

    public function create(array $data): Subscription
    {
        return Subscription::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Subscription::where('id', $id)->update($data) > 0;
    }

    public function delete(int $id): bool
    {
        return Subscription::destroy($id) > 0;
    }

    public function active(): Collection
    {
        return Subscription::active()
            ->where('end_date', '>=', now()->toDateString())
            ->with(['customer', 'package', 'branch'])
            ->get();
    }

    public function expired(): Collection
    {
        return Subscription::expired()
            ->orWhere('end_date', '<', now()->toDateString())
            ->with(['customer', 'package', 'branch'])
            ->get();
    }

    public function findByCustomer(int $customerId): Collection
    {
        return Subscription::where('customer_id', $customerId)
            ->with(['customer', 'package', 'branch'])
            ->get();
    }

    public function findByBranch(?int $branchId): Collection
    {
        if ($branchId === null) {
            return Subscription::with(['customer', 'package', 'branch'])->get();
        }
        return Subscription::where('branch_id', $branchId)
            ->with(['customer', 'package', 'branch'])
            ->get();
    }

    public function getExpiringSoon(int $days = 7): Collection
    {
        $expiryDate = now()->addDays($days)->toDateString();
        
        return Subscription::where('end_date', '<=', $expiryDate)
            ->where('end_date', '>=', now()->toDateString())
            ->where('status', 'active')
            ->with(['customer', 'package', 'branch'])
            ->get();
    }

    public function getRevenueByPeriod(string $startDate, string $endDate): float
    {
        return Subscription::whereBetween('start_date', [$startDate, $endDate])
            ->sum('amount_paid');
    }
}
