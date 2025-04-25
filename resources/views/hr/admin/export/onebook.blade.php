<table>
    <thead style="word-wrap: break-word;">
        <tr>
            <th>TR2_EmployeeTrainingHistory</th>
            <th colspan="18">หมายเหตุ 1) Column สีแดงตัวอักษรขาว หมายถึง เป็นข้อมูลที่จำเป็นต้องบันทึก 2) ข้อมูลที่นำเข้าระบบเริ่มตั้งแต่ Row #6 3) บรรทัดตัวอย่างให้ลบทิ้ง</th>
        </tr>
        <tr>
            <th style="width: 150px; background: red; color: white">EmployeeID</th>
            <th style="width: 150px; background: red; color: white">CourseID</th>
            <th style="width: 150px; background: red; color: white">StartDate</th>
            <th style="width: 150px; background: red; color: white">EndDate</th>
            <th style="width: 150px;">ClassNo</th>
            <th style="width: 150px;">CourseNameTH</th>
            <th style="width: 150px;">CourseNameEN</th>
            <th style="width: 150px; background: red; color: white">TrainingType</th>
            <th style="width: 150px; background: red; color: white">TrainingMethod</th>
            <th style="width: 150px; background: red; color: white">TrainingHours</th>
            <th style="width: 150px;">TrainingVenue</th>
            <th style="width: 150px;">TrainingCost</th>
            <th style="width: 150px;">TrainingObjective</th>
            <th style="width: 150px;">TrainingProvider</th>
            <th style="width: 150px;">InstructorExternal</th>
            <th style="width: 150px;">TrainingDSDType</th>
            <th style="width: 150px;">DSDCertificateNo</th>
            <th style="width: 150px;">DSDCertificateDate</th>
            <th style="width: 150px;">TrainingResults</th>
            <th style="width: 150px;">Remark</th>
        </tr>
        <tr style="display: none; height: 0px;">
            <th colspan="19"></th>
        </tr>
        <tr>
            <th>รหัสพนักงาน</th>
            <th>รหัสหลักสูตร</th>
            <th>วันที่เริ่มต้น</th>
            <th>วันที่สิ้นสุด</th>
            <th>รุ่นที่</th>
            <th>ชื่อหลักสูตร (ไทย)<br>(กรณีต้องการเปลี่ยนชื่อหลักสูตรและใช้รหัสเดิม)</th>
            <th>ชื่อหลักสูตร (อังกฤษ)<br>(กรณีต้องการเปลี่ยนชื่อหลักสูตรและใช้รหัสเดิม)</th>
            <th>ประเภทการจัดอบรม</th>
            <th>วิธีการอบรม</th>
            <th>จำนวนชั่วโมงอบรม</th>
            <th>สถานที่อบรม</th>
            <th>ค่าใช้จ่ายรวม</th>
            <th>วัตถุประสงค์</th>
            <th>หน่วยงาน/สถาบันที่จัด</th>
            <th>รายชื่อวิทยากร ระบุได้มากกว่า 1 ท่าน</th>
            <th>ประเภทหลักสูตรส่งกรมพัฒน์</th>
            <th>เลขที่เอกสารรับรอง</th>
            <th>วันที่ออกใบรับรอง</th>
            <th>ผลการอบรม</th>
            <th>หมายเหตุ</th>
        </tr>
        <tr>
            <th>Text(50)</th>
            <th>ระบุรหัสข้อมูล</th>
            <th>Date(dd/mm/yyyy)</th>
            <th>Date(dd/mm/yyyy)</th>
            <th>Numeric(99)</th>
            <th>Text(100)</th>
            <th>Text(100)</th>
            <th>Inhouse =ภายใน<br>Public = ภายนอก</th>
            <th>ระบุรหัสข้อมูล</th>
            <th>Numeric(999)</th>
            <th>Text(100)</th>
            <th>9999999.99</th>
            <th>Text(100)</th>
            <th>Text(100)</th>
            <th>Text(400)</th>
            <th>Preparation =ฝึกเตรียมเข้าทำงาน Change =ฝึกเปลี่ยนสาขาอาชีพ RaiseLabor =ฝึกยกระดับฝีมือแรงงาน</th>
            <th>Text(50)</th>
            <th>Date(dd/mm/yyyy)</th>
            <th>1 = ผ่าน<br>0 = ไม่ผ่าน</th>
            <th>Text (100)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->user }}</td>
                <td>{{ $transaction->userData->name }}</td>
                <td>
                    @if ($transaction->hr_approve)
                        {{ date("d/m/Y", strtotime($transaction->hr_approve_datetime)) }}
                    @endif
                </td>
                <td>
                    @if ($transaction->hr_approve)
                        {{ date("d/m/Y", strtotime($transaction->hr_approve_datetime)) }}
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
