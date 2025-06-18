<table>
    <thead style="word-wrap: break-word;">
        <tr>
            <th style="height: 50px;" colspan="3">&nbsp;</th>
            <th align="center" style="font-size: 24pt; font-weight: bold;" colspan="4">รายชื่อวิทยากร</th>
        </tr>
        <tr>
            <th style="font-size: 14pt;" colspan="7">วิทยากรสอนหลักสูตร : {{ $project->title }}</th>
        </tr>
        <tr>
            <th style="font-size: 14pt;" colspan="3">วันที่ : {{ $project_date }}</th>
            <th style="font-size: 14pt;">เวลา : {{ implode(" , ", $project_time) }}</th>
            <th style="font-size: 14pt;" colspan="3">สถานที่ฝึกอบรม : {{ $project->location }}</th>
        </tr>
        <tr>
            <th align="center">ลำดับ</th>
            <th align="center">วันที่</th>
            <th align="center">รหัสพนักงงาน</th>
            <th align="center">ชื่อ - สกุล</th>
            <th align="center">ตำแหน่ง</th>
            <th align="center">แผนก</th>
            <th align="center">ลายเซ็นต์</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($project->dateData as $date)
            @foreach ($date->lecturesData as $index => $lecture)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $date->title }}</td>
                    <td>{{ $lecture->userData->userid }}</td>
                    <td>{{ $lecture->userData->name }}</td>
                    <td>{{ $lecture->userData->position }}</td>
                    <td>{{ $lecture->userData->department }}</td>
                    <td style="width: 160px; height: 60px;">&nbsp;</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
