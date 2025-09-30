<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentRepository;
    protected $customerRepository;
    protected $subscriptionRepository;

    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        CustomerRepositoryInterface $customerRepository,
        SubscriptionRepositoryInterface $subscriptionRepository
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->customerRepository = $customerRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $branchId = null;
        
        // If user is branch manager, filter by their branch
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branchId = $user->branch_id;
        }
        
        $query = $this->paymentRepository->findByBranch($branchId);

        // Filter by status
        if ($request->filled('status')) {
            $query = $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query = $query->where('payment_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query = $query->where('payment_date', '<=', $request->end_date);
        }

        // Filter by customer
        if ($request->filled('customer_id')) {
            $query = $query->where('customer_id', $request->customer_id);
        }

        $sortedQuery = $query->sortByDesc('payment_date');
        
        // Convert to paginated collection
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $items = $sortedQuery->slice($offset, $perPage)->values();
        
        $payments = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $sortedQuery->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
        
        // If user is branch manager, only show customers from their branch
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $customers = $this->customerRepository->findByBranch($user->branch_id);
        } else {
            $customers = $this->customerRepository->all();
        }

        return view('admin.payments.index', compact('payments', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        // If user is branch manager, only show customers and subscriptions from their branch
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $customers = $this->customerRepository->findByBranch($user->branch_id);
            $subscriptions = $this->subscriptionRepository->findByBranch($user->branch_id);
        } else {
            $customers = $this->customerRepository->all();
            $subscriptions = $this->subscriptionRepository->all();
        }

        return view('admin.payments.create', compact('customers', 'subscriptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // If user is branch manager, force their branch_id
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $request->merge(['branch_id' => $user->branch_id]);
        }
        
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'status' => 'required|in:paid,pending,overdue',
        ]);

        $validatedData['received_by'] = Auth::id();
        
        // Get branch_id from customer or use admin's branch_id
        $customer = $this->customerRepository->find($validatedData['customer_id']);
        $validatedData['branch_id'] = $customer->branch_id ?? Auth::user()->branch_id ?? 1;

        $this->paymentRepository->create($validatedData);

        return redirect()->route('admin.payments.index')->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = $this->paymentRepository->find((int) $id);
        if (!$payment) {
            abort(404);
        }
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment = $this->paymentRepository->find((int) $id);
        if (!$payment) {
            abort(404);
        }

        $user = auth()->user();
        
        // If user is branch manager, only show customers and subscriptions from their branch
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $customers = $this->customerRepository->findByBranch($user->branch_id);
            $subscriptions = $this->subscriptionRepository->findByBranch($user->branch_id);
        } else {
            $customers = $this->customerRepository->all();
            $subscriptions = $this->subscriptionRepository->all();
        }

        return view('admin.payments.edit', compact('payment', 'customers', 'subscriptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = $this->paymentRepository->find((int) $id);
        if (!$payment) {
            abort(404);
        }

        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'status' => 'required|in:paid,pending,overdue',
        ]);

        // Get branch_id from customer or use admin's branch_id
        $customer = $this->customerRepository->find($validatedData['customer_id']);
        $validatedData['branch_id'] = $customer->branch_id ?? Auth::user()->branch_id ?? 1;

        $this->paymentRepository->update((int) $id, $validatedData);

        return redirect()->route('admin.payments.index')->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = $this->paymentRepository->find((int) $id);
        if (!$payment) {
            abort(404);
        }

        $this->paymentRepository->delete((int) $id);

        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully.');
    }

    /**
     * Mark payment as paid
     */
    public function markPaid(string $id)
    {
        $payment = $this->paymentRepository->find((int) $id);
        if (!$payment) {
            abort(404);
        }

        $this->paymentRepository->update((int) $id, [
            'status' => 'paid',
            'payment_date' => now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'Payment marked as paid.');
    }

    /**
     * Export payments to CSV
     */
    public function export(Request $request)
    {
        $query = $this->paymentRepository->all();

        // Apply same filters as index
        if ($request->filled('status')) {
            $query = $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query = $query->where('payment_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query = $query->where('payment_date', '<=', $request->end_date);
        }
        if ($request->filled('customer_id')) {
            $query = $query->where('customer_id', $request->customer_id);
        }

        $payments = $query->sortByDesc('payment_date');

        $filename = 'payments_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Customer', 'Amount', 'Payment Date', 'Payment Method', 'Status', 'Subscription'
            ]);

            // CSV data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->customer->first_name . ' ' . $payment->customer->last_name,
                    $payment->amount,
                    $payment->payment_date->format('Y-m-d'),
                    $payment->payment_method ?? 'N/A',
                    $payment->status,
                    $payment->subscription->package->name ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
