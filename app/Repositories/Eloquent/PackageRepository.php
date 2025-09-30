<?php

namespace App\Repositories\Eloquent;

use App\Models\Package;
use App\Repositories\Interfaces\PackageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PackageRepository implements PackageRepositoryInterface
{
    public function all(): Collection
    {
        return Package::all();
    }

    public function find(int $id): ?Package
    {
        return Package::find($id);
    }

    public function create(array $data): Package
    {
        return Package::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Package::where('id', $id)->update($data) > 0;
    }

    public function delete(int $id): bool
    {
        return Package::destroy($id) > 0;
    }

    public function active(): Collection
    {
        return Package::active()->get();
    }

    public function popular(): Collection
    {
        return Package::popular()->active()->get();
    }

    public function search(string $query): Collection
    {
        return Package::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();
    }

    public function findByPriceRange(float $minPrice, float $maxPrice): Collection
    {
        return Package::whereBetween('price', [$minPrice, $maxPrice])
            ->active()
            ->get();
    }
}
