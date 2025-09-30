<?php

namespace App\Repositories\Interfaces;

use App\Models\Package;
use Illuminate\Database\Eloquent\Collection;

interface PackageRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Package;
    public function create(array $data): Package;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function active(): Collection;
    public function popular(): Collection;
    public function search(string $query): Collection;
    public function findByPriceRange(float $minPrice, float $maxPrice): Collection;
}
