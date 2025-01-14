<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function create()
    {
        // Retrieve the last `user_item_id` for the logged-in user
        $lastItemId = Auth::user()->incomes()->max('item_id');
    
        // Calculate the next `user_item_id` (start from 1 if no budgets exist)
        $nextItemId = $lastItemId ? $lastItemId + 1 : 1;

        $incomes = Auth::user()->incomes;

        return view('/incomes/income', compact('nextItemId'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'time' => 'nullable',
            'date' => 'nullable|date',
            'amount' => 'nullable|numeric',
        ]);

        $validatedData['user_id'] = Auth::id();
        $lastItemId = Auth::user()->incomes()->max('item_id');
        $validatedData['item_id'] = $lastItemId ? $lastItemId + 1 : 1;

        Income::create($validatedData);

        return redirect()->route('income.create')->with('success', 'Income saved successfully.');
    }

    public function history()
    {
        $incomes = Auth::user()->incomes;

        return view('/incomes/incomehistory', compact('incomes'));
    }

}
