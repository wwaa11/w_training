<table>
    <thead style="word-wrap: break-word;">
        <tr>
            <th style="height: 50px;" colspan="3">&nbsp;</th>
            <th align="center" style="font-size: 24pt; font-weight: bold;" colspan="4">รายชื่อวิทยากร</th>
        </tr>
        <tr>
            <th style="font-size: 14pt;" colspan="7">วิทยากรสอนหลักสูตร : {{ $project->project_name }}</th>
        </tr>
        <tr>
            <th style="font-size: 14pt;" colspan="3">วันที่ : {{ $project_date }}</th>
            <th style="font-size: 14pt;">เวลา : {{ implode(" , ", $project_time) }}</th>
            <th style="font-size: 14pt;" colspan="3">สถานที่ฝึกอบรม : {{ $project_location }}</th>
        </tr>
        <tr>
            <th align="center">ลำดับ</th>
            <th align="center">วันที่</th>
            <th align="center">รหัสพนักงาน</th>
            <th align="center">ชื่อ - สกุล</th>
            <th align="center">ตำแหน่ง</th>
            <th align="center">แผนก</th>
            <th align="center">ลายเซ็นต์</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dates as $date)
            @foreach ($date->lectures as $index => $lecture)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $date->date_title }}</td>
                    <td>{{ $lecture->user->userid }}</td>
                    <td>{{ $lecture->user->name }}</td>
                    <td>{{ $lecture->user->position }}</td>
                    <td>{{ $lecture->user->department }}</td>
                    <td style="width: 160px; height: 60px;">&nbsp;</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
