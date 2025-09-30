<?php

namespace App\Repositories\Interfaces;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Collection;

interface AttendanceRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Attendance;
    public function create(array $data): Attendance;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function findByBranch(?int $branchId): Collection;
    public function findByDate(string $date): Collection;
    public function findByBranchAndDate(int $branchId, string $date): Collection;
    public function getAttendanceByDate(string $date, ?int $branchId = null): Collection;
    public function getTodayAttendance(int $branchId): Collection;
    public function getMonthlyAttendance(int $branchId, string $month): Collection;
    public function markAttendance(array $data): Attendance;
    public function findByMembersAndDate(array $memberIds, string $date): Collection;
    public function countByMembersAndDateRange(array $memberIds, string $startDate, string $endDate): int;
    public function findByMembersAndDateRange(array $memberIds, string $startDate, string $endDate): Collection;
}
