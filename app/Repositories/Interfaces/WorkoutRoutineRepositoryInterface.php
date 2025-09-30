<?php

namespace App\Repositories\Interfaces;

use App\Models\WorkoutRoutine;
use Illuminate\Database\Eloquent\Collection;

interface WorkoutRoutineRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?WorkoutRoutine;
    public function create(array $data): WorkoutRoutine;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function findByCustomer(int $customerId): Collection;
    public function findByTrainer(int $trainerId): Collection;
    public function getActiveRoutines(): Collection;
    public function getExpiredRoutines(): Collection;
    public function findByMembers(array $memberIds): Collection;
    public function countByMembers(array $memberIds): int;
}
