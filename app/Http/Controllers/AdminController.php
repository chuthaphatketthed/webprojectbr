<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\Equipment;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function dashboard()
    {
        $equipments = Equipment::all();
        return view('admin.dashboard', compact('equipments'));
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
            $borrowRequest->update(['status' => 'approved']);

            $equipment = Equipment::findOrFail($borrowRequest->equipment_id);
            if ($equipment->quantity >= $borrowRequest->quantity) {
                $equipment->decrement('quantity', $borrowRequest->quantity);
            } else {
                return redirect()->back()->with('error', 'จำนวนอุปกรณ์ไม่เพียงพอสำหรับการยืม');
            }
        }

        return redirect()->route('admin.approval')->with('success', 'อนุมัติคำขอเรียบร้อยแล้ว');
    }

    public function borrowHistory(Request $request)
    {
        $selectedStatus = $request->get('status', 'all'); // ค่าดีฟอลต์เป็น "all"
        $query = BorrowRequest::with(['user', 'equipment']);

        if ($selectedStatus !== 'all') {
            $query->where('status', $selectedStatus);
        }

        $borrowRequests = $query->get();

        return view('admin.borrow-history', compact('borrowRequests', 'selectedStatus'));
    }

    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('admin.equipment.edit', compact('equipment'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'details' => 'required|string',
        ]);

        $equipment = Equipment::findOrFail($id);
        $equipment->update($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'อัปเดตข้อมูลอุปกรณ์เรียบร้อยแล้ว!');
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();
        return redirect()->route('admin.dashboard')->with('success', 'ลบข้อมูลอุปกรณ์สำเร็จแล้ว!');
    }

    public function reject($id)
    {
        $borrowRequest = BorrowRequest::findOrFail($id);

        if ($borrowRequest->status === 'pending') {
            $equipment = Equipment::findOrFail($borrowRequest->equipment_id);
            $equipment->increment('quantity', $borrowRequest->quantity);
            $borrowRequest->update(['status' => 'rejected']);
            return redirect()->route('admin.approval')->with('success', 'ปฏิเสธคำขอเรียบร้อยแล้ว');
        }

        return redirect()->route('admin.approval')->with('error', 'ไม่สามารถปฏิเสธคำขอนี้ได้');
    }

    public function showDamageRequests()
    {
        $damageRequests = BorrowRequest::where('status', 'damage_pending')->with('equipment')->get();
        return view('admin.damage_requests', compact('damageRequests'));
    }

    public function approveDamageRequest($id)
    {
        $damageRequest = BorrowRequest::findOrFail($id);
        $equipment = Equipment::findOrFail($damageRequest->equipment_id);

        if ($equipment->quantity >= $damageRequest->quantity) {
            $equipment->decrement('quantity', $damageRequest->quantity);
            $damageRequest->update(['status' => 'damage_approved']);
        } else {
            return redirect()->back()->with('error', 'จำนวนอุปกรณ์ไม่เพียงพอที่จะลด');
        }

        return redirect()->route('admin.damage.requests')->with('success', 'ยืนยันคำร้องชำรุดเรียบร้อยแล้ว');
    }

    public function rejectDamageRequest($id)
    {
        $damageRequest = BorrowRequest::findOrFail($id);
        $damageRequest->update(['status' => 'damage_rejected']);

        return redirect()->route('admin.damage.requests')->with('success', 'ปฏิเสธคำร้องชำรุดเรียบร้อยแล้ว');
    }
    public function exportBorrowHistoryPDF()
    {
        $borrowRequests = BorrowRequest::with(['user', 'equipment'])->get();

        $pdf = Pdf::loadView('admin.borrow-history-pdf', compact('borrowRequests'));

        return $pdf->stream('borrow-history.pdf');
    }
}
