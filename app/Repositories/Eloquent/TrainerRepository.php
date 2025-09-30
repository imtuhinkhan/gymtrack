<?php

namespace App\Repositories\Eloquent;

use App\Models\Trainer;
use App\Repositories\Interfaces\TrainerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TrainerRepository implements TrainerRepositoryInterface
{
    public function all(): Collection
    {
        return Trainer::with(['branch'])->get();
    }

    public function find(int $id): ?Trainer
    {
        return Trainer::with(['branch'])->find($id);
    }

    public function create(array $data): Trainer
    {
        return Trainer::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Trainer::where('id', $id)->update($data) > 0;
    }

    public function delete(int $id): bool
    {
        return Trainer::destroy($id) > 0;
    }

    public function active(): Collection
    {
        return Trainer::active()->with(['branch'])->get();
    }

    public function findByBranch(?int $branchId): Collection
    {
        if ($branchId === null) {
            return Trainer::with(['branch'])->get();
        }
        return Trainer::where('branch_id', $branchId)->with(['branch'])->get();
    }

    public function findBySpecialization(string $specialization): Collection
    {
        return Trainer::whereJsonContains('specializations', $specialization)
            ->with(['branch'])
            ->get();
    }

    public function search(string $query): Collection
    {
        return Trainer::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->with(['branch'])
            ->get();
    }

    public function getAvailableTrainers(): Collection
    {
        return Trainer::active()
            ->whereDoesntHave('customers', function ($query) {
                $query->where('status', 'active');
            })
            ->with(['branch'])
            ->get();
    }
}
