<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\BorrowRequest;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;


class UserController extends Controller
{
    // แสดงรายการอุปกรณ์ทั้งหมด
    public function equipmentList()
    {
        $equipments = Equipment::all();
        return view('user.equipment', compact('equipments'));
    }

    // ฟังก์ชันสำหรับยืมอุปกรณ์
    public function borrow(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);

        if ($equipment->quantity < $request->quantity) {
            return redirect()->back()->withErrors(['quantity' => 'จำนวนอุปกรณ์ไม่เพียงพอ']);
        }

        // ลดจำนวนคงเหลือของอุปกรณ์
        $equipment->decrement('quantity', $request->quantity);

        // บันทึกคำขอการยืม
        BorrowRequest::create([
            'user_id' => Auth::id(),
            'equipment_id' => $request->equipment_id,
            'quantity' => $request->quantity,
            'status' => 'pending',
            'reason' => $request->reason,
        ]);

        return redirect()->route('user.equipment')->with('success', 'คำขอยืมได้ถูกส่งเรียบร้อยแล้ว');
    }

    // ฟังก์ชันสำหรับคืนอุปกรณ์
    public function return(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:borrow_requests,id',
            'return_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $borrowRequest = BorrowRequest::findOrFail($request->id);

        if ($borrowRequest->status !== 'approved') {
            return redirect()->back()->with('error', 'ไม่สามารถคืนอุปกรณ์ได้');
        }

        $borrowRequest->update([
            'status' => 'return_pending',
            'return_proof' => $request->file('return_proof')->store('proofs'),
        ]);

        $equipment = Equipment::findOrFail($borrowRequest->equipment_id);
        $equipment->increment('quantity', $borrowRequest->quantity);

        return redirect()->route('user.history')->with('success', 'การคืนอุปกรณ์เสร็จสมบูรณ์');
    }

    // แสดงประวัติการยืม
    public function history()
    {
        $borrowRequests = BorrowRequest::where('user_id', Auth::id())->get();
        return view('user.history', compact('borrowRequests'));
    }

    // แสดงคำขอที่รออนุมัติ
    public function pendingRequests()
    {
        $pendingRequests = BorrowRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        return view('user.pending', compact('pendingRequests'));
    }

    // ฟอร์มแก้ไขคำขอที่รออนุมัติ
    public function editPendingRequest($id)
    {
        $borrowRequest = BorrowRequest::where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        return view('user.pending.edit', compact('borrowRequest'));
    }

    // อัปเดตคำขอที่รออนุมัติ
    public function updatePendingRequest(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $borrowRequest = BorrowRequest::where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $equipment = Equipment::findOrFail($borrowRequest->equipment_id);

        // อัปเดตจำนวนคงเหลือในอุปกรณ์
        if ($request->quantity > $borrowRequest->quantity) {
            $difference = $request->quantity - $borrowRequest->quantity;

            if ($equipment->quantity < $difference) {
                return redirect()->back()->withErrors(['quantity' => 'จำนวนอุปกรณ์ไม่เพียงพอ']);
            }

            $equipment->decrement('quantity', $difference);
        } else {
            $difference = $borrowRequest->quantity - $request->quantity;
            $equipment->increment('quantity', $difference);
        }

        $borrowRequest->update([
            'quantity' => $request->input('quantity'),
            'reason' => $request->input('reason'),
        ]);

        return redirect()->route('user.pending')->with('success', 'แก้ไขคำขอเรียบร้อยแล้ว');
    }

    // ยกเลิกคำขอ
    public function cancelRequest($id)
    {
        $borrowRequest = BorrowRequest::findOrFail($id);

        if ($borrowRequest->status === 'pending') {
            $equipment = Equipment::findOrFail($borrowRequest->equipment_id);

            $equipment->increment('quantity', $borrowRequest->quantity);

            $borrowRequest->delete();

            return redirect()->route('user.pending')->with('success', 'คำขอถูกยกเลิกและจำนวนอุปกรณ์คืนเรียบร้อยแล้ว');
        }

        return redirect()->route('user.pending')->with('error', 'ไม่สามารถยกเลิกคำขอนี้ได้');
    }
    public function exportHistoryAsPDF()
    {
        // ดึงข้อมูลการยืม-คืนจากฐานข้อมูล
        $borrowRequests = BorrowRequest::where('user_id', auth()->id())->get();

        // สร้าง PDF จาก View `user.history`
        $pdf = PDF::loadView('user.history', compact('borrowRequests'));

        // ดาวน์โหลดไฟล์ PDF
        return $pdf->download('borrow_history.pdf');
    }
}
