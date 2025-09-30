<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('member')->orderBy('created_at', 'desc')->paginate(15);
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

    public function exportCsv()
    {
        // CSV export logic
        return response()->download('payments.csv');
    }
}
