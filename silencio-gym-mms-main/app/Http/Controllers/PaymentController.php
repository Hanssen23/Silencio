<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('member');
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('member', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%");
        }
        
        if ($request->filled('plan_type')) {
            $query->where('plan_type', $request->plan_type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date')) {
            $query->whereDate('payment_date', $request->date);
        }
        
        $payments = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('membership.payments.index', compact('payments'));
    }

    public function create()
    {
        return view('membership.payments.create');
    }

    public function store(Request $request)
    {
        // Payment creation logic
        return redirect()->route('membership.payments.index')->with('success', 'Payment created successfully');
    }

    public function show($id)
    {
        $payment = Payment::with('member')->findOrFail($id);
        return view('membership.payments.show', compact('payment'));
    }

    public function edit($id)
    {
        $payment = Payment::with('member')->findOrFail($id);
        return view('membership.payments.edit', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        // Payment update logic
        return redirect()->route('membership.payments.index')->with('success', 'Payment updated successfully');
    }

    public function destroy($id)
    {
        // Payment deletion logic
        return redirect()->route('membership.payments.index')->with('success', 'Payment deleted successfully');
    }

    public function print($id)
    {
        $payment = Payment::with('member')->findOrFail($id);
        return view('membership.payments.receipt', compact('payment'));
    }

    public function exportCsv(Request $request)
    {
        $query = Payment::with('member');
        
        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('member', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%");
        }
        
        if ($request->filled('plan_type')) {
            $query->where('plan_type', $request->plan_type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date')) {
            $query->whereDate('payment_date', $request->date);
        }
        
        $payments = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'payments_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Payment ID',
                'Member Name',
                'Member Email',
                'Member Number',
                'Plan Type',
                'Duration',
                'Amount',
                'Payment Date',
                'Payment Time',
                'Membership Start',
                'Membership End',
                'Status',
                'Notes'
            ]);
            
            // CSV data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->member ? $payment->member->full_name : 'N/A',
                    $payment->member ? $payment->member->email : 'N/A',
                    $payment->member ? $payment->member->member_number : 'N/A',
                    ucfirst($payment->plan_type),
                    ucfirst($payment->duration_type),
                    $payment->amount,
                    $payment->payment_date ? $payment->payment_date->format('Y-m-d') : 'N/A',
                    $payment->payment_time ?? 'N/A',
                    $payment->membership_start_date ? $payment->membership_start_date->format('Y-m-d') : 'N/A',
                    $payment->membership_expiration_date ? $payment->membership_expiration_date->format('Y-m-d') : 'N/A',
                    $payment->status,
                    $payment->notes ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function details($id)
    {
        $payment = Payment::with('member')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'payment' => $payment,
            'membership_period' => [
                'start_date' => $payment->membership_start_date,
                'end_date' => $payment->membership_expiration_date
            ]
        ]);
    }
}
