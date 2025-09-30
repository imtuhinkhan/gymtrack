<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Display a listing of the trainer's assigned members.
     */
    public function index()
    {
        // Get the trainer record for the authenticated user
        $trainer = \App\Models\Trainer::where('user_id', auth()->id())->first();
        
        if (!$trainer) {
            $members = collect();
        } else {
            $members = $this->customerRepository->findByTrainer($trainer->id);
        }
        
        return view('trainer.members.index', compact('members'));
    }

    /**
     * Display the specified member.
     */
    public function show($id)
    {
        // Get the trainer record for the authenticated user
        $trainer = \App\Models\Trainer::where('user_id', auth()->id())->first();
        
        if (!$trainer) {
            abort(404, 'Trainer record not found.');
        }
        
        $member = $this->customerRepository->findByTrainer($trainer->id)->where('id', $id)->first();
        
        if (!$member) {
            abort(404, 'Member not found or not assigned to you.');
        }
        
        // Load the workout routines relationship
        $member->load(['workoutRoutines', 'user']);
        
        return view('trainer.members.show', compact('member'));
    }
}
