<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ storage_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }
        body {
            font-family: "THSarabunNew", sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>ประวัติการยืม-คืนครุภัณฑ์</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ชื่อผู้ใช้งาน</th>
                <th>ชื่ออุปกรณ์</th>
                <th>จำนวน</th>
                <th>เหตุผลในการยืม</th>
                <th>สถานะ</th>
                <th>วันที่</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowRequests as $request)
            <tr>
                <td>{{ $request->id }}</td>
                <td>{{ $request->user->name }}</td>
                <td>{{ $request->equipment->name }}</td>
                <td>{{ $request->quantity }}</td>
                <td>{{ $request->reason }}</td>
                <td>
                    @switch($request->status)
                        @case('return_pending') {{ 'รอการคืน' }} @break
                        @case('returned') {{ 'คืนแล้ว' }} @break
                        @case('pending') {{ 'กำลังรออนุมัติ' }} @break
                        @case('approved') {{ 'อนุมัติแล้ว' }} @break
                        @case('rejected') {{ 'ปฏิเสธคำขอ' }} @break
                        @case('damage_pending') {{ 'รอการตรวจสอบการชำรุด' }} @break
                        @case('damage_approved') {{ 'อนุมัติคำขอซ่อม' }} @break
                        @case('damage_rejected') {{ 'ปฏิเสธคำขอซ่อม' }} @break
                        @default {{ ucfirst($request->status) }}
                    @endswitch
                </td>
                <td>{{ $request->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
