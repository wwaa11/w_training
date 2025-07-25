<table>
    <thead style="word-wrap: break-word;">
        <tr>
            <th style="height: 50px;" colspan="5">&nbsp;</th>
            <th align="center" style="font-size: 24pt; font-weight: bold;" colspan="5">ใบบันทึกฝึกอบรม ส่วนกลางโรงพยาบาล</th>
        </tr>
        <tr>
            <th style="font-size: 14pt;" colspan="10">หลักสูตร English Training Program</th>
        </tr>
        <tr>
            <th style="font-size: 14pt;" colspan="10">วัน {{ $dateFull["day"] }} เดือน {{ $dateFull["month"] }} ปี {{ $dateFull["year"] }}</th>
        </tr>
        <tr>
            <th align="center" rowspan="2">ลำดับ</th>
            <th align="center" rowspan="2">เลขประจำตัวประชาชน</th>
            <th align="center" rowspan="2">รหัส</th>
            <th align="center" rowspan="2">ชื่อ - สกุล</th>
            <th align="center" rowspan="2">ตำแหน่ง</th>
            <th align="center" rowspan="2">แผนก</th>
            <th align="center" colspan="2">เพศ</th>
            <th align="center" colspan="2">ลายเซ็น</th>
        </tr>
        <tr>
            <th align="center" style="width: 50px;">ชาย</th>
            <th align="center" style="width: 50px;">หญิง</th>
            <th align="center" style="width: 160px;">08.00-12.00 น.</th>
            <th align="center" style="width: 160px;">13.00-16.00 น.</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($exportRows as $index => $transaction)
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td data-type="text" style="width: 200px;">{{ (string) $transaction["id_card"] }}</td>
                <td>{{ $transaction["user_id"] }}</td>
                <td style="width: 200px;">{{ $transaction["name"] }}</td>
                <td style="width: 200px;">{{ $transaction["position"] }}</td>
                <td style="width: 200px;">{{ $transaction["department"] }}</td>
                <td align="center" style="width: 50px; font-size: 14pt">&nbsp;
                    @if ($transaction["gender"] == "ชาย")
                        &#10004;
                    @endif
                </td>
                <td align="center" style="width: 50px; font-size: 14pt">&nbsp;
                    @if ($transaction["gender"] == "หญิง")
                        &#10004;
                    @endif
                </td>
                <td style="width: 160px; height: 60px;">&nbsp;</td>
                <td style="width: 160px; height: 60px;">&nbsp;</td>
            </tr>
        @endforeach
    </tbody>
</table>
