<?php

namespace App\Repositories\Eloquent;

use App\Models\Attendance;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    public function all(): Collection
    {
        return Attendance::with(['attendable', 'branch'])->get();
    }

    public function find(int $id): ?Attendance
    {
        return Attendance::with(['attendable', 'branch'])->find($id);
    }

    public function create(array $data): Attendance
    {
        return Attendance::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Attendance::where('id', $id)->update($data) > 0;
    }

    public function delete(int $id): bool
    {
        return Attendance::destroy($id) > 0;
    }

    public function findByBranch(?int $branchId): Collection
    {
        if ($branchId === null) {
            return Attendance::with(['attendable', 'branch'])->get();
        }
        return Attendance::where('branch_id', $branchId)
            ->with(['attendable', 'branch'])
            ->get();
    }

    public function findByDate(string $date): Collection
    {
        return Attendance::where('date', $date)
            ->with(['attendable', 'branch'])
            ->get();
    }

    public function getAttendanceByDate(string $date, ?int $branchId = null): Collection
    {
        $query = Attendance::where('date', $date);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->with(['attendable', 'branch'])->get();
    }

    public function findByBranchAndDate(int $branchId, string $date): Collection
    {
        return Attendance::where('branch_id', $branchId)
            ->where('date', $date)
            ->with(['attendable', 'branch'])
            ->get();
    }

    public function getTodayAttendance(int $branchId): Collection
    {
        return $this->findByBranchAndDate($branchId, now()->toDateString());
    }

    public function getMonthlyAttendance(int $branchId, string $month): Collection
    {
        return Attendance::where('branch_id', $branchId)
            ->whereMonth('date', $month)
            ->with(['attendable', 'branch'])
            ->get();
    }

    public function markAttendance(array $data): Attendance
    {
        // Check if attendance already exists for this attendable on this date
        $existingAttendance = Attendance::where('attendable_id', $data['attendable_id'])
            ->where('attendable_type', $data['attendable_type'])
            ->where('date', $data['date'])
            ->first();

        if ($existingAttendance) {
            // Update existing attendance
            $existingAttendance->update([
                'status' => $data['status'],
                'branch_id' => $data['branch_id'],
            ]);
            return $existingAttendance;
        } else {
            // Create new attendance record
            return Attendance::create($data);
        }
    }

    public function findByMembersAndDate(array $memberIds, string $date): Collection
    {
        return Attendance::whereIn('attendable_id', $memberIds)
            ->where('attendable_type', 'App\Models\Customer')
            ->where('date', $date)
            ->with(['attendable.user', 'branch'])
            ->get();
    }

    public function countByMembersAndDateRange(array $memberIds, string $startDate, string $endDate): int
    {
        return Attendance::whereIn('attendable_id', $memberIds)
            ->where('attendable_type', 'App\Models\Customer')
            ->whereBetween('date', [$startDate, $endDate])
            ->count();
    }

    public function findByMembersAndDateRange(array $memberIds, string $startDate, string $endDate): Collection
    {
        return Attendance::whereIn('attendable_id', $memberIds)
            ->where('attendable_type', 'App\Models\Customer')
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['attendable.user', 'branch'])
            ->orderBy('date', 'desc')
            ->get();
    }
}
