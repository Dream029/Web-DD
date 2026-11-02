<?php

namespace App\Http\Controllers;

use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $requests = RepairRequest::with(['requester', 'technician'])->latest()->get();
        } elseif ($user->role === 'technician') {
            $requests = RepairRequest::where('technician_id', $user->id)
                ->orWhereNull('technician_id')
                ->with(['requester'])
                ->latest()->get();
        } else {
            $requests = RepairRequest::where('user_id', $user->id)->latest()->get();
        }

        return view('repairs.index', compact('requests'));
    }

    public function create()
    {
        return view('repairs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('repairs', 'public');
        }

        RepairRequest::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'location' => $request->location,
            'description' => $request->description,
            'priority' => $request->priority,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('repairs.index')->with('success', 'แจ้งซ่อมเรียบร้อยแล้ว');
    }

    public function show(RepairRequest $repair)
    {
        $technicians = User::where('role', 'technician')->get();
        return view('repairs.show', compact('repair', 'technicians'));
    }

    public function updateStatus(Request $request, RepairRequest $repair)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'technician_id' => 'nullable|exists:users,id',
            'resolution_note' => 'nullable|string',
        ]);

        $repair->update([
            'status' => $request->status,
            'technician_id' => $request->technician_id ?? $repair->technician_id,
            'resolution_note' => $request->resolution_note,
        ]);

        return redirect()->route('repairs.show', $repair->id)->with('success', 'อัปเดตสถานะงานเรียบร้อย');
    }
}
