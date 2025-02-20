<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\BorrowRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // แสดงรายการอุปกรณ์ทั้งหมด
    public function equipmentList()
    {
        $equipments = Equipment::all()->fresh();
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

    //  สร้างคำขอการยืม (แต่ยังไม่ลดจำนวนอุปกรณ์)
    BorrowRequest::create([
        'user_id' => Auth::id(),
        'equipment_id' => $request->equipment_id,
        'quantity' => $request->quantity,
        'status' => 'pending', // ❗️รออนุมัติจากแอดมิน
        'reason' => $request->reason,
    ]);

    return redirect()->route('user.equipment')->with('success', 'คำขอถูกส่งแล้ว กรุณารอการอนุมัติจากแอดมิน');
    }

    // ฟังก์ชันสำหรับคืนอุปกรณ์
    public function return(Request $request, $id)
{
    $request->validate([
        'id' => 'required|exists:borrow_requests,id',
        'return_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $borrowRequest = BorrowRequest::findOrFail($request->id);

    if ($borrowRequest->status !== 'approved') {
        return redirect()->back()->with('error', 'ไม่สามารถคืนอุปกรณ์ได้');
    }

    $proofPath = $request->file('return_proof')->store('proofs');

    //  อัปเดตสถานะเป็น return_pending
    $borrowRequest->update([
        'status' => 'return_pending',
        'return_proof' => $proofPath,
    ]);

    //  เพิ่มจำนวนอุปกรณ์กลับไป
    $equipment = Equipment::findOrFail($borrowRequest->equipment_id);
    $equipment->increment('quantity', $borrowRequest->quantity);
    $equipment->save(); // บันทึกการเปลี่ยนแปลง

    //  เปลี่ยนจาก user.history เป็น user.equipment เพื่อให้หน้าอัปเดตข้อมูล
    return redirect()->route('user.equipment')->with('success', 'การคืนอุปกรณ์เสร็จสมบูรณ์');
}


    // แสดงประวัติการยืม
    public function history(Request $request)
    {
        $selectedStatus = $request->get('status', 'all'); // กำหนดค่าเริ่มต้นเป็น 'all'
        $query = BorrowRequest::where('user_id', Auth::id());

        if ($selectedStatus !== 'all') {
            $query->where('status', $selectedStatus);
        }

        $borrowRequests = $query->get();

        return view('user.history', compact('borrowRequests', 'selectedStatus'));
    }

    // แจ้งอุปกรณ์ชำรุด
    public function reportDamage(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'required|string|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $request->file('image')->store('damage_reports');

        BorrowRequest::create([
            'user_id' => Auth::id(),
            'equipment_id' => $request->equipment_id,
            'quantity' => $request->quantity,
            'status' => 'damage_pending',
            'description' => $request->description,
            'image_path' => $imagePath,
        ]);

        // เพิ่มข้อความแจ้งเตือน
        return redirect()->route('user.history')->with('success', 'ส่งคำร้องแจ้งอุปกรณ์ชำรุดเรียบร้อยแล้ว กรุณารอการตรวจสอบจากแอดมิน');
    }

    // แอดมินอนุมัติอุปกรณ์ชำรุด และลดจำนวนจริง
    public function approveDamage($id)
    {
        $borrowRequest = BorrowRequest::findOrFail($id);

        if ($borrowRequest->status !== 'damage_pending') {
            return redirect()->back()->with('error', 'คำขอนี้ไม่สามารถอนุมัติได้');
        }

        $equipment = Equipment::findOrFail($borrowRequest->equipment_id);

        if ($equipment->quantity >= $borrowRequest->quantity) {
            $equipment->decrement('quantity', $borrowRequest->quantity);
        } else {
            return redirect()->back()->with('error', 'จำนวนอุปกรณ์ไม่เพียงพอสำหรับการตัดออก');
        }

        $borrowRequest->update(['status' => 'damage_approved']);

        return redirect()->back()->with('success', 'อนุมัติคำขอและลดจำนวนอุปกรณ์เรียบร้อยแล้ว');
    }

    // แสดงฟอร์มแจ้งอุปกรณ์ชำรุด
    public function showReportDamageForm()
    {
        $equipments = Equipment::all();
        return view('user.report_damage', compact('equipments'));
    }
    public function pendingRequests()
    {
        $pendingRequests = BorrowRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        return view('user.pending', compact('pendingRequests'));
    }
    public function editPendingRequest($id)
    {
        $borrowRequest = BorrowRequest::where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        return view('user.pending_edit', compact('borrowRequest'));
    }
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

        // ตรวจสอบจำนวนที่อัปเดต ว่ามีเพียงพอหรือไม่
        $difference = $request->quantity - $borrowRequest->quantity;
        if ($difference > 0 && $equipment->quantity < $difference) {
            return redirect()->back()->withErrors(['quantity' => 'จำนวนอุปกรณ์ไม่เพียงพอ']);
        }

        // อัปเดตจำนวนอุปกรณ์ที่มีอยู่
        $equipment->decrement('quantity', max($difference, 0));
        $equipment->increment('quantity', max(-$difference, 0));

        // อัปเดตข้อมูลคำขอ
        $borrowRequest->update([
            'quantity' => $request->input('quantity'),
            'reason' => $request->input('reason'),
        ]);

        return redirect()->route('user.pending')->with('success', 'แก้ไขคำขอเรียบร้อยแล้ว');
    }
    // แสดงฟอร์มคืนอุปกรณ์
public function showReturnForm($id)
{
    $borrowedItems = BorrowRequest::where('user_id', Auth::id())
                                  ->where('equipment_id', $id)
                                  ->where('status', 'approved')
                                  ->with('equipment') // ดึงข้อมูลอุปกรณ์ที่ถูกยืมมา
                                  ->get();

    if ($borrowedItems->isEmpty()) {
        return redirect()->route('user.equipment')->with('error', 'ไม่มีอุปกรณ์ที่สามารถคืนได้');
    }

    $equipment = Equipment::findOrFail($id);
    return view('user.return', compact('equipment', 'borrowedItems'));
}

}