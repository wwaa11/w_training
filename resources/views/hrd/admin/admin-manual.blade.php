@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-3 pb-16">
        <div class="rounded-xl bg-white p-4 shadow-lg sm:p-6">
            <div class="mb-4 flex items-center justify-between sm:mb-6">
                <h1 class="text-xl font-bold text-gray-800 sm:text-2xl lg:text-3xl">คู่มือการใช้งานระบบ HRD สำหรับผู้ดูแลระบบ</h1>
                <a class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-2 font-semibold text-white transition duration-200 hover:bg-blue-700 sm:gap-2 sm:px-6 sm:py-3" href="{{ route("hrd.admin.index") }}">
                    <i class="fas fa-arrow-left text-sm sm:text-base"></i>
                    <span class="text-xs sm:text-sm">กลับไปหน้าหลัก</span>
                </a>
            </div>

            <!-- Quick Reference Card -->
            <div class="mb-6 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 p-4 text-white sm:mb-8 sm:p-6">
                <h2 class="mb-3 text-lg font-semibold sm:mb-4 sm:text-xl">📋 Quick Reference</h2>
                <div class="grid grid-cols-1 gap-3 sm:gap-4 md:grid-cols-3">
                    <div class="rounded-lg bg-white/20 p-3 backdrop-blur-sm sm:p-4">
                        <h3 class="mb-1.5 text-sm font-semibold sm:mb-2 sm:text-base">🚀 การเริ่มต้น</h3>
                        <ul class="space-y-1 text-xs sm:text-sm">
                            <li>• สร้างโปรเจกต์ใหม่</li>
                            <li>• กำหนดวันที่และเวลา</li>
                            <li>• เปิดให้ลงทะเบียน</li>
                        </ul>
                    </div>
                    <div class="rounded-lg bg-white/20 p-3 backdrop-blur-sm sm:p-4">
                        <h3 class="mb-1.5 text-sm font-semibold sm:mb-2 sm:text-base">👥 การจัดการ</h3>
                        <ul class="space-y-1 text-xs sm:text-sm">
                            <li>• อนุมัติการลงทะเบียน</li>
                            <li>• จัดที่นั่งอัตโนมัติ</li>
                            <li>• จัดการผลการประเมิน</li>
                        </ul>
                    </div>
                    <div class="rounded-lg bg-white/20 p-3 backdrop-blur-sm sm:p-4">
                        <h3 class="mb-1.5 text-sm font-semibold sm:mb-2 sm:text-base">📊 การรายงาน</h3>
                        <ul class="space-y-1 text-xs sm:text-sm">
                            <li>• ส่งออก DBD Report</li>
                            <li>• สร้าง Onebook</li>
                            <li>• ดูสถิติการเข้าร่วม</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Table of Contents -->
            <div class="mb-6 rounded-lg bg-gray-50 p-4 sm:mb-8 sm:p-6">
                <h2 class="mb-3 text-lg font-semibold text-gray-800 sm:mb-4 sm:text-xl">สารบัญ</h2>
                <ul class="space-y-1.5 text-blue-600 sm:space-y-2">
                    <li><a class="text-xs hover:underline sm:text-sm" href="#overview">1. ภาพรวมระบบ</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#project-types">2. ประเภทโปรเจกต์</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#project-management">3. การจัดการโปรเจกต์</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#registration-management">4. การจัดการการลงทะเบียน</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#approval-system">5. ระบบการอนุมัติ</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#seat-management">6. การจัดการที่นั่ง</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#result-management">7. การจัดการผลการประเมิน</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#export-features">8. การส่งออกข้อมูล</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#user-management">9. การจัดการผู้ใช้</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#group-management">10. การจัดการกลุ่ม</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#troubleshooting">11. การแก้ไขปัญหา</a></li>
                </ul>
            </div>

            <!-- 1. ภาพรวมระบบ -->
            <section class="mb-6 sm:mb-8" id="overview">
                <h2 class="mb-3 text-xl font-bold text-gray-800 sm:mb-4 sm:text-2xl">1. ภาพรวมระบบ</h2>
                <div class="rounded-lg bg-blue-50 p-4 sm:p-6">
                    <p class="mb-3 text-sm text-gray-700 sm:mb-4 sm:text-base">
                        ระบบ HRD (Human Resource Development) เป็นระบบจัดการการฝึกอบรมและพัฒนาบุคลากร
                        ที่ช่วยให้ผู้ดูแลระบบสามารถจัดการโปรเจกต์การฝึกอบรม การลงทะเบียน การอนุมัติ และการติดตามผลได้อย่างมีประสิทธิภาพ
                        ระบบได้รับการออกแบบให้ใช้งานง่ายทั้งบนคอมพิวเตอร์และมือถือ
                    </p>
                    <div class="grid grid-cols-1 gap-3 sm:gap-4 md:grid-cols-3">
                        <div class="rounded-lg bg-white p-3 shadow sm:p-4">
                            <h3 class="mb-1.5 text-sm font-semibold text-blue-600 sm:mb-2 sm:text-base">การจัดการโปรเจกต์</h3>
                            <p class="text-xs text-gray-600 sm:text-sm">สร้าง แก้ไข และลบโปรเจกต์การฝึกอบรม</p>
                        </div>
                        <div class="rounded-lg bg-white p-3 shadow sm:p-4">
                            <h3 class="mb-1.5 text-sm font-semibold text-green-600 sm:mb-2 sm:text-base">การลงทะเบียน</h3>
                            <p class="text-xs text-gray-600 sm:text-sm">จัดการการลงทะเบียนของผู้เข้าร่วม</p>
                        </div>
                        <div class="rounded-lg bg-white p-3 shadow sm:p-4">
                            <h3 class="mb-1.5 text-sm font-semibold text-purple-600 sm:mb-2 sm:text-base">การรายงานและประเมิน</h3>
                            <p class="text-xs text-gray-600 sm:text-sm">ส่งออกข้อมูลและจัดการผลการประเมิน</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. ประเภทโปรเจกต์ -->
            <section class="mb-6 sm:mb-8" id="project-types">
                <h2 class="mb-3 text-xl font-bold text-gray-800 sm:mb-4 sm:text-2xl">2. ประเภทโปรเจกต์</h2>
                <div class="space-y-3 sm:space-y-4">
                    <div class="rounded-lg border border-gray-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-blue-600 sm:mb-3 sm:text-lg">Single Registration (ลงทะเบียน 1 ครั้ง)</h3>
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-gray-700 sm:ml-6 sm:space-y-2">
                            <li>ผู้ใช้สามารถลงทะเบียนได้เพียง 1 ครั้งต่อโปรเจกต์</li>
                            <li>เหมาะสำหรับกิจกรรมที่ต้องการจำกัดจำนวนผู้เข้าร่วม</li>
                            <li>ระบบจะตรวจสอบการลงทะเบียนซ้ำอัตโนมัติ</li>
                        </ul>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-green-600 sm:mb-3 sm:text-lg">Multiple Registration (ลงทะเบียนได้มากกว่า 1 ครั้ง)</h3>
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-gray-700 sm:ml-6 sm:space-y-2">
                            <li>ผู้ใช้สามารถลงทะเบียนได้หลายครั้งในโปรเจกต์เดียวกัน</li>
                            <li>เหมาะสำหรับกิจกรรมที่จัดหลายรอบหรือหลายวัน</li>
                            <li>สามารถเลือกช่วงเวลาและวันที่ที่ต้องการได้</li>
                        </ul>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-purple-600 sm:mb-3 sm:text-lg">No Registration (ไม่ต้องลงทะเบียน)</h3>
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-gray-700 sm:ml-6 sm:space-y-2">
                            <li>ผู้ใช้ไม่ต้องลงทะเบียนล่วงหน้า</li>
                            <li>สามารถเข้าร่วมได้โดยตรงในวันงาน</li>
                            <li>เหมาะสำหรับกิจกรรมเปิดกว้างหรือการประชุมทั่วไป</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 3. การจัดการโปรเจกต์ -->
            <section class="mb-8" id="project-management">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">3. การจัดการโปรเจกต์</h2>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การสร้างโปรเจกต์ใหม่</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>คลิกปุ่ม "สร้างโปรเจกต์ใหม่" ในหน้าหลัก</li>
                            <li>กรอกข้อมูลพื้นฐานของโปรเจกต์:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li><strong>ชื่อโปรเจกต์:</strong> ชื่อที่แสดงให้ผู้ใช้เห็น (สูงสุด 255 ตัวอักษร)</li>
                                    <li><strong>รายละเอียด:</strong> คำอธิบายเพิ่มเติมของโปรเจกต์</li>
                                    <li><strong>ประเภทโปรเจกต์:</strong>
                                        <ul class="ml-4 mt-1 list-disc">
                                            <li><strong>Single:</strong> ลงทะเบียนได้ 1 ครั้งต่อโปรเจกต์</li>
                                            <li><strong>Multiple:</strong> ลงทะเบียนได้หลายครั้งในโปรเจกต์เดียวกัน</li>
                                            <li><strong>Attendance:</strong> ไม่ต้องลงทะเบียนล่วงหน้า</li>
                                        </ul>
                                    </li>
                                    <li><strong>การจัดที่นั่ง:</strong> เปิด/ปิดการจัดที่นั่งอัตโนมัติ</li>
                                    <li><strong>การจัดกลุ่ม:</strong> เปิด/ปิดการจัดกลุ่มผู้เข้าร่วม</li>
                                </ul>
                            </li>
                            <li>กำหนดช่วงเวลาลงทะเบียน:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li><strong>วันที่เริ่มต้นการฝึกอบรม:</strong> วันแรกที่ผู้ใช้สามารถลงทะเบียนได้</li>
                                    <li><strong>วันที่สิ้นสุดการฝึกอบรม:</strong> วันสุดท้ายที่ผู้ใช้สามารถลงทะเบียนได้</li>
                                    <li><strong>ลงทะเบียนวันนี้:</strong> อนุญาตให้ลงทะเบียนในวันเดียวกัน</li>
                                </ul>
                            </li>
                            <li>เพิ่มวันที่และช่วงเวลาของกิจกรรม:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li><strong>ชื่อวันที่:</strong> ชื่อของวันกิจกรรม (เช่น "วันที่ 1")</li>
                                    <li><strong>รายละเอียด:</strong> คำอธิบายเพิ่มเติมของวันนั้น</li>
                                    <li><strong>สถานที่:</strong> สถานที่จัดกิจกรรม</li>
                                    <li><strong>วันที่:</strong> วันจริงของกิจกรรม</li>
                                    <li><strong>ช่วงเวลา:</strong> เวลาเริ่ม-สิ้นสุดของแต่ละรอบ</li>
                                    <li><strong>จำกัดจำนวน:</strong> จำกัดจำนวนผู้เข้าร่วมต่อรอบ</li>
                                </ul>
                            </li>
                            <li>เพิ่มลิงก์ (ถ้ามี):
                                <ul class="ml-6 mt-2 list-disc">
                                    <li><strong>ชื่อลิงก์:</strong> ชื่อที่แสดงให้ผู้ใช้เห็น</li>
                                    <li><strong>URL:</strong> ลิงก์ที่ต้องการ</li>
                                    <li><strong>จำกัดเวลา:</strong> จำกัดเวลาการเข้าถึงลิงก์</li>
                                </ul>
                            </li>
                            <li>คลิก "สร้างโปรเจกต์" เพื่อบันทึก</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-blue-50 p-3">
                            <p class="text-sm text-blue-700">
                                <strong>หมายเหตุ:</strong> ระบบจะสร้างโปรเจกต์พร้อมกับวันที่ ช่วงเวลา และลิงก์ทั้งหมดในครั้งเดียว
                                หากเกิดข้อผิดพลาด ระบบจะลบข้อมูลทั้งหมดที่สร้างไว้แล้ว
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การแก้ไขโปรเจกต์</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>คลิก "ดูรายละเอียด" ในรายการโปรเจกต์</li>
                            <li>คลิกปุ่ม "แก้ไขโปรเจกต์"</li>
                            <li>แก้ไขข้อมูลที่ต้องการ:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li><strong>ข้อมูลพื้นฐาน:</strong> ชื่อ รายละเอียด ประเภทโปรเจกต์</li>
                                    <li><strong>การตั้งค่า:</strong> การจัดที่นั่ง การจัดกลุ่ม ช่วงเวลาลงทะเบียน</li>
                                    <li><strong>วันที่และเวลา:</strong> เพิ่ม แก้ไข หรือลบวันที่และช่วงเวลา</li>
                                    <li><strong>ลิงก์:</strong> เพิ่ม แก้ไข หรือลบลิงก์ที่เกี่ยวข้อง</li>
                                </ul>
                            </li>
                            <li>คลิก "อัปเดตโปรเจกต์" เพื่อบันทึกการเปลี่ยนแปลง</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-yellow-50 p-3">
                            <p class="text-sm text-yellow-800">
                                <strong>หมายเหตุ:</strong> การแก้ไขโปรเจกต์ที่มีผู้ลงทะเบียนแล้วอาจส่งผลต่อการลงทะเบียนที่มีอยู่
                                ระบบจะแสดงคำเตือนหากมีการเปลี่ยนแปลงที่อาจส่งผลกระทบ
                            </p>
                        </div>
                        <div class="mt-3 rounded-lg bg-blue-50 p-3">
                            <p class="text-sm text-blue-700">
                                <strong>ฟีเจอร์พิเศษ:</strong> ระบบจะแสดงข้อมูลโปรเจกต์ในรูปแบบที่ง่ายต่อการแก้ไข
                                รวมถึงการแสดงวันที่และช่วงเวลาทั้งหมดในหน้าเดียว
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การลบโปรเจกต์</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>คลิก "ดูรายละเอียด" ในรายการโปรเจกต์</li>
                            <li>คลิกปุ่ม "ลบโปรเจกต์"</li>
                            <li>ยืนยันการลบในหน้าต่างที่ปรากฏ</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-red-50 p-3">
                            <p class="text-sm text-red-800">
                                <strong>คำเตือน:</strong> การลบโปรเจกต์จะลบข้อมูลทั้งหมดที่เกี่ยวข้องอย่างถาวร รวมถึง:
                            </p>
                            <ul class="ml-4 mt-2 list-disc text-xs text-red-700">
                                <li>ข้อมูลการลงทะเบียนและประวัติการเข้าร่วมทั้งหมด</li>
                                <li>วันที่และช่วงเวลาของกิจกรรมทั้งหมด</li>
                                <li>การจัดที่นั่งและการจัดกลุ่ม</li>
                                <li>ผลการประเมินและหัวข้อการประเมิน</li>
                                <li>ลิงก์ที่เกี่ยวข้องทั้งหมด</li>
                                <li>ข้อมูลการเข้าร่วมและการลงชื่อ</li>
                            </ul>
                            <p class="mt-2 text-xs text-red-700">
                                <strong>หมายเหตุ:</strong> การลบนี้ไม่สามารถกู้คืนได้ กรุณาตรวจสอบให้แน่ใจก่อนดำเนินการ
                                ระบบจะบันทึกการลบในล็อกเพื่อการตรวจสอบ
                            </p>
                        </div>
                        <div class="mt-3 rounded-lg bg-blue-50 p-3">
                            <p class="text-sm text-blue-700">
                                <strong>การบันทึกล็อก:</strong> ระบบจะบันทึกข้อมูลโปรเจกต์ที่ถูกลบไว้ในล็อก
                                พร้อมรายละเอียดผู้ที่ทำการลบและเวลาที่ลบ
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. การจัดการการลงทะเบียน -->
            <section class="mb-8" id="registration-management">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">4. การจัดการการลงทะเบียน</h2>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การดูรายการลงทะเบียน</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>คลิก "ดูรายละเอียด" ในรายการโปรเจกต์</li>
                            <li>คลิกแท็บ "การลงทะเบียน"</li>
                            <li>ดูรายการผู้ลงทะเบียนทั้งหมด พร้อมสถานะ:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li><strong>รอการอนุมัติ:</strong> การลงทะเบียนที่ยังไม่ได้รับการอนุมัติ</li>
                                    <li><strong>ได้รับการอนุมัติ:</strong> การลงทะเบียนที่ได้รับการอนุมัติแล้ว</li>
                                    <li><strong>วันที่ลงทะเบียน:</strong> วันและเวลาที่ผู้ใช้ลงทะเบียน</li>
                                    <li><strong>วันที่และเวลา:</strong> วันที่และช่วงเวลาที่เลือก</li>
                                </ul>
                            </li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-blue-50 p-3">
                            <p class="text-sm text-blue-700">
                                <strong>ฟีเจอร์:</strong> สามารถกรองตามสถานะ วันที่ และช่วงเวลาได้
                                รวมถึงการค้นหาตามชื่อหรือรหัสพนักงาน
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การเพิ่มการลงทะเบียนด้วยตนเอง</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การลงทะเบียน" ของโปรเจกต์</li>
                            <li>คลิกปุ่ม "เพิ่มการลงทะเบียน"</li>
                            <li>พิมพ์รหัสพนักงานที่ต้องการลงทะเบียน</li>
                            <li>เลือกวันที่และช่วงเวลาที่ต้องการ Checkin , Approval:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li>พิมพ์วันที่ในรูปแบบ YYYY-MM-DD</li>
                                    <li>พิมพ์ช่วงเวลาในรูปแบบ HH:MM</li>
                                    <li>ตรวจสอบจำนวนที่นั่งที่เหลือ</li>
                                </ul>
                            </li>
                            <li>คลิก "บันทึก" เพื่อเพิ่มการลงทะเบียน</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-green-50 p-3">
                            <p class="text-sm text-green-700">
                                <strong>ข้อดี:</strong> สามารถเพิ่มการลงทะเบียนให้ผู้ใช้ที่อาจมีปัญหาในการลงทะเบียนด้วยตนเอง
                                หรือสำหรับกรณีพิเศษที่ต้องการจัดการโดยตรง
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การแก้ไขการลงทะเบียน</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การลงทะเบียน" ของโปรเจกต์</li>
                            <li>คลิกปุ่ม "แก้ไข" ข้างการลงทะเบียนที่ต้องการ</li>
                            <li>แก้ไขข้อมูลที่ต้องการ:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li>เปลี่ยนวันที่หรือช่วงเวลา</li>
                                    <li>เปลี่ยนสถานะการอนุมัติ</li>
                                </ul>
                            </li>
                            <li>คลิก "อัปเดต" เพื่อบันทึกการเปลี่ยนแปลง</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การลบการลงทะเบียน</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การลงทะเบียน" ของโปรเจกต์</li>
                            <li>คลิกปุ่ม "ลบ" ข้างการลงทะเบียนที่ต้องการ</li>
                            <li>ยืนยันการลบ</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-yellow-50 p-3">
                            <p class="text-sm text-yellow-800">
                                <strong>หมายเหตุ:</strong> การลบการลงทะเบียนจะทำให้ที่นั่งที่ถูกจองกลับมาเป็นว่าง
                                และผู้ใช้จะไม่สามารถเข้าร่วมกิจกรรมในรอบนั้นได้
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 5. ระบบการอนุมัติ -->
            <section class="mb-8" id="approval-system">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">5. ระบบการอนุมัติ</h2>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การอนุมัติการลงทะเบียน</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การอนุมัติ" ของโปรเจกต์</li>
                            <li>ดูรายการการลงทะเบียนที่รอการอนุมัติ:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li>แสดงรายการผู้ลงทะเบียนที่ยังไม่ได้รับการอนุมัติ</li>
                                    <li>แสดงข้อมูลผู้ลงทะเบียน วันที่ลงทะเบียน และวันที่/เวลาที่เลือก</li>
                                    <li>สามารถกรองตามวันที่ ช่วงเวลา หรือสถานะได้</li>
                                </ul>
                            </li>
                            <li>เลือกการลงทะเบียนที่ต้องการอนุมัติ:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li>เลือกทีละรายการ หรือเลือกหลายรายการพร้อมกัน</li>
                                    <li>ตรวจสอบข้อมูลก่อนการอนุมัติ</li>
                                </ul>
                            </li>
                            <li>คลิกปุ่ม "อนุมัติ" หรือ "อนุมัติทั้งหมด"</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-green-50 p-3">
                            <p class="text-sm text-green-700">
                                <strong>ข้อดี:</strong> การอนุมัติจะทำให้ผู้ใช้สามารถเข้าร่วมกิจกรรมได้
                                และระบบจะจัดที่นั่งให้อัตโนมัติ (หากเปิดใช้งาน)
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การอนุมัติแบบกลุ่ม</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การอนุมัติ" ของโปรเจกต์</li>
                            <li>เลือกการลงทะเบียนหลายรายการที่ต้องการอนุมัติ</li>
                            <li>คลิกปุ่ม "อนุมัติทั้งหมด"</li>
                            <li>ยืนยันการอนุมัติในหน้าต่างที่ปรากฏ</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-blue-50 p-3">
                            <p class="text-sm text-blue-700">
                                <strong>ฟีเจอร์:</strong> สามารถเลือกอนุมัติตามเงื่อนไข เช่น อนุมัติทั้งหมดในวันที่เดียวกัน
                                หรืออนุมัติตามแผนกหรือตำแหน่ง
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การยกเลิกการอนุมัติ</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การอนุมัติ" ของโปรเจกต์</li>
                            <li>ดูรายการการลงทะเบียนที่ได้รับการอนุมัติแล้ว</li>
                            <li>คลิกปุ่ม "ยกเลิกการอนุมัติ" ข้างการลงทะเบียนที่ต้องการ</li>
                            <li>ยืนยันการยกเลิกการอนุมัติ</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-yellow-50 p-3">
                            <p class="text-sm text-yellow-800">
                                <strong>ผลกระทบ:</strong> การยกเลิกการอนุมัติจะทำให้การลงทะเบียนกลับไปอยู่ในสถานะ "รอการอนุมัติ"
                                และสามารถอนุมัติใหม่ได้ในภายหลัง หากมีการจัดที่นั่งแล้ว จะยกเลิกการจัดที่นั่งด้วย
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-blue-50 p-4">
                    <h3 class="mb-2 font-semibold text-blue-800">สถานะการลงทะเบียน</h3>
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                        <div class="flex items-center gap-2">
                            <div class="h-3 w-3 rounded-full bg-yellow-400"></div>
                            <span class="text-sm text-gray-700">รอการอนุมัติ</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-3 w-3 rounded-full bg-green-400"></div>
                            <span class="text-sm text-gray-700">ได้รับการอนุมัติ</span>
                        </div>
                    </div>
                    <div class="mt-3 rounded-lg bg-gray-50 p-3">
                        <p class="text-xs text-gray-600">
                            <strong>หมายเหตุ:</strong> ระบบไม่มีสถานะ "ถูกปฏิเสธ" การลงทะเบียนที่ไม่ได้รับการอนุมัติจะอยู่ในสถานะ "รอการอนุมัติ"
                            และสามารถอนุมัติได้ในภายหลัง หรือลบการลงทะเบียนออกหากไม่ต้องการ
                        </p>
                    </div>
                </div>

                <div class="mt-6 rounded-lg bg-purple-50 p-4">
                    <h3 class="mb-2 font-semibold text-purple-800">คุณสมบัติพิเศษของระบบการอนุมัติ</h3>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การแจ้งเตือน</h4>
                            <p class="text-xs text-gray-600">แจ้งเตือนเมื่อมีการลงทะเบียนใหม่ที่รอการอนุมัติ</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การบันทึกล็อก</h4>
                            <p class="text-xs text-gray-600">บันทึกการอนุมัติและยกเลิกการอนุมัติในล็อก</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การกรองขั้นสูง</h4>
                            <p class="text-xs text-gray-600">กรองตามวันที่ ช่วงเวลา แผนก หรือสถานะ</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การจัดการแบบกลุ่ม</h4>
                            <p class="text-xs text-gray-600">อนุมัติหรือยกเลิกการอนุมัติหลายรายการพร้อมกัน</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 6. การจัดการที่นั่ง -->
            <section class="mb-8" id="seat-management">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">6. การจัดการที่นั่ง</h2>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การกำหนดที่นั่งอัตโนมัติ</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การจัดการที่นั่ง" ของโปรเจกต์</li>
                            <li>คลิกปุ่ม "กำหนดที่นั่งอัตโนมัติ"</li>
                            <li>ระบบจะจัดที่นั่งให้ผู้ลงทะเบียนทั้งหมด (ทั้งที่ได้รับการอนุมัติและยังไม่ได้รับการอนุมัติ):
                                <ul class="ml-6 mt-2 list-disc">
                                    <li>จัดที่นั่งตามลำดับการลงทะเบียน</li>
                                    <li>พิจารณาแผนกและตำแหน่งในการจัดที่นั่ง</li>
                                    <li>หลีกเลี่ยงการจัดที่นั่งซ้ำ</li>
                                    <li>คำนวณจำนวนที่นั่งที่เหมาะสม</li>
                                </ul>
                            </li>
                            <li>ตรวจสอบผลลัพธ์และปรับแต่งตามต้องการ</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-green-50 p-3">
                            <p class="text-sm text-green-700">
                                <strong>ข้อดี:</strong> ประหยัดเวลาในการจัดที่นั่ง ลดความผิดพลาด และจัดที่นั่งได้อย่างเป็นระบบ
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การกำหนดที่นั่งด้วยตนเอง</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การจัดการที่นั่ง" ของโปรเจกต์</li>
                            <li>เลือกผู้ใช้จากรายการ:

                            </li>

                            <li>คลิก + เพื่อเพิ่มที่นั่ง</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การลบการกำหนดที่นั่ง</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การจัดการที่นั่ง" ของโปรเจกต์</li>
                            <li>คลิกปุ่ม X ข้างการกำหนดที่นั่งที่ต้องการ</li>
                            <li>ยืนยันการลบ</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-yellow-50 p-3">
                            <p class="text-sm text-yellow-800">
                                <strong>หมายเหตุ:</strong> การลบการกำหนดที่นั่งจะทำให้ที่นั่งนั้นกลับมาเป็นว่าง
                                และสามารถจัดให้ผู้ใช้อื่นได้
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การล้างที่นั่งทั้งหมด</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การจัดการที่นั่ง" ของโปรเจกต์</li>
                            <li>เลือกช่วงเวลาที่ต้องการล้างที่นั่ง</li>
                            <li>คลิกปุ่ม "ล้างที่นั่งทั้งหมด"</li>
                            <li>ยืนยันการล้างที่นั่ง</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-red-50 p-3">
                            <p class="text-sm text-red-800">
                                <strong>คำเตือน:</strong> การล้างที่นั่งทั้งหมดจะลบการกำหนดที่นั่งทั้งหมดในช่วงเวลานั้น
                                ใช้เฉพาะเมื่อต้องการเริ่มต้นใหม่เท่านั้น
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-purple-50 p-4">
                    <h3 class="mb-2 font-semibold text-purple-800">คุณสมบัติพิเศษของการจัดการที่นั่ง</h3>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การจัดที่นั่งอัจฉริยะ</h4>
                            <p class="text-xs text-gray-600">พิจารณาแผนก ตำแหน่ง และความเหมาะสมในการจัดที่นั่ง</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การตรวจสอบความขัดแย้ง</h4>
                            <p class="text-xs text-gray-600">ตรวจสอบการจัดที่นั่งซ้ำหรือความขัดแย้งอัตโนมัติ</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การแสดงผลแบบ Visual</h4>
                            <p class="text-xs text-gray-600">แสดงที่นั่งในรูปแบบแผนผังที่เข้าใจง่าย</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การจัดการแบบกลุ่ม</h4>
                            <p class="text-xs text-gray-600">จัดที่นั่งหลายคนพร้อมกันหรือล้างที่นั่งหลายรายการ</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 7. การจัดการผลการประเมิน -->
            <section class="mb-6 sm:mb-8" id="result-management">
                <h2 class="mb-3 text-xl font-bold text-gray-800 sm:mb-4 sm:text-2xl">7. การจัดการผลการประเมิน</h2>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-gray-700 sm:mb-3 sm:text-lg">การนำเข้าข้อมูลผลการประเมิน</h3>
                    <div class="rounded-lg bg-gray-50 p-3 sm:p-4">
                        <ol class="ml-4 list-decimal space-y-1.5 text-sm text-gray-700 sm:ml-6 sm:space-y-2">
                            <li>ไปที่หน้า "การจัดการผลการประเมิน" ของโปรเจกต์</li>
                            <li>คลิกปุ่ม "ดาวน์โหลดเทมเพลต" เพื่อดาวน์โหลดไฟล์ Excel</li>
                            <li>กรอกข้อมูลผลการประเมินในไฟล์ Excel:
                                <ul class="ml-4 mt-1 list-disc">
                                    <li>รหัสพนักงานของผู้เข้าร่วม</li>
                                    <li>คะแนนในแต่ละหัวข้อการประเมิน</li>
                                    <li>หมายเหตุหรือข้อเสนอแนะ (ถ้ามี)</li>
                                </ul>
                            </li>
                            <li>อัปโหลดไฟล์ Excel ที่กรอกข้อมูลแล้ว</li>
                            <li>ตรวจสอบข้อมูลและยืนยันการนำเข้า</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-gray-700 sm:mb-3 sm:text-lg">การล้างข้อมูลผลการประเมิน</h3>
                    <div class="rounded-lg bg-gray-50 p-3 sm:p-4">
                        <ol class="ml-4 list-decimal space-y-1.5 text-sm text-gray-700 sm:ml-6 sm:space-y-2">
                            <li>ไปที่หน้า "การจัดการผลการประเมิน" ของโปรเจกต์</li>
                            <li>คลิกปุ่ม "ล้างข้อมูลผลการประเมิน"</li>
                            <li>ยืนยันการล้างข้อมูล</li>
                        </ol>
                        <div class="mt-3 rounded-lg bg-red-50 p-2">
                            <p class="text-xs text-red-700">
                                <strong>คำเตือน:</strong> การล้างข้อมูลจะลบผลการประเมินทั้งหมดอย่างถาวร
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-purple-50 p-3 sm:p-4">
                    <h3 class="mb-2 text-sm font-semibold text-purple-800 sm:text-base">คุณสมบัติของระบบผลการประเมิน</h3>
                    <div class="grid grid-cols-1 gap-2 sm:gap-3 md:grid-cols-2">
                        <div class="rounded-lg bg-white p-2.5 sm:p-3">
                            <h4 class="mb-1 text-xs font-semibold text-purple-600 sm:text-sm">การนำเข้าข้อมูล</h4>
                            <p class="text-xs text-gray-600 sm:text-sm">นำเข้าข้อมูลผลการประเมินจากไฟล์ Excel</p>
                        </div>
                        <div class="rounded-lg bg-white p-2.5 sm:p-3">
                            <h4 class="mb-1 text-xs font-semibold text-purple-600 sm:text-sm">การจัดการข้อมูล</h4>
                            <p class="text-xs text-gray-600 sm:text-sm">ล้างและจัดการข้อมูลผลการประเมิน</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 8. การส่งออกข้อมูล -->
            <section class="mb-8" id="export-features">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">8. การส่งออกข้อมูล</h2>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">วิธีการส่งออกข้อมูล</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "รายละเอียดโปรเจกต์"</li>
                            <li>เลือกประเภทรายงานที่ต้องการส่งออก</li>
                            <li>คลิกปุ่ม "ส่งออก" หรือ "ดาวน์โหลด"</li>
                            <li>เลือกรูปแบบไฟล์ (Excel, PDF)</li>
                            <li>รอการประมวลผลและดาวน์โหลดไฟล์</li>
                        </ol>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-blue-600">รายงาน DBD</h3>
                        <p class="mb-3 text-gray-700">ส่งออกข้อมูลในรูปแบบที่เหมาะสมสำหรับการรายงาน DBD</p>
                        <ul class="ml-6 list-disc space-y-1 text-sm text-gray-600">
                            <li>ข้อมูลการลงทะเบียนทั้งหมด</li>
                            <li>สถิติการเข้าร่วมและเปอร์เซ็นต์</li>
                            <li>รายละเอียดโปรเจกต์และกิจกรรม</li>
                            <li>ข้อมูลผู้เข้าร่วมตามแผนกและตำแหน่ง</li>
                            <li>รายงานตามช่วงเวลาและวันที่</li>
                        </ul>
                        <div class="mt-3 rounded-lg bg-blue-50 p-2">
                            <p class="text-xs text-blue-700">
                                <strong>รูปแบบ:</strong> Excel (.xlsx) พร้อมการจัดรูปแบบที่เหมาะสม
                            </p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-green-600">รายงาน Onebook</h3>
                        <p class="mb-3 text-gray-700">ส่งออกข้อมูลสำหรับการทำ Onebook</p>
                        <ul class="ml-6 list-disc space-y-1 text-sm text-gray-600">
                            <li>รายชื่อผู้เข้าร่วมทั้งหมด</li>
                            <li>ข้อมูลการลงทะเบียนและสถานะ</li>
                            <li>รายละเอียดกิจกรรมและตารางเวลา</li>
                            <li>ข้อมูลการเข้าร่วมและการลงชื่อ</li>
                            <li>สถิติการเข้าร่วมตามกลุ่ม</li>
                        </ul>
                        <div class="mt-3 rounded-lg bg-green-50 p-2">
                            <p class="text-xs text-green-700">
                                <strong>รูปแบบ:</strong> Excel (.xlsx) พร้อมการจัดกลุ่มข้อมูล
                            </p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-purple-600">รายงานตามวันที่</h3>
                        <p class="mb-3 text-gray-700">ส่งออกข้อมูลแยกตามวันที่ของกิจกรรม</p>
                        <ul class="ml-6 list-disc space-y-1 text-sm text-gray-600">
                            <li>รายชื่อผู้เข้าร่วมในแต่ละวัน</li>
                            <li>สถิติการเข้าร่วมรายวัน</li>
                            <li>รายละเอียดกิจกรรมรายวัน</li>
                            <li>ข้อมูลการลงชื่อและการเข้าร่วม</li>
                            <li>รายงานตามช่วงเวลาในแต่ละวัน</li>
                        </ul>
                        <div class="mt-3 rounded-lg bg-purple-50 p-2">
                            <p class="text-xs text-purple-700">
                                <strong>รูปแบบ:</strong> Excel (.xlsx) แยกตามวันที่
                            </p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-orange-600">รายงาน PDF</h3>
                        <p class="mb-3 text-gray-700">ส่งออกรายงานในรูปแบบ PDF</p>
                        <ul class="ml-6 list-disc space-y-1 text-sm text-gray-600">
                            <li>รายงานการเข้าร่วมแบบสรุป</li>
                            <li>รายชื่อผู้เข้าร่วมพร้อมที่นั่ง</li>
                            <li>สถิติการเข้าร่วมและกราฟ</li>
                            <li>รายงานตามช่วงเวลา</li>
                            <li>ข้อมูลโปรเจกต์และกิจกรรม</li>
                        </ul>
                        <div class="mt-3 rounded-lg bg-orange-50 p-2">
                            <p class="text-xs text-orange-700">
                                <strong>รูปแบบ:</strong> PDF (.pdf) พร้อมการจัดรูปแบบที่สวยงาม
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-lg bg-purple-50 p-4">
                    <h3 class="mb-2 font-semibold text-purple-800">คุณสมบัติพิเศษของการส่งออกข้อมูล</h3>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การกรองข้อมูล</h4>
                            <p class="text-xs text-gray-600">กรองข้อมูลก่อนส่งออกตามวันที่ ช่วงเวลา หรือสถานะ</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การจัดรูปแบบอัตโนมัติ</h4>
                            <p class="text-xs text-gray-600">จัดรูปแบบข้อมูลอัตโนมัติให้เหมาะสมกับการใช้งาน</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การคำนวณสถิติ</h4>
                            <p class="text-xs text-gray-600">คำนวณสถิติการเข้าร่วมและเปอร์เซ็นต์อัตโนมัติ</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การส่งออกแบบกลุ่ม</h4>
                            <p class="text-xs text-gray-600">ส่งออกหลายรายงานพร้อมกันหรือตามช่วงเวลา</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 9. การจัดการผู้ใช้ -->
            <section class="mb-8" id="user-management">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">9. การจัดการผู้ใช้</h2>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การค้นหาผู้ใช้</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การจัดการผู้ใช้"</li>
                            <li>ใช้ฟิลด์ค้นหาเพื่อหาผู้ใช้:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li><strong>ชื่อ:</strong> ค้นหาตามชื่อจริงหรือนามสกุล</li>
                                    <li><strong>รหัสพนักงาน:</strong> ค้นหาตามรหัสพนักงาน</li>
                                    <li><strong>ตำแหน่ง:</strong> ค้นหาตามตำแหน่งงาน</li>
                                    <li><strong>แผนก:</strong> ค้นหาตามแผนก</li>
                                </ul>
                            </li>
                            <li>ระบบจะแสดงผลการค้นหาแบบ Real-time</li>
                            <li>สามารถเลือกผู้ใช้จากผลการค้นหาเพื่อเพิ่มการลงทะเบียน</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-blue-50 p-3">
                            <p class="text-sm text-blue-700">
                                <strong>ฟีเจอร์:</strong> การค้นหาขั้นสูงรองรับการค้นหาด้วยหลายเงื่อนไขพร้อมกัน
                                และสามารถกรองผลลัพธ์ตามแผนกหรือตำแหน่งได้
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การดูประวัติผู้ใช้</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>คลิกที่ชื่อผู้ใช้เพื่อดูประวัติการเข้าร่วม</li>
                            <li>ดูสถิติการเข้าร่วมกิจกรรมต่างๆ:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li>จำนวนครั้งที่เข้าร่วมกิจกรรม</li>
                                    <li>เปอร์เซ็นต์การเข้าร่วม</li>
                                    <li>รายการกิจกรรมที่เข้าร่วม</li>
                                    <li>สถานะการลงทะเบียนปัจจุบัน</li>
                                </ul>
                            </li>
                            <li>ตรวจสอบข้อมูลเพิ่มเติม:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li>ข้อมูลส่วนตัวและติดต่อ</li>
                                    <li>ประวัติการลงทะเบียน</li>
                                    <li>ผลการประเมิน (ถ้ามี)</li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การรีเซ็ตรหัสผ่าน</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ค้นหาผู้ใช้ที่ต้องการรีเซ็ตรหัสผ่าน</li>
                            <li>คลิกปุ่ม "รีเซ็ตรหัสผ่าน" ข้างชื่อผู้ใช้</li>
                            <li>ยืนยันการรีเซ็ตรหัสผ่าน</li>
                            <li>ระบบจะตั้งรหัสผ่านใหม่เป็นรหัสพนักงานของผู้ใช้</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-yellow-50 p-3">
                            <p class="text-sm text-yellow-800">
                                <strong>หมายเหตุ:</strong> การรีเซ็ตรหัสผ่านจะทำให้ผู้ใช้ต้องเปลี่ยนรหัสผ่านใหม่เมื่อเข้าสู่ระบบครั้งต่อไป
                                ระบบจะบันทึกการรีเซ็ตรหัสผ่านในล็อกเพื่อการตรวจสอบ
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การติดตามการเข้าร่วม</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>คลิกที่ชื่อผู้ใช้เพื่อดูรายละเอียด</li>
                            <li>ไปที่แท็บ "ประวัติการเข้าร่วม"</li>
                            <li>ดูรายการกิจกรรมที่เข้าร่วม:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li>วันที่และเวลาที่เข้าร่วม</li>
                                    <li>สถานะการลงชื่อเข้า-ออก</li>
                                    <li>ที่นั่งที่ได้รับจัดสรร</li>
                                    <li>ผลการประเมิน (ถ้ามี)</li>
                                </ul>
                            </li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-green-50 p-3">
                            <p class="text-sm text-green-700">
                                <strong>ข้อดี:</strong> สามารถติดตามพฤติกรรมการเข้าร่วมของผู้ใช้แต่ละคน
                                และใช้ข้อมูลนี้ในการวางแผนกิจกรรมในอนาคต
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-purple-50 p-4">
                    <h3 class="mb-2 font-semibold text-purple-800">คุณสมบัติพิเศษของการจัดการผู้ใช้</h3>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การค้นหาขั้นสูง</h4>
                            <p class="text-xs text-gray-600">ค้นหาด้วยหลายเงื่อนไขและกรองผลลัพธ์</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การติดตามประวัติ</h4>
                            <p class="text-xs text-gray-600">ติดตามประวัติการเข้าร่วมและพฤติกรรม</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การจัดการรหัสผ่าน</h4>
                            <p class="text-xs text-gray-600">รีเซ็ตรหัสผ่านและจัดการความปลอดภัย</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <h4 class="mb-1 text-sm font-semibold text-purple-600">การรายงานสถิติ</h4>
                            <p class="text-xs text-gray-600">สร้างรายงานสถิติการเข้าร่วมของผู้ใช้</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 10. การจัดการกลุ่ม -->
            <section class="mb-8" id="group-management">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">10. การจัดการกลุ่ม</h2>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การเพิ่มสมาชิกในกลุ่ม</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>เลือกกลุ่มที่ต้องการเพิ่มสมาชิก</li>
                            <li>คลิกปุ่ม "เพิ่มสมาชิก"</li>
                            <li>ค้นหาและเลือกผู้ใช้ที่ต้องการเพิ่ม</li>
                            <li>คลิก "เพิ่มสมาชิก" เพื่อบันทึก</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-blue-50 p-3">
                            <p class="text-sm text-blue-700">
                                <strong>ฟีเจอร์:</strong> สามารถเพิ่มสมาชิกหลายคนพร้อมกัน
                                และระบบจะตรวจสอบความขัดแย้งอัตโนมัติ
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การนำเข้าข้อมูลกลุ่ม</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>คลิกปุ่ม "นำเข้าข้อมูลกลุ่ม"</li>
                            <li>ดาวน์โหลดเทมเพลต Excel</li>
                            <li>กรอกข้อมูลในเทมเพลต:
                                <ul class="ml-6 mt-2 list-disc">
                                    <li>ชื่อกลุ่ม</li>
                                    <li>รายชื่อสมาชิก</li>
                                    <li>ข้อมูลเพิ่มเติม (ถ้ามี)</li>
                                </ul>
                            </li>
                            <li>อัปโหลดไฟล์ Excel ที่กรอกข้อมูลแล้ว</li>
                            <li>ตรวจสอบข้อมูลและยืนยันการนำเข้า</li>
                        </ol>
                    </div>
                </div>
            </section>

            <!-- 11. การแก้ไขปัญหา -->
            <section class="mb-6 sm:mb-8" id="troubleshooting">
                <h2 class="mb-3 text-xl font-bold text-gray-800 sm:mb-4 sm:text-2xl">11. การแก้ไขปัญหา</h2>

                <div class="space-y-6">
                    <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-yellow-800">ปัญหาที่พบบ่อย</h3>

                        <div class="space-y-4">
                            <div>
                                <h4 class="font-semibold text-yellow-700">ผู้ใช้ไม่สามารถลงทะเบียนได้</h4>
                                <ul class="ml-6 mt-2 list-disc text-sm text-yellow-700">
                                    <li>ตรวจสอบว่าช่วงเวลาลงทะเบียนยังไม่หมด</li>
                                    <li>ตรวจสอบว่าจำนวนที่นั่งยังไม่เต็ม</li>
                                    <li>ตรวจสอบประเภทโปรเจกต์ว่าอนุญาตการลงทะเบียนหรือไม่</li>
                                </ul>
                            </div>

                            <div>
                                <h4 class="font-semibold text-yellow-700">ระบบไม่แสดงโปรเจกต์</h4>
                                <ul class="ml-6 mt-2 list-disc text-sm text-yellow-700">
                                    <li>ตรวจสอบสถานะโปรเจกต์ว่าอยู่ในสถานะ "ใช้งาน"</li>
                                    <li>ตรวจสอบวันที่ของโปรเจกต์ว่ายังไม่หมด</li>
                                    <li>ตรวจสอบการตั้งค่าการแสดงผล</li>
                                </ul>
                            </div>

                            <div>
                                <h4 class="font-semibold text-yellow-700">การส่งออกข้อมูลไม่ทำงาน</h4>
                                <ul class="ml-6 mt-2 list-disc text-sm text-yellow-700">
                                    <li>ตรวจสอบการเชื่อมต่ออินเทอร์เน็ต</li>
                                    <li>ตรวจสอบสิทธิ์การเข้าถึงไฟล์</li>
                                    <li>ลองรีเฟรชหน้าเว็บและลองใหม่</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-blue-200 bg-blue-50 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-blue-800">เคล็ดลับการใช้งาน</h3>
                        <ul class="ml-6 list-disc space-y-2 text-blue-700">
                            <li>ใช้ฟิลเตอร์เพื่อค้นหาโปรเจกต์ที่ต้องการได้เร็วขึ้น</li>
                            <li>ตรวจสอบรายงานการใช้งานเป็นประจำเพื่อติดตามประสิทธิภาพ</li>
                            <li>สำรองข้อมูลก่อนทำการเปลี่ยนแปลงสำคัญ</li>
                            <li>ใช้ระบบการอนุมัติเพื่อควบคุมคุณภาพของผู้เข้าร่วม</li>
                        </ul>
                    </div>

                    <div class="rounded-lg border border-green-200 bg-green-50 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-green-800">การติดต่อฝ่ายสนับสนุน</h3>
                        <p class="text-green-700">
                            หากพบปัญหาที่ไม่สามารถแก้ไขได้ กรุณาติดต่อฝ่าย IT Support
                            พร้อมระบุรายละเอียดปัญหาและขั้นตอนที่ทำให้เกิดปัญหา
                        </p>
                        <div class="mt-3 rounded-lg bg-white p-3">
                            <p class="text-sm text-green-700">
                                <strong>ติดต่อ:</strong> โทร 21471 (Programmer)
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Back to top button -->
            <div class="mt-6 text-center sm:mt-8">
                <a class="inline-flex items-center gap-1.5 rounded-lg bg-gray-600 px-4 py-2 font-semibold text-white transition duration-200 hover:bg-gray-700 sm:gap-2 sm:px-6 sm:py-3" href="#overview">
                    <i class="fas fa-arrow-up text-sm sm:text-base"></i>
                    <span class="text-xs sm:text-sm">กลับขึ้นด้านบน</span>
                </a>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Highlight current section in navigation
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('a[href^="#"]');

            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (scrollY >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('text-blue-600', 'font-semibold');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('text-blue-600', 'font-semibold');
                }
            });
        });
    </script>
@endsection
