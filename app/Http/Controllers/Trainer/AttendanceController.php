<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    protected $attendanceRepository;
    protected $customerRepository;

    public function __construct(
        AttendanceRepositoryInterface $attendanceRepository,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->attendanceRepository = $attendanceRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Display a listing of attendance for trainer's members.
     */
    public function index(Request $request)
    {
        // Get the trainer record for the authenticated user
        $trainer = \App\Models\Trainer::where('user_id', auth()->id())->first();
        
        if (!$trainer) {
            $members = collect();
            $attendance = collect();
        } else {
            $members = $this->customerRepository->findByTrainer($trainer->id);
            $memberIds = $members->pluck('id')->toArray();
            
            $date = $request->input('date', now()->toDateString());
            $attendance = $this->attendanceRepository->findByMembersAndDate($memberIds, $date);
        }
        
        return view('trainer.attendance.index', compact('attendance', 'members', 'date'));
    }

    /**
     * Store manual attendance entry for trainer's members.
     */
    public function storeManual(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i|after:check_in_time',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get the trainer record for the authenticated user
        $trainer = \App\Models\Trainer::where('user_id', auth()->id())->first();
        
        if (!$trainer) {
            return redirect()->back()->withErrors(['customer_id' => 'Trainer record not found.']);
        }
        
        // Verify the member belongs to this trainer
        $member = $this->customerRepository->findByTrainer($trainer->id)->where('id', $request->customer_id)->first();
        
        if (!$member) {
            return redirect()->back()->withErrors(['customer_id' => 'Member not found or not assigned to you.']);
        }

        // Check if attendance already exists for this customer on this date
        $existingAttendance = $this->attendanceRepository->all()
            ->where('customer_id', $request->customer_id)
            ->where('date', $request->date)
            ->first();

        if ($existingAttendance) {
            return redirect()->back()
                ->withErrors(['date' => 'Attendance for this member on ' . \Carbon\Carbon::parse($request->date)->format('M d, Y') . ' has already been submitted.'])
                ->withInput();
        }

        $data = $request->all();
        $data['attendable_type'] = 'App\Models\Customer';
        $data['attendable_id'] = $request->customer_id;
        $data['trainer_id'] = $trainer->id;
        $data['branch_id'] = $member->branch_id;
        
        $this->attendanceRepository->create($data);

        return redirect()->route('trainer.attendance.index')
            ->with('success', 'Attendance recorded successfully.');
    }
}
