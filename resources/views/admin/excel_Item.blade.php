<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>PR9 HRD</title>
    <link href="{{ url("images/Logo.ico") }}" rel="shortcut icon">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style>
    @font-face {
        font-family: 'Sarabun';
        src: url('{{ public_path("fontexcel/Sarabun-Regular.ttf") }}') format('truetype');
    }

    body {
        font-family: 'Sarabun';
    }

    table {
        border-collapse: collapse;
        font-size: 9pt;
        table-layout: fixed;
    }

    th {
        border: 1px solid;
        text-align: center;
        font-family: 'Sarabun';
    }

    td {
        border: 1px solid;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        font-family: 'Sarabun';
    }
</style>

<body>
    <table width="100%">
        <thead>
            <tr>
                <th colspan="3"><img style="padding: 3px" height="64px" src="{{ public_path("images/Side Logo.png") }}"></th>
                <th colspan="3" style="text-align: center; padding: 5px">ใบลงทะเบียน</th>
            </tr>
            <tr>
                <th>วันที่</th>
                <td colspan="2">{{ $item->slot->slot_name }}</td>
                <th>เวลา</th>
                <td colspan="2">{{ $item->item_name }}</td>
            </tr>
            <tr>
                <th>เรื่อง</th>
                <td colspan="5">{{ $item->slot->project->project_name }}</td>
            </tr>
            <tr>
                <th style="width: 7%;">ลำดับ</th>
                <th style="width: 10%;">รหัสพนักงาน</th>
                <th style="width: 25%;">ชื่อ - สกุล</th>
                <th style="">ตำแหน่ง</th>
                <th style="">แผนก</th>
                <th style="width: 15%">ลายเช็น</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($item->transactions as $index => $transaction)
                <tr>
                    <td style="text-align: center">{{ $index + 1 }}</td>
                    <td style="text-align: center">{{ $transaction->user }}</td>
                    <td>{{ $transaction->userData->name }}</td>
                    <td>
                        {{ $transaction->userData->position }}
                    </td>
                    <td>
                        {{ $transaction->userData->department }}
                    </td>
                    <td>&nbsp;</td>
                </tr>
            @endforeach
            <tr>
                <th style="text-align: right" colspan="5">รวม</th>
                <th style="text-align: center">{{ count($item->transactions) }}</th>
            </tr>
        </tbody>
    </table>
</body>

</html>
