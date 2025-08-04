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
                <th colspan="2">{{ $time->date->date_title }}</th>
                <th>เวลา</th>
                <th colspan="4">{{ $time->time_title }} ({{ \Carbon\Carbon::parse($time->time_start)->format("H:i") }} - {{ \Carbon\Carbon::parse($time->time_end)->format("H:i") }})</th>
            </tr>
            <tr>
                <th>เรื่อง</th>
                <th colspan="7">{{ $time->date->project->project_name }}</th>
            </tr>
            @if ($time->date->date_location)
                <tr>
                    <th>สถานที่</th>
                    <th colspan="7">{{ $time->date->date_location }}</th>
                </tr>
            @endif
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
            @foreach ($registrations as $index => $registration)
                <tr>
                    <td style="text-align: center">{{ $index + 1 }}</td>
                    <td style="text-align: center">{{ $registration->user->userid ?? "N/A" }}</td>
                    <td style="padding-left: 5px">{{ $registration->user->name ?? "N/A" }}</td>
                    <td>
                        {{ $registration->user->position ?? "N/A" }}
                    </td>
                    <td>
                        {{ $registration->user->department ?? "N/A" }}
                    </td>
                    <td style="text-align: center">
                        @if ($registration->user->sign !== null && $registration->attend_datetime !== null)
                            <img width="80px" src="{{ $registration->user->sign }}">
                        @endif
                    </td>
                    <td style="text-align: center">
                        @if ($registration->attend_datetime !== null)
                            {{ date("d/m/y H:i", strtotime($registration->attend_datetime)) }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                    <td style="text-align: center">
                        @if ($registration->approve_datetime !== null)
                            {{ date("d/m/y H:i", strtotime($registration->approve_datetime)) }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr>
                <th style="text-align: right" colspan="6">รวม</th>
                <th style="text-align: center" colspan="2">{{ count($registrations) }}</th>
            </tr>
        </tbody>
    </table>
</body>

</html>
