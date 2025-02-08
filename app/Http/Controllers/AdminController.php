<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\Equipment;

class AdminController extends Controller
{
    public function dashboard()
    {
        $equipments = Equipment::all(); // ดึงข้อมูลทั้งหมดจากตาราง Equipment
        return view('admin.dashboard', compact('equipments')); // ส่งตัวแปร $equipments ไปยัง View
    }

    public function create()
    {
        return view('admin.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'details' => 'required|string',
        ]);

        Equipment::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'อุปกรณ์ถูกเพิ่มเรียบร้อยแล้ว!');
    }


    public function approvalList()
    {
        $borrowRequests = BorrowRequest::where('status', 'pending')->get();
        return view('admin.approval', compact('borrowRequests'));
    }
    public function approve($id)
    {
        $borrowRequest = BorrowRequest::findOrFail($id);

        if ($borrowRequest->status === 'pending') {
            // อนุมัติคำขอยืม
            $borrowRequest->update(['status' => 'approved']);

            // ลดจำนวนอุปกรณ์ที่เหลือ
            $equipment = Equipment::findOrFail($borrowRequest->equipment_id);
            $equipment->update([
                'quantity' => $equipment->quantity - $borrowRequest->quantity,
            ]);
        }

        return redirect()->route('admin.approval')->with('success', 'Request approved.');
    }

    public function borrowHistory()
    {
        // ดึงข้อมูล Borrow Requests ทั้งหมด
        $borrowRequests = BorrowRequest::with(['user', 'equipment'])->get();
        return view('admin.borrow-history', compact('borrowRequests'));
    }
    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('admin.equipment.edit', compact('equipment'));
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();
        return redirect()->route('admin.dashboard')->with('success', 'ลบข้อมูลอุปกรณ์สำเร็จแล้ว!');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'details' => 'required|string',
        ]);

        $equipment = Equipment::findOrFail($id);

        $equipment->update([
            'name' => $request->input('name'),
            'quantity' => $request->input('quantity'),
            'details' => $request->input('details'),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'อัปเดตข้อมูลอุปกรณ์เรียบร้อยแล้ว!');
    }
    public function reject($id)
    {
        $borrowRequest = BorrowRequest::findOrFail($id);

        if ($borrowRequest->status === 'pending') {
            // ดึงข้อมูลอุปกรณ์ที่เกี่ยวข้อง
            $equipment = Equipment::findOrFail($borrowRequest->equipment_id);

            // เพิ่มจำนวนคงเหลือกลับไป
            $equipment->increment('quantity', $borrowRequest->quantity);

            // เปลี่ยนสถานะเป็น rejected
            $borrowRequest->update([
                'status' => 'rejected',
            ]);

            return redirect()->route('admin.approval')->with('success', 'คำขอถูกปฏิเสธเรียบร้อยแล้ว');
        }

        return redirect()->route('admin.approval')->with('error', 'ไม่สามารถปฏิเสธคำขอนี้ได้');
    }
}
