@extends("layouts.hrd")

@section("title", "คู่มือการใช้งานระบบ HRD")

@section("content")
    <div class="container mx-auto px-4">
        <div class="rounded-lg bg-white p-6 shadow-lg">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">คู่มือการใช้งานระบบ HRD สำหรับผู้ใช้</h1>
                <a class="flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition duration-200 hover:bg-blue-700" href="{{ route("hrd.index") }}">
                    <i class="fas fa-arrow-left"></i>
                    กลับไปหน้าหลัก
                </a>
            </div>

            <!-- Quick Reference Card -->
            <div class="mb-8 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                <h2 class="mb-4 text-xl font-semibold">📋 Quick Reference</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-lg bg-white/20 p-4 backdrop-blur-sm">
                        <h3 class="mb-2 font-semibold">🚀 การเริ่มต้น</h3>
                        <ul class="space-y-1 text-sm">
                            <li>• เข้าสู่ระบบด้วยบัญชีของคุณ</li>
                            <li>• เลือกกิจกรรมที่ต้องการ</li>
                            <li>• ลงทะเบียนหรือเช็คอิน</li>
                        </ul>
                    </div>
                    <div class="rounded-lg bg-white/20 p-4 backdrop-blur-sm">
                        <h3 class="mb-2 font-semibold">👥 ประเภทกิจกรรม</h3>
                        <ul class="space-y-1 text-sm">
                            <li>• กิจกรรมเข้าร่วม (เช็คอินทันที)</li>
                            <li>• กิจกรรมเดี่ยว (ลงทะเบียน 1 ครั้ง)</li>
                            <li>• กิจกรรมหลายเซสชัน</li>
                        </ul>
                    </div>
                    <div class="rounded-lg bg-white/20 p-4 backdrop-blur-sm">
                        <h3 class="mb-2 font-semibold">📊 การติดตาม</h3>
                        <ul class="space-y-1 text-sm">
                            <li>• ดูประวัติการเข้าร่วม</li>
                            <li>• ตรวจสอบสถานะการลงทะเบียน</li>
                            <li>• ยกเลิกการลงทะเบียน</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Table of Contents -->
            <div class="mb-8 rounded-lg bg-gray-50 p-6">
                <h2 class="mb-4 text-xl font-semibold text-gray-800">สารบัญ</h2>
                <ul class="space-y-2 text-blue-600">
                    <li><a class="hover:underline" href="#overview">1. ภาพรวมระบบ</a></li>
                    <li><a class="hover:underline" href="#project-types">2. ประเภทกิจกรรม</a></li>
                    <li><a class="hover:underline" href="#attendance-projects">3. กิจกรรมเข้าร่วม</a></li>
                    <li><a class="hover:underline" href="#single-projects">4. กิจกรรมเดี่ยว</a></li>
                    <li><a class="hover:underline" href="#multiple-projects">5. กิจกรรมหลายเซสชัน</a></li>
                    <li><a class="hover:underline" href="#registration-process">6. ขั้นตอนการลงทะเบียน</a></li>
                    <li><a class="hover:underline" href="#check-in-process">7. ขั้นตอนการเช็คอิน</a></li>
                    <li><a class="hover:underline" href="#cancellation">8. การยกเลิกการลงทะเบียน</a></li>
                    <li><a class="hover:underline" href="#faq">9. คำถามที่พบบ่อย</a></li>
                </ul>
            </div>

            <!-- 1. ภาพรวมระบบ -->
            <section class="mb-8" id="overview">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">1. ภาพรวมระบบ</h2>
                <div class="rounded-lg bg-blue-50 p-6">
                    <p class="mb-4 text-gray-700">
                        ระบบ HRD (Human Resource Development) เป็นระบบจัดการการฝึกอบรมและพัฒนาบุคลากร
                        ที่ช่วยให้ผู้ใช้สามารถลงทะเบียนเข้าร่วมกิจกรรม การเช็คอิน และติดตามประวัติการเข้าร่วมได้อย่างสะดวก
                    </p>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="rounded-lg bg-white p-4 shadow">
                            <h3 class="mb-2 font-semibold text-blue-600">การลงทะเบียน</h3>
                            <p class="text-sm text-gray-600">ลงทะเบียนเข้าร่วมกิจกรรมต่างๆ</p>
                        </div>
                        <div class="rounded-lg bg-white p-4 shadow">
                            <h3 class="mb-2 font-semibold text-green-600">การเช็คอิน</h3>
                            <p class="text-sm text-gray-600">เช็คอินเข้าร่วมกิจกรรมในวันงาน</p>
                        </div>
                        <div class="rounded-lg bg-white p-4 shadow">
                            <h3 class="mb-2 font-semibold text-purple-600">ประวัติการเข้าร่วม</h3>
                            <p class="text-sm text-gray-600">ดูประวัติและสถิติการเข้าร่วม</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. ประเภทกิจกรรม -->
            <section class="mb-8" id="project-types">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">2. ประเภทกิจกรรม</h2>
                <div class="space-y-4">
                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-purple-600">กิจกรรมเข้าร่วม (ไม่ต้องลงทะเบียน)</h3>
                        <ul class="ml-6 list-disc space-y-2 text-gray-700">
                            <li>ไม่ต้องลงทะเบียนล่วงหน้า</li>
                            <li>สามารถเช็คอินได้โดยตรงในวันงาน</li>
                            <li>เหมาะสำหรับกิจกรรมเปิดกว้างหรือการประชุมทั่วไป</li>
                            <li>ยกเลิกการลงทะเบียนได้เสมอ แม้จะเช็คอินแล้ว</li>
                        </ul>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-blue-600">กิจกรรมเดี่ยว (ลงทะเบียน 1 ครั้ง)</h3>
                        <ul class="ml-6 list-disc space-y-2 text-gray-700">
                            <li>ต้องลงทะเบียนก่อนเช็คอิน</li>
                            <li>ลงทะเบียนได้เพียง 1 ครั้งต่อกิจกรรม</li>
                            <li>เหมาะสำหรับกิจกรรมที่ต้องการจำกัดจำนวนผู้เข้าร่วม</li>
                            <li>ยกเลิกได้เฉพาะก่อนเช็คอินเท่านั้น</li>
                        </ul>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-green-600">กิจกรรมหลายเซสชัน (ลงทะเบียนหลายครั้ง)</h3>
                        <ul class="ml-6 list-disc space-y-2 text-gray-700">
                            <li>ลงทะเบียนได้หลายครั้งในกิจกรรมเดียวกัน</li>
                            <li>เหมาะสำหรับกิจกรรมที่จัดหลายรอบหรือหลายวัน</li>
                            <li>สามารถเลือกช่วงเวลาและวันที่ที่ต้องการได้</li>
                            <li>ยกเลิกได้เฉพาะก่อนเช็คอินเท่านั้น</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 3. กิจกรรมเข้าร่วม -->
            <section class="mb-8" id="attendance-projects">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">3. กิจกรรมเข้าร่วม</h2>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">วิธีการเข้าร่วม</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>เข้าไปดูรายละเอียดกิจกรรมที่ต้องการเข้าร่วม</li>
                            <li>รอจนถึงเวลาที่กำหนดในเซสชันนั้นๆ</li>
                            <li>กดปุ่ม "เช็คอินตอนนี้" เมื่อถึงเวลา</li>
                            <li>ระบบจะยืนยันการเข้าร่วมของคุณ</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">คุณสมบัติ</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ul class="ml-6 list-disc space-y-2 text-gray-700">
                            <li>ไม่ต้องลงทะเบียนก่อน</li>
                            <li>เช็คอินได้ทันทีเมื่อถึงเวลา</li>
                            <li>ยกเลิกการลงทะเบียนได้เสมอ แม้จะเช็คอินแล้ว</li>
                            <li>เช็คอินได้เพียงครั้งเดียวต่อเซสชัน</li>
                        </ul>
                    </div>
                </div>

                <div class="rounded-lg bg-purple-50 p-4">
                    <h3 class="mb-2 font-semibold text-purple-800">ตัวอย่างหน้าจอ</h3>
                    <div class="rounded-lg border-2 border-purple-200 bg-white p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="font-semibold text-gray-900">เช็คอินตอนนี้</h4>
                            <span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800">พร้อมเช็คอิน</span>
                        </div>
                        <p class="mb-2 text-sm text-gray-600">เซสชัน: การอบรมการใช้งานระบบ</p>
                        <p class="mb-3 text-sm text-gray-600">เวลา: 09:00 - 12:00</p>
                        <button class="w-full rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white">
                            เช็คอินตอนนี้
                        </button>
                    </div>
                </div>
            </section>

            <!-- 4. กิจกรรมเดี่ยว -->
            <section class="mb-8" id="single-projects">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">4. กิจกรรมเดี่ยว</h2>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">ขั้นตอนการลงทะเบียน</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>เลือกเซสชันที่ต้องการและคลิกปุ่ม "ลงทะเบียน"</li>
                            <li>ระบบจะยืนยันการลงทะเบียนของคุณ</li>
                            <li>รอจนถึงเวลาที่กำหนดในเซสชันที่ลงทะเบียน</li>
                            <li>กดปุ่ม "เช็คอินตอนนี้" เพื่อยืนยันการเข้าร่วม</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">คุณสมบัติ</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ul class="ml-6 list-disc space-y-2 text-gray-700">
                            <li>ต้องลงทะเบียนก่อนเช็คอิน</li>
                            <li>ลงทะเบียนได้ 1 เซสชันเท่านั้น</li>
                            <li>ยกเลิกได้เฉพาะก่อนเช็คอิน</li>
                            <li>ไม่สามารถลงทะเบียนในวันเดียวกันได้ (บางกิจกรรม)</li>
                        </ul>
                    </div>
                </div>

                <div class="rounded-lg bg-blue-50 p-4">
                    <h3 class="mb-2 font-semibold text-blue-800">ตัวอย่างหน้าจอ</h3>
                    <div class="rounded-lg border-2 border-blue-200 bg-white p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="font-semibold text-gray-900">ลงทะเบียน</h4>
                            <span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800">เปิดรับ</span>
                        </div>
                        <p class="mb-2 text-sm text-gray-600">เซสชัน: การอบรมการใช้งานระบบ</p>
                        <p class="mb-3 text-sm text-gray-600">เวลา: 09:00 - 12:00</p>
                        <button class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">
                            ลงทะเบียน
                        </button>
                    </div>
                </div>
            </section>

            <!-- 5. กิจกรรมหลายเซสชัน -->
            <section class="mb-8" id="multiple-projects">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">5. กิจกรรมหลายเซสชัน</h2>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">ขั้นตอนการลงทะเบียน</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>เลือกเซสชันที่ต้องการจากรายการที่มี</li>
                            <li>คลิกปุ่ม "ลงทะเบียน" สำหรับเซสชันที่เลือก</li>
                            <li>เช็คอินเมื่อถึงเวลาในแต่ละเซสชัน</li>
                            <li>สามารถลงทะเบียนเซสชันอื่นเพิ่มเติมได้</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">คุณสมบัติ</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ul class="ml-6 list-disc space-y-2 text-gray-700">
                            <li>ลงทะเบียนได้หลายเซสชัน</li>
                            <li>เข้าร่วมได้หลายเซสชัน</li>
                            <li>ยกเลิกได้เฉพาะก่อนเช็คอิน</li>
                            <li>ไม่สามารถลงทะเบียนซ้ำในเซสชันเดียวกัน</li>
                        </ul>
                    </div>
                </div>

                <div class="rounded-lg bg-green-50 p-4">
                    <h3 class="mb-2 font-semibold text-green-800">ตัวอย่างหน้าจอ</h3>
                    <div class="grid gap-3">
                        <div class="rounded-lg border-2 border-green-200 bg-white p-3">
                            <div class="mb-2 flex items-center justify-between">
                                <h4 class="font-semibold text-gray-900">เซสชันที่ 1</h4>
                                <span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800">ลงทะเบียนแล้ว</span>
                            </div>
                            <p class="text-sm text-gray-600">เวลา: 09:00 - 12:00</p>
                        </div>
                        <div class="rounded-lg border-2 border-gray-200 bg-white p-3">
                            <div class="mb-2 flex items-center justify-between">
                                <h4 class="font-semibold text-gray-900">เซสชันที่ 2</h4>
                                <span class="rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-800">เปิดรับ</span>
                            </div>
                            <p class="mb-2 text-sm text-gray-600">เวลา: 13:00 - 16:00</p>
                            <button class="rounded bg-green-600 px-3 py-1 text-sm text-white">
                                ลงทะเบียน
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 6. ขั้นตอนการลงทะเบียน -->
            <section class="mb-8" id="registration-process">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">6. ขั้นตอนการลงทะเบียน</h2>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การลงทะเบียนทั่วไป</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>เข้าสู่ระบบด้วยบัญชีผู้ใช้ของคุณ</li>
                            <li>เลือกกิจกรรมที่ต้องการจากรายการ</li>
                            <li>อ่านรายละเอียดและเงื่อนไขของกิจกรรม</li>
                            <li>เลือกเซสชันที่ต้องการ (ถ้ามีหลายเซสชัน)</li>
                            <li>คลิกปุ่ม "ลงทะเบียน"</li>
                            <li>ยืนยันการลงทะเบียนในหน้าต่างที่ปรากฏ</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">ข้อควรระวัง</h3>
                    <div class="rounded-lg bg-yellow-50 p-4">
                        <ul class="ml-6 list-disc space-y-2 text-yellow-700">
                            <li>ตรวจสอบวันที่และเวลาของกิจกรรมให้ถูกต้อง</li>
                            <li>สำหรับกิจกรรมเดี่ยว ลงทะเบียนได้เพียง 1 ครั้งเท่านั้น</li>
                            <li>หากกิจกรรมเต็มแล้ว จะไม่สามารถลงทะเบียนได้</li>
                            <li>การลงทะเบียนบางกิจกรรมไม่อนุญาตให้ลงทะเบียนในวันเดียวกัน</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 7. ขั้นตอนการเช็คอิน -->
            <section class="mb-8" id="check-in-process">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">7. ขั้นตอนการเช็คอิน</h2>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">การเช็คอินทั่วไป</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>เข้าสู่ระบบและไปที่กิจกรรมที่ลงทะเบียน</li>
                            <li>รอจนถึงเวลาที่กำหนดในเซสชันนั้นๆ</li>
                            <li>เมื่อถึงเวลา ปุ่ม "เช็คอินตอนนี้" จะปรากฏ</li>
                            <li>คลิกปุ่ม "เช็คอินตอนนี้"</li>
                            <li>ยืนยันการเช็คอินในหน้าต่างที่ปรากฏ</li>
                            <li>ระบบจะแสดงข้อความยืนยันการเช็คอินสำเร็จ</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">ข้อควรระวัง</h3>
                    <div class="rounded-lg bg-red-50 p-4">
                        <ul class="ml-6 list-disc space-y-2 text-red-700">
                            <li>เช็คอินได้เฉพาะในช่วงเวลาที่กำหนดเท่านั้น</li>
                            <li>เช็คอินได้เพียงครั้งเดียวต่อเซสชัน</li>
                            <li>หากเช็คอินแล้ว จะไม่สามารถยกเลิกการลงทะเบียนได้ (สำหรับกิจกรรมเดี่ยวและหลายเซสชัน)</li>
                            <li>ตรวจสอบเวลาให้ถูกต้องก่อนเช็คอิน</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 8. การยกเลิกการลงทะเบียน -->
            <section class="mb-8" id="cancellation">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">8. การยกเลิกการลงทะเบียน</h2>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">วิธีการยกเลิก</h3>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <ol class="ml-6 list-decimal space-y-2 text-gray-700">
                            <li>ไปที่กิจกรรมที่ลงทะเบียน</li>
                            <li>คลิกปุ่ม "ยกเลิกการลงทะเบียน"</li>
                            <li>ยืนยันการยกเลิกในหน้าต่างที่ปรากฏ</li>
                            <li>ระบบจะลบข้อมูลการลงทะเบียนของคุณออก</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">เงื่อนไขการยกเลิก</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-lg bg-green-50 p-4">
                            <h4 class="mb-2 font-semibold text-green-800">กิจกรรมเข้าร่วม</h4>
                            <ul class="ml-4 list-disc space-y-1 text-sm text-green-700">
                                <li>ยกเลิกได้เสมอ</li>
                                <li>แม้จะเช็คอินแล้วก็สามารถยกเลิกได้</li>
                                <li>การยกเลิกจะลบข้อมูลการเข้าร่วมออกจากระบบ</li>
                            </ul>
                        </div>
                        <div class="rounded-lg bg-yellow-50 p-4">
                            <h4 class="mb-2 font-semibold text-yellow-800">กิจกรรมเดี่ยว/หลายเซสชัน</h4>
                            <ul class="ml-4 list-disc space-y-1 text-sm text-yellow-700">
                                <li>ยกเลิกได้เฉพาะก่อนเช็คอินเท่านั้น</li>
                                <li>ไม่สามารถยกเลิกได้หลังจากเช็คอินแล้ว</li>
                                <li>การลงทะเบียนจะถูกล็อคหลังจากเช็คอิน</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 9. คำถามที่พบบ่อย -->
            <section class="mb-8" id="faq">
                <h2 class="mb-4 text-2xl font-bold text-gray-800">9. คำถามที่พบบ่อย</h2>

                <div class="space-y-6">
                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-gray-700">ฉันสามารถยกเลิกการลงทะเบียนได้หรือไม่?</h3>
                        <p class="text-gray-700">
                            ขึ้นอยู่กับประเภทของกิจกรรม:
                        </p>
                        <ul class="ml-6 mt-2 list-disc space-y-1 text-gray-700">
                            <li><strong>กิจกรรมเข้าร่วม:</strong> ยกเลิกได้เสมอ แม้จะเช็คอินแล้ว</li>
                            <li><strong>กิจกรรมเดี่ยว/หลายเซสชัน:</strong> ยกเลิกได้เฉพาะก่อนเช็คอินเท่านั้น</li>
                        </ul>
                    </div>

                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-gray-700">ทำไมฉันไม่เห็นปุ่มลงทะเบียน?</h3>
                        <p class="text-gray-700">อาจเป็นเพราะ:</p>
                        <ul class="ml-6 mt-2 list-disc space-y-1 text-gray-700">
                            <li>กิจกรรมยังไม่เปิดรับลงทะเบียน</li>
                            <li>หมดเวลาลงทะเบียนแล้ว</li>
                            <li>กิจกรรมไม่อนุญาตให้ลงทะเบียนในวันเดียวกัน</li>
                            <li>คุณได้ลงทะเบียนแล้ว (สำหรับกิจกรรมเดี่ยว)</li>
                        </ul>
                    </div>

                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-gray-700">ฉันสามารถเช็คอินได้เมื่อไหร่?</h3>
                        <p class="text-gray-700">
                            คุณสามารถเช็คอินได้เฉพาะในช่วงเวลาที่กำหนดในแต่ละเซสชันเท่านั้น
                            ระบบจะแสดงปุ่มเช็คอินเมื่อถึงเวลาและซ่อนเมื่อหมดเวลา
                        </p>
                    </div>

                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-gray-700">ฉันสามารถลงทะเบียนซ้ำได้หรือไม่?</h3>
                        <p class="text-gray-700">ขึ้นอยู่กับประเภทของกิจกรรม:</p>
                        <ul class="ml-6 mt-2 list-disc space-y-1 text-gray-700">
                            <li><strong>กิจกรรมเดี่ยว:</strong> ไม่สามารถลงทะเบียนซ้ำได้</li>
                            <li><strong>กิจกรรมหลายเซสชัน:</strong> ลงทะเบียนได้หลายเซสชัน แต่ไม่ซ้ำกัน</li>
                            <li><strong>กิจกรรมเข้าร่วม:</strong> ไม่ต้องลงทะเบียน</li>
                        </ul>
                    </div>

                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="mb-3 text-lg font-semibold text-gray-700">ฉันจะรู้ได้อย่างไรว่ากิจกรรมเต็มแล้ว?</h3>
                        <p class="text-gray-700">
                            ระบบจะแสดงสถานะ "เต็มแล้ว" เมื่อกิจกรรมมีผู้ลงทะเบียนครบจำนวนที่กำหนดแล้ว
                            ในกรณีนี้ปุ่มลงทะเบียนจะถูกซ่อนหรือแสดงเป็นสีเทา
                        </p>
                    </div>
                </div>
            </section>

            <!-- Back to top button -->
            <div class="mt-8 text-center">
                <a class="inline-flex items-center gap-2 rounded-lg bg-gray-600 px-6 py-3 font-semibold text-white transition duration-200 hover:bg-gray-700" href="#overview">
                    <i class="fas fa-arrow-up"></i>
                    กลับขึ้นด้านบน
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
