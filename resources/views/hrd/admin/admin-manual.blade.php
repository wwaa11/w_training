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
                    <li><a class="text-xs hover:underline sm:text-sm" href="#troubleshooting">10. การแก้ไขปัญหา</a></li>
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
                                    <li><strong>ชื่อโปรเจกต์:</strong> ชื่อที่แสดงให้ผู้ใช้เห็น</li>
                                    <li><strong>รายละเอียด:</strong> คำอธิบายเพิ่มเติม</li>
                                    <li><strong>ประเภทโปรเจกต์:</strong> เลือกประเภทการลงทะเบียน</li>
                                    <li><strong>จำนวนที่นั่งสูงสุด:</strong> จำกัดจำนวนผู้เข้าร่วม</li>
                                </ul>
                            </li>
                            <li>กำหนดช่วงเวลาลงทะเบียน (วันที่เริ่มและสิ้นสุด)</li>
                            <li>เพิ่มวันที่และช่วงเวลาของกิจกรรม</li>
                            <li>คลิก "สร้างโปรเจกต์" เพื่อบันทึก</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การแก้ไขโปรเจกต์</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>คลิก "ดูรายละเอียด" ในรายการโปรเจกต์</li>
                            <li>คลิกปุ่ม "แก้ไขโปรเจกต์"</li>
                            <li>แก้ไขข้อมูลที่ต้องการ</li>
                            <li>คลิก "อัปเดตโปรเจกต์" เพื่อบันทึกการเปลี่ยนแปลง</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-yellow-50 p-3">
                            <p class="text-sm text-yellow-800">
                                <strong>หมายเหตุ:</strong> การแก้ไขโปรเจกต์ที่มีผู้ลงทะเบียนแล้วอาจส่งผลต่อการลงทะเบียนที่มีอยู่
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
                                <li>ข้อมูลการลงทะเบียนและประวัติการเข้าร่วม</li>
                                <li>วันที่และช่วงเวลาของกิจกรรม</li>
                                <li>การจัดที่นั่ง</li>
                                <li>ผลการประเมิน</li>
                                <li>ลิงก์ที่เกี่ยวข้อง</li>
                            </ul>
                            <p class="mt-2 text-xs text-red-700">
                                <strong>หมายเหตุ:</strong> การลบนี้ไม่สามารถกู้คืนได้ กรุณาตรวจสอบให้แน่ใจก่อนดำเนินการ
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
                            <li>ดูรายการผู้ลงทะเบียนทั้งหมด พร้อมสถานะ</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การเพิ่มการลงทะเบียนด้วยตนเอง</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การลงทะเบียน" ของโปรเจกต์</li>
                            <li>คลิกปุ่ม "เพิ่มการลงทะเบียน"</li>
                            <li>เลือกผู้ใช้จากรายการ</li>
                            <li>เลือกวันที่และช่วงเวลาที่ต้องการ</li>
                            <li>คลิก "บันทึก" เพื่อเพิ่มการลงทะเบียน</li>
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
                            <li>ดูรายการการลงทะเบียนที่รอการอนุมัติ</li>
                            <li>เลือกการลงทะเบียนที่ต้องการอนุมัติ</li>
                            <li>คลิกปุ่ม "อนุมัติ" หรือ "อนุมัติทั้งหมด"</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การยกเลิกการอนุมัติ</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การอนุมัติ" ของโปรเจกต์</li>
                            <li>ดูรายการการลงทะเบียนที่ได้รับการอนุมัติแล้ว</li>
                            <li>คลิกปุ่ม "ยกเลิกการอนุมัติ" ข้างการลงทะเบียนที่ต้องการ</li>
                        </ol>
                        <div class="mt-4 rounded-lg bg-blue-50 p-3">
                            <p class="text-sm text-blue-700">
                                <strong>หมายเหตุ:</strong> การยกเลิกการอนุมัติจะทำให้การลงทะเบียนกลับไปอยู่ในสถานะ "รอการอนุมัติ"
                                และสามารถอนุมัติใหม่ได้ในภายหลัง
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
                            <li>ระบบจะจัดที่นั่งให้ผู้ลงทะเบียนที่ได้รับการอนุมัติแล้ว</li>
                            <li>ตรวจสอบผลลัพธ์และปรับแต่งตามต้องการ</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การกำหนดที่นั่งด้วยตนเอง</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การจัดการที่นั่ง" ของโปรเจกต์</li>
                            <li>เลือกผู้ใช้จากรายการ</li>
                            <li>เลือกที่นั่งที่ต้องการ</li>
                            <li>คลิก "กำหนดที่นั่ง" เพื่อบันทึก</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การลบการกำหนดที่นั่ง</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่หน้า "การจัดการที่นั่ง" ของโปรเจกต์</li>
                            <li>คลิกปุ่ม "ลบ" ข้างการกำหนดที่นั่งที่ต้องการ</li>
                            <li>ยืนยันการลบ</li>
                        </ol>
                    </div>
                </div>
            </section>

            <!-- 7. การจัดการผลการประเมิน -->
            <section class="mb-6 sm:mb-8" id="result-management">
                <h2 class="mb-3 text-xl font-bold text-gray-800 sm:mb-4 sm:text-2xl">7. การจัดการผลการประเมิน</h2>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-gray-700 sm:mb-3 sm:text-lg">การสร้างหัวข้อการประเมิน</h3>
                    <div class="rounded-lg bg-gray-50 p-3 sm:p-4">
                        <ol class="ml-4 list-decimal space-y-1.5 text-sm text-gray-700 sm:ml-6 sm:space-y-2">
                            <li>ไปที่หน้า "การจัดการผลการประเมิน" ของโปรเจกต์</li>
                            <li>คลิกปุ่ม "สร้างหัวข้อการประเมิน"</li>
                            <li>กรอกชื่อหัวข้อการประเมิน (เช่น ความรู้ความเข้าใจ, ทักษะการปฏิบัติ)</li>
                            <li>กำหนดน้ำหนักหรือคะแนนเต็มของแต่ละหัวข้อ</li>
                            <li>คลิก "บันทึก" เพื่อสร้างหัวข้อ</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-gray-700 sm:mb-3 sm:text-lg">การบันทึกผลการประเมิน</h3>
                    <div class="rounded-lg bg-gray-50 p-3 sm:p-4">
                        <ol class="ml-4 list-decimal space-y-1.5 text-sm text-gray-700 sm:ml-6 sm:space-y-2">
                            <li>เลือกผู้เข้าร่วมที่ต้องการบันทึกผลการประเมิน</li>
                            <li>กรอกคะแนนในแต่ละหัวข้อการประเมิน</li>
                            <li>เพิ่มหมายเหตุหรือข้อเสนอแนะ (ถ้ามี)</li>
                            <li>คลิก "บันทึกผลการประเมิน"</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-gray-700 sm:mb-3 sm:text-lg">การแก้ไขผลการประเมิน</h3>
                    <div class="rounded-lg bg-gray-50 p-3 sm:p-4">
                        <ol class="ml-4 list-decimal space-y-1.5 text-sm text-gray-700 sm:ml-6 sm:space-y-2">
                            <li>ค้นหาผลการประเมินที่ต้องการแก้ไข</li>
                            <li>คลิกปุ่ม "แก้ไข" ข้างผลการประเมิน</li>
                            <li>แก้ไขคะแนนหรือหมายเหตุตามต้องการ</li>
                            <li>คลิก "อัปเดต" เพื่อบันทึกการเปลี่ยนแปลง</li>
                        </ol>
                    </div>
                </div>

                <div class="rounded-lg bg-purple-50 p-3 sm:p-4">
                    <h3 class="mb-2 text-sm font-semibold text-purple-800 sm:text-base">คุณสมบัติของระบบผลการประเมิน</h3>
                    <div class="grid grid-cols-1 gap-2 sm:gap-3 md:grid-cols-2">
                        <div class="rounded-lg bg-white p-2.5 sm:p-3">
                            <h4 class="mb-1 text-xs font-semibold text-purple-600 sm:text-sm">การประเมินหลายหัวข้อ</h4>
                            <p class="text-xs text-gray-600 sm:text-sm">รองรับการประเมินหลายหัวข้อต่อกิจกรรม</p>
                        </div>
                        <div class="rounded-lg bg-white p-2.5 sm:p-3">
                            <h4 class="mb-1 text-xs font-semibold text-purple-600 sm:text-sm">การคำนวณอัตโนมัติ</h4>
                            <p class="text-xs text-gray-600 sm:text-sm">คำนวณคะแนนรวมและเปอร์เซ็นต์อัตโนมัติ</p>
                        </div>
                        <div class="rounded-lg bg-white p-2.5 sm:p-3">
                            <h4 class="mb-1 text-xs font-semibold text-purple-600 sm:text-sm">การส่งออกผลการประเมิน</h4>
                            <p class="text-xs text-gray-600 sm:text-sm">ส่งออกผลการประเมินในรูปแบบ Excel</p>
                        </div>
                        <div class="rounded-lg bg-white p-2.5 sm:p-3">
                            <h4 class="mb-1 text-xs font-semibold text-purple-600 sm:text-sm">การติดตามประวัติ</h4>
                            <p class="text-xs text-gray-600 sm:text-sm">ติดตามประวัติการประเมินของผู้เข้าร่วม</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 8. การส่งออกข้อมูล -->
            <section class="mb-8" id="export-features">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">8. การส่งออกข้อมูล</h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-blue-600">รายงาน DBD</h3>
                        <p class="mb-3 text-gray-700">ส่งออกข้อมูลในรูปแบบที่เหมาะสมสำหรับการรายงาน DBD</p>
                        <ul class="ml-6 list-disc space-y-1 text-sm text-gray-600">
                            <li>ข้อมูลการลงทะเบียนทั้งหมด</li>
                            <li>สถิติการเข้าร่วม</li>
                            <li>รายละเอียดโปรเจกต์</li>
                        </ul>
                    </div>

                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-green-600">รายงาน Onebook</h3>
                        <p class="mb-3 text-gray-700">ส่งออกข้อมูลสำหรับการทำ Onebook</p>
                        <ul class="ml-6 list-disc space-y-1 text-sm text-gray-600">
                            <li>รายชื่อผู้เข้าร่วม</li>
                            <li>ข้อมูลการลงทะเบียน</li>
                            <li>รายละเอียดกิจกรรม</li>
                        </ul>
                    </div>

                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-purple-600">รายงานตามวันที่</h3>
                        <p class="mb-3 text-gray-700">ส่งออกข้อมูลแยกตามวันที่ของกิจกรรม</p>
                        <ul class="ml-6 list-disc space-y-1 text-sm text-gray-600">
                            <li>รายชื่อผู้เข้าร่วมในแต่ละวัน</li>
                            <li>สถิติการเข้าร่วมรายวัน</li>
                            <li>รายละเอียดกิจกรรมรายวัน</li>
                        </ul>
                    </div>

                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-orange-600">รายงาน PDF</h3>
                        <p class="mb-3 text-gray-700">ส่งออกรายงานในรูปแบบ PDF</p>
                        <ul class="ml-6 list-disc space-y-1 text-sm text-gray-600">
                            <li>รายงานการเข้าร่วม</li>
                            <li>รายชื่อผู้เข้าร่วม</li>
                            <li>สถิติต่างๆ</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 9. การจัดการผู้ใช้ -->
            <section class="mb-8" id="user-management">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">9. การจัดการผู้ใช้</h2>

                <div class="rounded-lg bg-gray-50 p-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การค้นหาผู้ใช้</h3>
                    <ul class="ml-6 list-disc space-y-2 text-gray-700">
                        <li>ใช้ฟิลด์ค้นหาเพื่อหาผู้ใช้ตามชื่อหรือรหัสพนักงาน</li>
                        <li>ระบบจะแสดงผลการค้นหาแบบ Real-time</li>
                        <li>สามารถเลือกผู้ใช้จากผลการค้นหาเพื่อเพิ่มการลงทะเบียน</li>
                    </ul>
                </div>

                <div class="mt-6 rounded-lg bg-gray-50 p-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การดูประวัติผู้ใช้</h3>
                    <ul class="ml-6 list-disc space-y-2 text-gray-700">
                        <li>คลิกที่ชื่อผู้ใช้เพื่อดูประวัติการเข้าร่วม</li>
                        <li>ดูสถิติการเข้าร่วมกิจกรรมต่างๆ</li>
                        <li>ตรวจสอบสถานะการลงทะเบียนปัจจุบัน</li>
                    </ul>
                </div>
            </section>

            <!-- 10. การใช้งานบนมือถือ -->
            <section class="mb-8" id="mobile-admin">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">10. การใช้งานบนมือถือ</h2>

                <div class="rounded-lg bg-gray-50 p-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การเข้าสู่ระบบบนมือถือ</h3>
                    <ul class="ml-6 list-disc space-y-2 text-gray-700">
                        <li>เข้าสู่ระบบ HRD ผ่านแอปพลิเคชันบนมือถือ</li>
                        <li>ตรวจสอบข้อมูลผู้ใช้และสิทธิ์</li>
                        <li>ดูรายการโปรเจกต์และการลงทะเบียน</li>
                    </ul>
                </div>

                <div class="mt-6 rounded-lg bg-gray-50 p-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การจัดการผลการประเมินบนมือถือ</h3>
                    <ul class="ml-6 list-disc space-y-2 text-gray-700">
                        <li>ดูรายการผลการประเมินทั้งหมด</li>
                        <li>สร้างผลการประเมินใหม่</li>
                        <li>แก้ไขผลการประเมินที่มีอยู่</li>
                    </ul>
                </div>
            </section>

            <!-- 10. การแก้ไขปัญหา -->
            <section class="mb-6 sm:mb-8" id="troubleshooting">
                <h2 class="mb-3 text-xl font-bold text-gray-800 sm:mb-4 sm:text-2xl">10. การแก้ไขปัญหา</h2>

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
