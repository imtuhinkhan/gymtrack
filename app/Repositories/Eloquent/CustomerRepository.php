<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function all(): Collection
    {
        return Customer::with(['branch', 'trainer'])->get();
    }

    public function find(int $id): ?Customer
    {
        return Customer::with(['branch', 'trainer'])->find($id);
    }

    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Customer::where('id', $id)->update($data) > 0;
    }

    public function delete(int $id): bool
    {
        return Customer::destroy($id) > 0;
    }

    public function active(): Collection
    {
        return Customer::active()->with(['branch', 'trainer'])->get();
    }

    public function expired(): Collection
    {
        return Customer::expired()->with(['branch', 'trainer'])->get();
    }

    public function findByBranch(?int $branchId): Collection
    {
        if ($branchId === null) {
            return Customer::with(['branch', 'trainer'])->get();
        }
        return Customer::where('branch_id', $branchId)->with(['branch', 'trainer'])->get();
    }

    public function findByTrainer(int $trainerId): Collection
    {
        return Customer::where('trainer_id', $trainerId)->with(['branch', 'trainer'])->get();
    }

    public function search(string $query): Collection
    {
        return Customer::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->with(['branch', 'trainer'])
            ->get();
    }

    public function getWithActiveSubscription(): Collection
    {
        return Customer::whereHas('subscriptions', function ($query) {
            $query->where('status', 'active')
                  ->where('end_date', '>=', now()->toDateString());
        })->with(['branch', 'trainer'])->get();
    }

    public function getExpiringSoon(int $days = 7): Collection
    {
        $expiryDate = now()->addDays($days)->toDateString();
        
        return Customer::where('expiry_date', '<=', $expiryDate)
            ->where('expiry_date', '>=', now()->toDateString())
            ->with(['branch', 'trainer'])
            ->get();
    }
}
