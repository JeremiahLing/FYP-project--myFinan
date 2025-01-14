<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function create()
    {
        // Retrieve the last `user_item_id` for the logged-in user
        $lastItemId = Auth::user()->budgets()->max('item_id');
    
        // Calculate the next `user_item_id` (start from 1 if no budgets exist)
        $nextItemId = $lastItemId ? $lastItemId + 1 : 1;

        $budgets = Auth::user()->budgets;

        return view('/budgets/budget', compact('nextItemId')); // Pass `nextId` to the view
    }

    // Handle form submission
    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'time' => 'nullable',
            'date' => 'nullable|date',
            'amount' => 'nullable|numeric',
        ]);

        // Calculate the next `user_item_id` for the user
        $validatedData['user_id'] = Auth::id();
        $lastItemId = Auth::user()->budgets()->max('item_id');
        $validatedData['item_id'] = $lastItemId ? $lastItemId + 1 : 1;

        // Save the data to the database
        Budget::create($validatedData);

        // Redirect with a success message
        return redirect()->route('budget.create')->with('success', 'Budget saved successfully.');
    }

    //Update budget
    public function update(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'item_id' => 'required|exists:budgets,item_id',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'time' => 'required|date_format:H:i:s',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);

        // Find the budget item by ID
        $budget = Budget::where('item_id', $request->item_id)->firstOrFail();

        // Update the budget item
        $budget->item_name = $request->item_name;
        $budget->quantity = $request->quantity;
        $budget->description = $request->description;
        $budget->time = $request->time;
        $budget->date = $request->date;
        $budget->amount = $request->amount;
        $budget->save();

        // Redirect back with success message
        return response()->json(['success' => true, 'message' => 'Item updated successfully.']);
    }

    public function history()
    {
        // Retrieve all records from the `budgets` table
        $budgets = Auth::user()->budgets;

        // Pass the records to the view
        return view('/budgets/budgethistory', compact('budgets'));
    }

}
