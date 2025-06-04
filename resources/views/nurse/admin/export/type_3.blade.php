<table>
    <thead style="word-wrap: break-word;">
        <tr>
            <th style="height: 50px;" colspan="3">&nbsp;</th>
            <th align="center" style="font-size: 24pt; font-weight: bold;" colspan="7">ใบบันทึกฝึกอบรม ส่วนกลางโรงพยาบาล</th>
        </tr>
        <tr>
            <th style="font-size: 14pt;" colspan="10">หลักสูตร {{ $project->title }} {{ $project->detail }}</th>
        </tr>
        <tr>
            <td colspan="2">ที่มา </td>
            <td colspan="8">
                <span style="font-size: 20px">&#9723;</span> จัดในแผน ลำดับที่ ................................................. (อ้างอิงลำดับที่ในแผนการฝึกอบรมประจำปี)
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="8">
                <span style="font-size: 20px">&#9723;</span> จัดแทนในแผน เรื่อง ................................................................ เนื่องจาก..................................................
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="8">
                <span style="font-size: 20px">&#9723;</span> จัดนอกแผน เนื่องจาก ...........................................................................................................................
            </td>
        </tr>
        <tr>
            <td colspan="10">วันที่ฝึกอบรม : ..................... เวลาเริ่ม : ................ ถึง : .............. คิดเป็นประวัติการฝึกอบรม เท่ากับ.......... ชั่วโมง …........นาที</td>
        </tr>
        @foreach ($lectures as $index => $lecture)
            <tr>
                <td colspan="2">
                    @if ($index == 0)
                        วิทยากร :
                    @endif
                </td>
                <td colspan="3">{{ $index + 1 }} ชื่อ {{ $lecture["name"] }}</td>
                <td colspan="3">ตำแหน่ง {{ $lecture["position"] }}</td>
                <td colspan="2">รหัส {{ $lecture["userid"] }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="10">เนื้อหาการฝึกอบรม : กรุณาแนบเอกสารประกอบการฝึกอบรม</td>
        </tr>
        <tr>
            <td colspan="10">รายชื่อผู้เข้ารับการฝึกอบรม :</td>
        </tr>
        <tr>
            <th align="center" rowspan="2">รหัส</th>
            <th align="center" rowspan="2">ชื่อ - สกุล</th>
            <th align="center" rowspan="2">ตำแหน่ง</th>
            <th align="center" rowspan="2">แผนก</th>
            <th align="center" rowspan="2">ลายเซ็น</th>
            <th align="center" rowspan="2">วันที่ประเมิน</th>
            <th align="center" rowspan="2">วิธีการประเมิน</th>
            <th align="center" colspan="3">ระดับการประเมิน</th>
        </tr>
        <tr>
            <th align="center">1</th>
            <th align="center">2</th>
            <th align="center">3</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $index => $transaction)
            <tr>
                <td>{{ $transaction->user_id }}</td>
                <td style="width: 200px;">{{ $transaction->userData->name }}</td>
                <td style="width: 200px;">{{ $transaction->userData->position }}</td>
                <td style="width: 200px;">{{ $transaction->userData->department }}</td>
                <td style="width: 160px; height: 60px;">&nbsp;</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="10">วิธีการประเมินผล : P = ฝึกปฏิบัติจริง O = สังเกตุการปฏิบัติงาน I = ถาม - ตอบ</td>
        </tr>
        <tr>
            <td colspan="2">ระดับประเมินผล :</td>
            <td colspan="2">3 = ปฏิบัติงานและแก้ไขปัญหาได้</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2">2 = ปฏิบัติงานและแก้ไขปัญหาได้บ้าง</td>
            <td colspan="6" align="center">ลงชื่อหัวหน้าแผนก/หน่วย .....................................................</td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2">1 = การปฏิบัติงานยังต้องปรับปรุงแก้ไข</td>
            <td colspan="6" align="center">แผนกสรรหาและพัฒนาบุคลากร รับเอกสาร</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td colspan="6" align="center"> ลงชื่อ …............................. วันที่รับเอกสาร ..........................</td>
        </tr>
        <tr>
            <td colspan="10" align="center"> กรุณาส่งแบบฟอร์มนี้ ภายใน 15 วัน นับจากวันที่ฝึกอบรม หากเกินกำหนด ขอสงวนสิทธิ "ไม่รับ " บันทึกประวัติการฝึกอบรม</td>
        </tr>
    </tfoot>
</table>
