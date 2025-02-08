<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'equipment_id',
        'quantity',
        'status',
        'reason',
        'return_proof',
        'description', // เพิ่มรายละเอียดการแจ้งชำรุด
        'image_path', // เพิ่มที่อยู่ของรูปภาพการแจ้งชำรุด
    ];

    public $timestamps = true; // ใช้ timestamps สำหรับ created_at และ updated_at

    // ความสัมพันธ์กับผู้ใช้
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ความสัมพันธ์กับอุปกรณ์
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
