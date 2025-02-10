<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\BorrowRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $currentBorrows = BorrowRequest::where('user_id', $request->user()->id)
            ->where('status', 'approved') // เฉพาะคำขอยืมที่ได้รับการอนุมัติ
            ->get();

        return view('profile.edit', [
            'user' => $request->user(),
            'currentBorrows' => $currentBorrows, // ส่งข้อมูลรายการยืมไปยัง View
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // อัปเดตข้อมูลผู้ใช้
        $request->user()->fill([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'address' => $request->input('address'),
        ]);

        // รีเซ็ตการยืนยันอีเมลหากมีการเปลี่ยนแปลง
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'อัปเดตข้อมูลสำเร็จ');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Validation rules for updating the user's profile.
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:15'], // เพิ่มหมายเลขโทรศัพท์
            'address' => ['nullable', 'string', 'max:255'],     // เพิ่มที่อยู่
        ];
    }
}
