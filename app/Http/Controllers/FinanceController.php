<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function transactions(Request $request)
    {
        if ($request->user()->role === 'student') {
            return response()->json(
                Transaction::where('user_id', $request->user()->id)
                    ->orderBy('created_at', 'desc')
                    ->get()
            );
        }

        return response()->json(
            Transaction::with('user')
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function summary()
    {
        $all = Transaction::all();
        $revenue = $all->where('type', 'revenue')->sum('amount');
        $expenses = $all->where('type', 'expense')->sum('amount');
        $pending = $all->where('status', 'pending')->sum('amount');

        return response()->json([
            'revenue' => $revenue,
            'expenses' => $expenses,
            'profit' => $revenue - $expenses,
            'pending' => $pending,
            'totalTransactions' => $all->count(),
        ]);
    }

    public function store(Request $request)
    {
        $transaction = Transaction::create($request->all());
        return response()->json($transaction);
    }

    public function destroy($id)
    {
        Transaction::destroy($id);
        return response()->json(['deleted' => true]);
    }
}
