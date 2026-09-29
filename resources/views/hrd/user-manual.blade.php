@extends("layouts.hrd")

@section("title", "คู่มือการใช้งานระบบ HRD")

@section("content")
    @include("hrd.partials.app-page", [
        "appEyebrow" => "HRD",
        "appTitle" => "คู่มือการใช้งาน",
        "appSubtitle" => "สำหรับผู้ใช้ทั่วไป",
        "appBackUrl" => route("hrd.index"),
        "appBottomClass" => "pb-16",
    ])
        <div class="hrd-card p-4 sm:p-6">
            <!-- Quick Reference Card -->
            <div class="mb-6 hrd-hero-banner p-4 sm:mb-8 sm:p-6">
                <h2 class="mb-3 text-lg font-semibold sm:mb-4 sm:text-xl">📋 Quick Reference</h2>
                <div class="grid grid-cols-1 gap-3 sm:gap-4 md:grid-cols-3">
                    <div class="rounded-lg bg-white/20 p-3 backdrop-blur-sm sm:p-4">
                        <h3 class="mb-1.5 text-sm font-semibold sm:mb-2 sm:text-base">🚀 การเริ่มต้น</h3>
                        <ul class="space-y-1 text-xs sm:text-sm">
                            <li>• เข้าสู่ระบบด้วยบัญชีของคุณ</li>
                            <li>• เลือกกิจกรรมที่ต้องการ</li>
                            <li>• ลงทะเบียนหรือเช็คอิน</li>
                        </ul>
                    </div>
                    <div class="rounded-lg bg-white/20 p-3 backdrop-blur-sm sm:p-4">
                        <h3 class="mb-1.5 text-sm font-semibold sm:mb-2 sm:text-base">👥 ประเภทกิจกรรม</h3>
                        <ul class="space-y-1 text-xs sm:text-sm">
                            <li>• กิจกรรมเข้าร่วม (เช็คอินทันที)</li>
                            <li>• กิจกรรมเดี่ยว (ลงทะเบียน 1 ครั้ง)</li>
                            <li>• กิจกรรมหลายเซสชัน</li>
                        </ul>
                    </div>
                    <div class="rounded-lg bg-white/20 p-3 backdrop-blur-sm sm:p-4">
                        <h3 class="mb-1.5 text-sm font-semibold sm:mb-2 sm:text-base">📊 การติดตาม</h3>
                        <ul class="space-y-1 text-xs sm:text-sm">
                            <li>• ดูประวัติการเข้าร่วม</li>
                            <li>• กลุ่ม/ที่นั่ง (หลังเช็คอิน)</li>
                            <li>• ลิงก์ทรัพยากรระหว่างเซสชัน</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Table of Contents -->
            <div class="mb-6 rounded-lg bg-slate-50 p-4 sm:mb-8 sm:p-6">
                <h2 class="mb-3 text-lg font-semibold text-slate-900 sm:mb-4 sm:text-xl">สารบัญ</h2>
                <ul class="space-y-1.5 text-blue-600 sm:space-y-2">
                    <li><a class="text-xs hover:underline sm:text-sm" href="#overview">1. ภาพรวมระบบ</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#screens">2. หน้าจอและเลย์เอาต์</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#project-types">3. ประเภทกิจกรรม</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#attendance-projects">4. กิจกรรมเข้าร่วม</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#single-projects">5. กิจกรรมเดี่ยว</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#multiple-projects">6. กิจกรรมหลายเซสชัน</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#registration-process">7. ขั้นตอนการลงทะเบียน</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#check-in-process">8. ขั้นตอนการเช็คอิน</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#seat-group-links">9. กลุ่ม ที่นั่ง และลิงก์</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#results">10. การดูผลการประเมิน</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#cancellation">11. การยกเลิกการลงทะเบียน</a></li>
                    <li><a class="text-xs hover:underline sm:text-sm" href="#faq">12. คำถามที่พบบ่อย</a></li>
                </ul>
            </div>

            <!-- 1. ภาพรวมระบบ -->
            <section class="mb-6 sm:mb-8" id="overview">
                <h2 class="mb-3 text-xl font-bold text-slate-900 sm:mb-4 sm:text-2xl">1. ภาพรวมระบบ</h2>
                <div class="rounded-lg bg-blue-50 p-4 sm:p-6">
                    <p class="mb-3 text-sm text-slate-700 sm:mb-4 sm:text-base">
                        ระบบ HRD (Human Resource Development) เป็นระบบจัดการการฝึกอบรมและพัฒนาบุคลากร
                        ที่ช่วยให้ผู้ใช้สามารถลงทะเบียนเข้าร่วมกิจกรรม การเช็คอิน และติดตามประวัติการเข้าร่วมได้อย่างสะดวก
                        ระบบได้รับการออกแบบให้ใช้งานง่ายทั้งบนคอมพิวเตอร์และมือถือ
                    </p>
                    <div class="grid grid-cols-1 gap-3 sm:gap-4 md:grid-cols-3">
                        <div class="rounded-lg bg-white p-3 shadow sm:p-4">
                            <h3 class="mb-1.5 text-sm font-semibold text-blue-600 sm:mb-2 sm:text-base">การลงทะเบียน</h3>
                            <p class="text-xs text-slate-600 sm:text-sm">ลงทะเบียนเข้าร่วมกิจกรรมต่างๆ</p>
                        </div>
                        <div class="rounded-lg bg-white p-3 shadow sm:p-4">
                            <h3 class="mb-1.5 text-sm font-semibold text-green-600 sm:mb-2 sm:text-base">การเช็คอิน</h3>
                            <p class="text-xs text-slate-600 sm:text-sm">เช็คอินเข้าร่วมกิจกรรมในวันงาน</p>
                        </div>
                        <div class="rounded-lg bg-white p-3 shadow sm:p-4">
                            <h3 class="mb-1.5 text-sm font-semibold text-purple-600 sm:mb-2 sm:text-base">ประวัติและผลการประเมิน</h3>
                            <p class="text-xs text-slate-600 sm:text-sm">ดูประวัติและผลการประเมินการเข้าร่วม</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. หน้าจอและเลย์เอาต์ -->
            <section class="mb-6 sm:mb-8" id="screens">
                <h2 class="mb-3 text-xl font-bold text-slate-900 sm:mb-4 sm:text-2xl">2. หน้าจอและเลย์เอาต์</h2>

                <div class="mb-4 space-y-4 sm:mb-6">
                    <div class="rounded-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-blue-600 sm:text-lg">หน้าหลัก (/hrd)</h3>
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-slate-700 sm:ml-6">
                            <li>รายการโปรแกรมที่เปิดรับลงทะเบียนและโปรแกรมที่คุณลงทะเบียนแล้ว</li>
                            <li>แถบ <strong>Live — เช็คอินได้ตอนนี้</strong> แสดงเฉพาะเซสชันที่อยู่ในช่วงเช็คอิน (เริ่ม 30 นาทีก่อนเวลาจัด จนถึงเวลาสิ้นสุดเซสชัน)</li>
                            <li>แต่ละการ์ดแสดง <strong>เวลาจัด</strong> และ <strong>เปิดเช็คอิน</strong> เป็นบล็อกเต็มความกว้าง</li>
                            <li>เช็คอินจากหน้านี้แล้วระบบจะอยู่หน้าเดิม (ไม่บังคับไปหน้ารายละเอียดโปรเจกต์)</li>
                        </ul>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-blue-600 sm:text-lg">หน้ารายละเอียดโปรเจกต์</h3>
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-slate-700 sm:ml-6">
                            <li>ส่วนหัว: ชื่อโปรแกรม รายละเอียด และสถานะการลงทะเบียน (ไม่แสดงกลุ่ม/ที่นั่งที่ส่วนหัว)</li>
                            <li>หลังลงทะเบียน (โปรเจกต์เดี่ยว/หลายเซสชัน): ส่วน <strong>เซสชันและเช็คอิน</strong> รวมตารางเวลา สถานะ กลุ่ม/ที่นั่ง และปุ่มเช็คอินในการ์ดเดียวต่อเซสชัน</li>
                            <li>กิจกรรมแบบเข้าร่วม (ไม่ลงทะเบียน): ส่วน <strong>เช็คอินตอนนี้</strong> แบบ Live เมื่อถึงช่วงเวลา</li>
                            <li>ฟอร์มลงทะเบียน / เพิ่มเซสชัน: เลือกช่วงจากรายการแบบย่อยตามวัน มีสรุปการเลือกที่แถบด้านล่าง</li>
                            <li>ปุ่มรีเฟรชมุมขวาบน (เมื่อมีเซสชันเปิดเช็คอิน) สำหรับอัปเดตสถานะ</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-4 space-y-4 rounded-lg border border-slate-200 bg-slate-50 p-3 sm:mt-6 sm:p-4">
                    @include("hrd.partials.user-manual.example-live-checkin")
                    @include("hrd.partials.user-manual.example-sessions-hub")
                </div>
            </section>

            <!-- 3. ประเภทกิจกรรม -->
            <section class="mb-6 sm:mb-8" id="project-types">
                <h2 class="mb-3 text-xl font-bold text-slate-900 sm:mb-4 sm:text-2xl">3. ประเภทกิจกรรม</h2>
                <div class="space-y-3 sm:space-y-4">
                    <div class="rounded-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-purple-600 sm:mb-3 sm:text-lg">กิจกรรมเข้าร่วม (ไม่ต้องลงทะเบียน)</h3>
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-slate-700 sm:ml-6 sm:space-y-2">
                            <li>ไม่ต้องลงทะเบียนล่วงหน้า</li>
                            <li>สามารถเช็คอินได้โดยตรงในวันงาน</li>
                            <li>เหมาะสำหรับกิจกรรมเปิดกว้างหรือการประชุมทั่วไป</li>
                            <li>ยกเลิกการลงทะเบียนได้เสมอ แม้จะเช็คอินแล้ว</li>
                        </ul>
                    </div>
                    <div class="rounded-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-blue-600 sm:mb-3 sm:text-lg">กิจกรรมเดี่ยว (ลงทะเบียน 1 ครั้ง)</h3>
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-slate-700 sm:ml-6 sm:space-y-2">
                            <li>ต้องลงทะเบียนก่อนเช็คอิน</li>
                            <li>ลงทะเบียนได้เพียง 1 ครั้งต่อกิจกรรม</li>
                            <li>เหมาะสำหรับกิจกรรมที่ต้องการจำกัดจำนวนผู้เข้าร่วม</li>
                            <li>ยกเลิกได้เฉพาะก่อนเช็คอินเท่านั้น</li>
                        </ul>
                    </div>
                    <div class="rounded-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-green-600 sm:mb-3 sm:text-lg">กิจกรรมหลายเซสชัน (ลงทะเบียนหลายครั้ง)</h3>
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-slate-700 sm:ml-6 sm:space-y-2">
                            <li>ลงทะเบียนได้หลายครั้งในกิจกรรมเดียวกัน</li>
                            <li>เหมาะสำหรับกิจกรรมที่จัดหลายรอบหรือหลายวัน</li>
                            <li>สามารถเลือกช่วงเวลาและวันที่ที่ต้องการได้</li>
                            <li>ยกเลิกได้เฉพาะก่อนเช็คอินเท่านั้น</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 3. กิจกรรมเข้าร่วม -->
            <section class="mb-6 sm:mb-8" id="attendance-projects">
                <h2 class="mb-3 text-xl font-bold text-slate-900 sm:mb-4 sm:text-2xl">4. กิจกรรมเข้าร่วม</h2>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">วิธีการเข้าร่วม</h3>
                    <div class="rounded-lg bg-slate-50 p-3 sm:p-4">
                        <ol class="ml-4 list-decimal space-y-1.5 text-sm text-slate-700 sm:ml-6 sm:space-y-2">
                            <li>เข้าไปดูรายละเอียดกิจกรรมที่ต้องการเข้าร่วม</li>
                            <li>รอจนถึงเวลาที่กำหนดในเซสชันนั้นๆ</li>
                            <li>กดปุ่ม "เช็คอินตอนนี้" เมื่อถึงเวลา</li>
                            <li>ระบบจะยืนยันการเข้าร่วมของคุณ</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">คุณสมบัติ</h3>
                    <div class="rounded-lg bg-slate-50 p-3 sm:p-4">
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-slate-700 sm:ml-6 sm:space-y-2">
                            <li>ไม่ต้องลงทะเบียนก่อน</li>
                            <li>เช็คอินได้ทันทีเมื่อถึงเวลา</li>
                            <li>ยกเลิกการลงทะเบียนได้เสมอ แม้จะเช็คอินแล้ว</li>
                            <li>เช็คอินได้เพียงครั้งเดียวต่อเซสชัน</li>
                        </ul>
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 sm:p-4">
                    @include("hrd.partials.user-manual.example-live-checkin")
                </div>
            </section>

            <!-- 4. กิจกรรมเดี่ยว -->
            <section class="mb-6 sm:mb-8" id="single-projects">
                <h2 class="mb-3 text-xl font-bold text-slate-900 sm:mb-4 sm:text-2xl">5. กิจกรรมเดี่ยว</h2>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">ขั้นตอนการลงทะเบียน</h3>
                    <div class="rounded-lg bg-slate-50 p-3 sm:p-4">
                        <ol class="ml-4 list-decimal space-y-1.5 text-sm text-slate-700 sm:ml-6 sm:space-y-2">
                            <li>เลือกเซสชันที่ต้องการและคลิกปุ่ม "ลงทะเบียน"</li>
                            <li>ระบบจะยืนยันการลงทะเบียนของคุณ</li>
                            <li>รอจนถึงเวลาที่กำหนดในเซสชันที่ลงทะเบียน</li>
                            <li>กดปุ่ม "เช็คอินตอนนี้" เพื่อยืนยันการเข้าร่วม</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">คุณสมบัติ</h3>
                    <div class="rounded-lg bg-slate-50 p-3 sm:p-4">
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-slate-700 sm:ml-6 sm:space-y-2">
                            <li>ต้องลงทะเบียนก่อนเช็คอิน</li>
                            <li>ลงทะเบียนได้ 1 เซสชันเท่านั้น</li>
                            <li>ยกเลิกได้เฉพาะก่อนเช็คอิน</li>
                            <li>ไม่สามารถลงทะเบียนในวันเดียวกันได้ (บางกิจกรรม)</li>
                        </ul>
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 sm:p-4">
                    @include("hrd.partials.user-manual.example-registration")
                </div>
            </section>

            <!-- 5. กิจกรรมหลายเซสชัน -->
            <section class="mb-6 sm:mb-8" id="multiple-projects">
                <h2 class="mb-3 text-xl font-bold text-slate-900 sm:mb-4 sm:text-2xl">6. กิจกรรมหลายเซสชัน</h2>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">ขั้นตอนการลงทะเบียน</h3>
                    <div class="rounded-lg bg-slate-50 p-3 sm:p-4">
                        <ol class="ml-4 list-decimal space-y-1.5 text-sm text-slate-700 sm:ml-6 sm:space-y-2">
                            <li>เลือกเซสชันที่ต้องการจากรายการที่มี</li>
                            <li>คลิกปุ่ม "ลงทะเบียน" สำหรับเซสชันที่เลือก</li>
                            <li>เช็คอินเมื่อถึงเวลาในแต่ละเซสชัน</li>
                            <li>สามารถลงทะเบียนเซสชันอื่นเพิ่มเติมได้</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">คุณสมบัติ</h3>
                    <div class="rounded-lg bg-slate-50 p-3 sm:p-4">
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-slate-700 sm:ml-6 sm:space-y-2">
                            <li>ลงทะเบียนได้หลายเซสชัน</li>
                            <li>เข้าร่วมได้หลายเซสชัน</li>
                            <li>ยกเลิกได้เฉพาะก่อนเช็คอิน</li>
                            <li>ไม่สามารถลงทะเบียนซ้ำในเซสชันเดียวกัน</li>
                        </ul>
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 sm:p-4">
                    @include("hrd.partials.user-manual.example-sessions-hub")
                    <p class="mt-3 text-xs text-slate-600">หลังลงทะเบียนหลายเซสชัน แต่ละรอบมีสถานะและเช็คอินแยกกัน</p>
                </div>
            </section>

            <!-- 7. ขั้นตอนการลงทะเบียน -->
            <section class="mb-6 sm:mb-8" id="registration-process">
                <h2 class="mb-3 text-xl font-bold text-slate-900 sm:mb-4 sm:text-2xl">7. ขั้นตอนการลงทะเบียน</h2>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">การลงทะเบียนทั่วไป</h3>
                    <div class="rounded-lg bg-slate-50 p-3 sm:p-4">
                        <ol class="ml-4 list-decimal space-y-1.5 text-sm text-slate-700 sm:ml-6 sm:space-y-2">
                            <li>เข้าสู่ระบบด้วยบัญชีผู้ใช้ของคุณ</li>
                            <li>เลือกกิจกรรมที่ต้องการจากรายการ</li>
                            <li>อ่านรายละเอียดและเงื่อนไขของกิจกรรม</li>
                            <li>เลือกเซสชันที่ต้องการ (ถ้ามีหลายเซสชัน)</li>
                            <li>คลิกปุ่ม "ลงทะเบียน" ที่แถบสรุปด้านล่าง</li>
                            <li>เมื่อสำเร็จ ระบบแสดงข้อความยืนยันการลงทะเบียนเท่านั้น (ไม่แสดงชื่อกลุ่มทันที แม้โปรเจกต์เปิดจัดกลุ่มอัตโนมัติ)</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">ข้อควรระวัง</h3>
                    <div class="rounded-lg bg-yellow-50 p-3 sm:p-4">
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-yellow-700 sm:ml-6 sm:space-y-2">
                            <li>ตรวจสอบวันที่และเวลาของกิจกรรมให้ถูกต้อง</li>
                            <li>สำหรับกิจกรรมเดี่ยว ลงทะเบียนได้เพียง 1 ครั้งเท่านั้น</li>
                            <li>หากกิจกรรมเต็มแล้ว จะไม่สามารถลงทะเบียนได้</li>
                            <li>การลงทะเบียนบางกิจกรรมไม่เปิดให้ลงทะเบียนในวันที่มีการจัดหลักสูตร</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 7. ขั้นตอนการเช็คอิน -->
            <section class="mb-6 sm:mb-8" id="check-in-process">
                <h2 class="mb-3 text-xl font-bold text-slate-900 sm:mb-4 sm:text-2xl">8. ขั้นตอนการเช็คอิน</h2>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">การเช็คอินทั่วไป</h3>
                    <div class="rounded-lg bg-slate-50 p-3 sm:p-4">
                        <ol class="ml-4 list-decimal space-y-1.5 text-sm text-slate-700 sm:ml-6 sm:space-y-2">
                            <li>เปิดหน้าหลัก HRD หรือหน้ารายละเอียดโปรเจกต์ที่ลงทะเบียน</li>
                            <li>รอจนเข้าช่วงเช็คอิน (เริ่ม 30 นาทีก่อนเวลาจัด จนสิ้นสุดเซสชัน)</li>
                            <li>เมื่อถึงเวลา ปุ่ม <strong>เช็คอินตอนนี้</strong> จะปรากฏในการ์ดเซสชัน</li>
                            <li>กดปุ่มแล้วตรวจสอบรายละเอียดในหน้าต่างยืนยัน (ชื่อโปรแกรม วัน เวลา สถานที่)</li>
                            <li>กดยืนยันเพื่อบันทึกการเข้าร่วม</li>
                            <li>ระบบแสดงข้อความสำเร็จและอยู่หน้าเดิม — จากนั้นจะเห็นกลุ่ม/ที่นั่ง (ถ้าโปรเจกต์เปิดใช้) ในการ์ดเซสชันนั้น</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">ข้อควรระวัง</h3>
                    <div class="rounded-lg bg-red-50 p-3 sm:p-4">
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-red-700 sm:ml-6 sm:space-y-2">
                            <li>เช็คอินได้ตั้งแต่ 30 นาทีก่อนเวลาจัด จนถึงเวลาสิ้นสุดเซสชัน</li>
                            <li>เช็คอินได้เพียงครั้งเดียวต่อเซสชัน</li>
                            <li>หากเช็คอินแล้ว จะไม่สามารถยกเลิกการลงทะเบียนได้ (สำหรับกิจกรรมเดี่ยวและหลายเซสชัน)</li>
                            <li>ตรวจสอบเวลาให้ถูกต้องก่อนเช็คอิน</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 9. กลุ่ม ที่นั่ง และลิงก์ -->
            <section class="mb-6 sm:mb-8" id="seat-group-links">
                <h2 class="mb-3 text-xl font-bold text-slate-900 sm:mb-4 sm:text-2xl">9. กลุ่ม ที่นั่ง และลิงก์</h2>

                <div class="mb-4 rounded-lg border border-violet-200 bg-violet-50 p-4 sm:mb-6 sm:p-6">
                    <h3 class="mb-2 text-base font-semibold text-violet-900">กลุ่มและที่นั่ง</h3>
                    <ul class="ml-4 list-disc space-y-1.5 text-sm text-violet-950 sm:ml-6">
                        <li>แสดงเฉพาะในการ์ด <strong>เช็คอิน / เซสชันและเช็คอิน</strong> ไม่แสดงที่ส่วนหัวโปรเจกต์</li>
                        <li>ก่อนเช็คอิน: ข้อความ <em>กรุณาเช็คอินก่อนเพื่อดูกลุ่ม/ที่นั่งของคุณ</em></li>
                        <li>หลังเช็คอิน: แสดงการ์ดกลุ่ม (สีม่วง) และที่นั่ง (สีเขียว) แยกตามเซสชัน — หลายเซสชันอาจได้กลุ่ม/ที่นั่งต่างกัน</li>
                        <li>หากยังไม่จัด: แสดง <em>รอจัดกลุ่ม</em> หรือ <em>รอจัดที่นั่ง</em> จนแอดมินหรือระบบจัดให้</li>
                    </ul>
                </div>

                <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 sm:p-6">
                    <h3 class="mb-2 text-base font-semibold text-blue-900">ลิงก์ทรัพยากรของโปรเจกต์</h3>
                    <ul class="ml-4 list-disc space-y-1.5 text-sm text-blue-950 sm:ml-6">
                        <li>ปรากฏหลังเช็คอินแล้ว และอยู่ในช่วงเวลาเซสชัน (เช่นเดียวกับช่วงเช็คอิน)</li>
                        <li>แสดงในการ์ดเซสชันใต้เวลาที่เช็คอิน</li>
                        <li>ลิงก์ที่แอดมินตั้งเวลาจำกัดจะแสดงเฉพาะเมื่ออยู่ในช่วงที่เปิด</li>
                    </ul>
                </div>

                <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-3 sm:mt-6 sm:p-4">
                    @include("hrd.partials.user-manual.example-assignment-checked-in")
                </div>
            </section>

            <!-- 10. การดูผลการประเมิน -->
            <section class="mb-6 sm:mb-8" id="results">
                <h2 class="mb-3 text-xl font-bold text-slate-900 sm:mb-4 sm:text-2xl">10. การดูผลการประเมิน</h2>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">การเข้าถึงผลการประเมิน</h3>
                    <div class="rounded-lg bg-slate-50 p-3 sm:p-4">
                        <ol class="ml-4 list-decimal space-y-1.5 text-sm text-slate-700 sm:ml-6 sm:space-y-2">
                            <li>ไปที่หน้า "ประวัติการเข้าร่วม"</li>
                            <li>ค้นหากิจกรรมที่ต้องการดูผลการประเมิน</li>
                            <li>หากมีผลการประเมิน จะแสดงปุ่ม "ผลการประเมิน"</li>
                            <li>คลิกเพื่อดูรายละเอียดผลการประเมิน</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">ข้อมูลที่แสดง</h3>
                    <div class="rounded-lg bg-purple-50 p-3 sm:p-4">
                        <ul class="ml-4 list-disc space-y-1.5 text-sm text-purple-700 sm:ml-6 sm:space-y-2">
                            <li>คะแนนในแต่ละหมวดหมู่การประเมิน</li>
                            <li>ผลการประเมินรวม</li>
                            <li>วันที่ประเมิน</li>
                            <li>หมายเหตุหรือข้อเสนอแนะ (ถ้ามี)</li>
                        </ul>
                    </div>
                </div>

                <div class="rounded-lg bg-purple-50 p-3 sm:p-4">
                    <h3 class="mb-2 text-sm font-semibold text-purple-800 sm:text-base">ตัวอย่างหน้าจอผลการประเมิน</h3>
                    <div class="rounded-lg border-2 border-purple-200 bg-white p-3 sm:p-4">
                        <div class="mb-2 flex items-center justify-between sm:mb-3">
                            <h4 class="text-sm font-semibold text-purple-800 sm:text-base">ผลการประเมิน</h4>
                            <i class="fas fa-chart-line text-purple-600"></i>
                        </div>
                        <div class="space-y-1.5 sm:space-y-2">
                            <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                <span class="text-xs font-medium sm:text-sm">ความรู้ความเข้าใจ</span>
                                <span class="text-xs font-bold text-purple-600 sm:text-sm">85/100</span>
                            </div>
                            <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                <span class="text-xs font-medium sm:text-sm">ทักษะการปฏิบัติ</span>
                                <span class="text-xs font-bold text-purple-600 sm:text-sm">90/100</span>
                            </div>
                            <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                <span class="text-xs font-medium sm:text-sm">ความพึงพอใจ</span>
                                <span class="text-xs font-bold text-purple-600 sm:text-sm">88/100</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 9. การยกเลิกการลงทะเบียน -->
            <section class="mb-6 sm:mb-8" id="cancellation">
                <h2 class="mb-3 text-xl font-bold text-slate-900 sm:mb-4 sm:text-2xl">11. การยกเลิกการลงทะเบียน</h2>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">วิธีการยกเลิก</h3>
                    <div class="rounded-lg bg-slate-50 p-3 sm:p-4">
                        <ol class="ml-4 list-decimal space-y-1.5 text-sm text-slate-700 sm:ml-6 sm:space-y-2">
                            <li>ไปที่กิจกรรมที่ลงทะเบียน</li>
                            <li>คลิกปุ่ม "ยกเลิกการลงทะเบียน"</li>
                            <li>ยืนยันการยกเลิกในหน้าต่างที่ปรากฏ</li>
                            <li>ระบบจะลบข้อมูลการลงทะเบียนของคุณออก</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">เงื่อนไขการยกเลิก</h3>
                    <div class="grid grid-cols-1 gap-3 sm:gap-4 md:grid-cols-2">
                        <div class="rounded-lg bg-green-50 p-3 sm:p-4">
                            <h4 class="mb-1.5 text-sm font-semibold text-green-800 sm:mb-2 sm:text-base">กิจกรรมเข้าร่วม</h4>
                            <ul class="ml-3 list-disc space-y-1 text-xs text-green-700 sm:ml-4 sm:text-sm">
                                <li>ยกเลิกได้เสมอ</li>
                                <li>แม้จะเช็คอินแล้วก็สามารถยกเลิกได้</li>
                                <li>การยกเลิกจะลบข้อมูลการเข้าร่วมออกจากระบบ</li>
                            </ul>
                        </div>
                        <div class="rounded-lg bg-yellow-50 p-3 sm:p-4">
                            <h4 class="mb-1.5 text-sm font-semibold text-yellow-800 sm:mb-2 sm:text-base">กิจกรรมเดี่ยว/หลายเซสชัน</h4>
                            <ul class="ml-3 list-disc space-y-1 text-xs text-yellow-700 sm:ml-4 sm:text-sm">
                                <li>ยกเลิกได้เฉพาะก่อนเช็คอินเท่านั้น</li>
                                <li>ไม่สามารถยกเลิกได้หลังจากเช็คอินแล้ว</li>
                                <li>การลงทะเบียนจะถูกล็อคหลังจากเช็คอิน</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 10. คำถามที่พบบ่อย -->
            <section class="mb-6 sm:mb-8" id="faq">
                <h2 class="mb-3 text-xl font-bold text-slate-900 sm:mb-4 sm:text-2xl">12. คำถามที่พบบ่อย</h2>

                <div class="space-y-4 sm:space-y-6">
                    <div class="rounded-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">ฉันสามารถยกเลิกการลงทะเบียนได้หรือไม่?</h3>
                        <p class="text-sm text-slate-700 sm:text-base">
                            ขึ้นอยู่กับประเภทของกิจกรรม:
                        </p>
                        <ul class="ml-4 mt-1.5 list-disc space-y-1 text-sm text-slate-700 sm:ml-6 sm:mt-2">
                            <li><strong>กิจกรรมเข้าร่วม:</strong> ยกเลิกได้เสมอ แม้จะเช็คอินแล้ว</li>
                            <li><strong>กิจกรรมเดี่ยว/หลายเซสชัน:</strong> ยกเลิกได้เฉพาะก่อนเช็คอินเท่านั้น</li>
                        </ul>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">ทำไมฉันไม่เห็นปุ่มลงทะเบียน?</h3>
                        <p class="text-sm text-slate-700 sm:text-base">อาจเป็นเพราะ:</p>
                        <ul class="ml-4 mt-1.5 list-disc space-y-1 text-sm text-slate-700 sm:ml-6 sm:mt-2">
                            <li>กิจกรรมยังไม่เปิดรับลงทะเบียน</li>
                            <li>หมดเวลาลงทะเบียนแล้ว</li>
                            <li>กิจกรรมไม่เปิดให้ลงทะเบียนในวันที่มีการจัดหลักสูตร</li>
                            <li>คุณได้ลงทะเบียนแล้ว (สำหรับกิจกรรมเดี่ยว)</li>
                        </ul>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">ฉันสามารถเช็คอินได้เมื่อไหร่?</h3>
                        <p class="text-sm text-slate-700 sm:text-base">
                            ตั้งแต่ 30 นาทีก่อนเวลาจัดของเซสชัน จนถึงเวลาสิ้นสุดเซสชัน
                            ปุ่มเช็คอินจะปรากฏในหน้าหลัก (Live) หรือในการ์ดเซสชันที่หน้ารายละเอียดโปรเจกต์
                        </p>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">ลงทะเบียนแล้วทำไมยังไม่เห็นกลุ่ม?</h3>
                        <p class="text-sm text-slate-700 sm:text-base">
                            ระบบซ่อนรายละเอียดกลุ่มและที่นั่งจนกว่าคุณจะเช็คอินในเซสชันนั้น
                            หลังเช็คอิน ดูได้ในการ์ดเซสชันเดียวกับปุ่มเช็คอิน
                        </p>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">ฉันสามารถลงทะเบียนซ้ำได้หรือไม่?</h3>
                        <p class="text-sm text-slate-700 sm:text-base">ขึ้นอยู่กับประเภทของกิจกรรม:</p>
                        <ul class="ml-4 mt-1.5 list-disc space-y-1 text-sm text-slate-700 sm:ml-6 sm:mt-2">
                            <li><strong>กิจกรรมเดี่ยว:</strong> ไม่สามารถลงทะเบียนซ้ำได้</li>
                            <li><strong>กิจกรรมหลายเซสชัน:</strong> ลงทะเบียนได้หลายเซสชัน แต่ไม่ซ้ำกัน</li>
                            <li><strong>กิจกรรมเข้าร่วม:</strong> ไม่ต้องลงทะเบียน</li>
                        </ul>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">ฉันจะรู้ได้อย่างไรว่ากิจกรรมเต็มแล้ว?</h3>
                        <p class="text-sm text-slate-700 sm:text-base">
                            ระบบจะแสดงสถานะ "เต็มแล้ว" เมื่อกิจกรรมมีผู้ลงทะเบียนครบจำนวนที่กำหนดแล้ว
                            ในกรณีนี้ปุ่มลงทะเบียนจะถูกซ่อนหรือแสดงเป็นสีเทา
                        </p>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">ฉันสามารถดูผลการประเมินได้เมื่อไหร่?</h3>
                        <p class="text-sm text-slate-700 sm:text-base">
                            ผลการประเมินจะแสดงหลังจากที่คุณเข้าร่วมกิจกรรมและผู้ดูแลระบบได้บันทึกผลการประเมินแล้ว
                            คุณสามารถดูได้ในหน้า "ประวัติการเข้าร่วม" โดยคลิกที่ "ผลการประเมิน"
                        </p>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="mb-2 text-base font-semibold text-slate-700 sm:mb-3 sm:text-lg">ระบบใช้งานได้บนมือถือหรือไม่?</h3>
                        <p class="text-sm text-slate-700 sm:text-base">
                            ใช่ ระบบได้รับการออกแบบให้ใช้งานได้ดีทั้งบนคอมพิวเตอร์และมือถือ
                            หน้าจอจะปรับขนาดตามอุปกรณ์ที่ใช้งาน และปุ่มต่างๆ มีขนาดเหมาะสมสำหรับการสัมผัส
                        </p>
                    </div>
                </div>
            </section>

            <!-- Back to top button -->
            <div class="mt-6 text-center sm:mt-8">
                <a class="hrd-btn-secondary inline-flex" href="#overview">
                    <i class="fas fa-arrow-up text-sm sm:text-base"></i>
                    <span class="text-xs sm:text-sm">กลับขึ้นด้านบน</span>
                </a>
            </div>
        </div>
    @include("hrd.partials.app-page", ["appPageClose" => true])
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
