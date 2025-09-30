<?php

namespace App\Repositories\Eloquent;

use App\Models\WorkoutRoutine;
use App\Repositories\Interfaces\WorkoutRoutineRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WorkoutRoutineRepository implements WorkoutRoutineRepositoryInterface
{
    public function all(): Collection
    {
        return WorkoutRoutine::with(['trainer', 'exercises'])->get();
    }

    public function find(int $id): ?WorkoutRoutine
    {
        return WorkoutRoutine::with(['trainer', 'exercises'])->find($id);
    }

    public function create(array $data): WorkoutRoutine
    {
        return WorkoutRoutine::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return WorkoutRoutine::where('id', $id)->update($data) > 0;
    }

    public function delete(int $id): bool
    {
        return WorkoutRoutine::destroy($id) > 0;
    }

    public function findByCustomer(int $customerId): Collection
    {
        // Since WorkoutRoutine doesn't have customer relationship, return empty collection
        return collect();
    }

    public function findByTrainer(int $trainerId): Collection
    {
        return WorkoutRoutine::where('trainer_id', $trainerId)
            ->with(['trainer', 'member', 'exercises'])
            ->get();
    }

    public function getActiveRoutines(): Collection
    {
        return WorkoutRoutine::where('is_active', true)
            ->with(['trainer', 'exercises'])
            ->get();
    }

    public function getExpiredRoutines(): Collection
    {
        // Since we don't have start_date/end_date, return empty collection
        return collect();
    }

    public function findByMembers(array $memberIds): Collection
    {
        return WorkoutRoutine::whereIn('member_id', $memberIds)
            ->with(['member.user', 'trainer', 'exercises'])
            ->get();
    }

    public function countByMembers(array $memberIds): int
    {
        return WorkoutRoutine::whereIn('member_id', $memberIds)->count();
    }
}
