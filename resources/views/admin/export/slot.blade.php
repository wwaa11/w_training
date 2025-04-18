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
        font-style: normal;
        font-weight: normal;
        src: url('{{ public_path("fontexcel/Sarabun-Regular.ttf") }}') format('truetype');
    }

    @font-face {
        font-family: 'Sarabun';
        font-style: normal;
        font-weight: bold;
        src: url('{{ public_path("fontexcel/Sarabun-Bold.ttf") }}') format('truetype');
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
    }

    td {
        border: 1px solid;
        overflow-wrap: anywhere;
    }
</style>

<body>
    <table width="100%">
        <thead>
            <tr>
                <th colspan="3"><img style="padding: 3px" height="64px" src="{{ public_path("images/Side Logo.png") }}"></th>
                <th colspan="5" style="text-align: center; padding: 5px">ใบลงทะเบียน</th>
            </tr>
            <tr>
                <th>วันที่</th>
                <th colspan="2">{{ $item->slot->slot_name }}</th>
                <th>เวลา</th>
                <th colspan="4">{{ $item->item_name }}</th>
            </tr>
            <tr>
                <th>เรื่อง</th>
                <th colspan="7">{{ $item->slot->project->project_name }}</th>
            </tr>
            <tr>
                <th style="">ลำดับ</th>
                <th style="">รหัสพนักงาน</th>
                <th style="">ชื่อ - สกุล</th>
                <th style="">ตำแหน่ง</th>
                <th style="">แผนก</th>
                <th style="">ลายเช็น</th>
                <th style="width: 70px;">CHECK IN</th>
                <th style="">HR</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($item->transactions as $index => $transaction)
                <tr>
                    <td style="text-align: center">{{ $index + 1 }}</td>
                    <td style="text-align: center">{{ $transaction->user }}</td>
                    <td style="padding-left: 5px">{{ $transaction->userData->name }}</td>
                    <td>
                        {{ $transaction->userData->position }}
                    </td>
                    <td>
                        {{ $transaction->userData->department }}
                    </td>
                    <td style="text-align: center">
                        @if ($transaction->userData->sign !== null && $transaction->checkin_datetime !== null)
                            <img width="80px" src="{{ $transaction->userData->sign }}">
                        @endif
                    </td>
                    <td style="text-align: center">
                        @if ($transaction->checkin_datetime !== null)
                            {{ date("d/m/y H:i", strtotime($transaction->checkin_datetime)) }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                    <td style="text-align: center">
                        @if ($transaction->hr_approve_datetime !== null)
                            {{ date("d/m/y H:i", strtotime($transaction->hr_approve_datetime)) }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr>
                <th style="text-align: right" colspan="6">รวม</th>
                <th style="text-align: center" colspan="2">{{ count($item->transactions) }}</th>
            </tr>
        </tbody>
    </table>
</body>

</html>
