<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Staff;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::with('staff')->paginate(10);
        $staff = Staff::all();
        return view('managements.attendance', compact('attendance', 'staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
        ]);

        // Find the attendance record
        $attendance = Attendance::where('staff_id', $validated['staff_id'])
                                ->where('date', $validated['date'])
                                ->first();

        if ($attendance) {
            // If attendance exists, remove it (toggle)
            $attendance->delete();

            return response()->json(['success' => true, 'removed' => true]);
        } else {
            // If not, create it
            Attendance::create([
                'staff_id' => $validated['staff_id'],
                'date' => $validated['date'],
            ]);

            return response()->json(['success' => true, 'added' => true]);
        }
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendance')->with('success', 'Attendance record deleted successfully.');
    }
}