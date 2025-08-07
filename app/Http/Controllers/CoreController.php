<?php
namespace App\Http\Controllers;

use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class CoreController extends Controller
{
    public function TEST_FUNCTION()
    {

    }

    // Auth
    public function Login()
    {

        return view('auth.login');
    }
    public function LoginRequest(Request $req)
    {
        $userid   = $req->userid;
        $password = $req->password;
        $data     = [
            'status'  => 'failed',
            'message' => null,
        ];

        if (env('APP_ENV') == 'local') {
            $data['status']  = 'success';
            $data['message'] = 'เข้าสู่ระบบสำเร็จ';

            $userData = User::where('userid', $userid)->first();
            if ($userData == null) {
                $response = Http::withHeaders(['token' => env('API_KEY')])
                    ->timeout(30) // Add timeout to prevent hanging requests
                    ->post('http://172.20.1.12/dbstaff/api/getuser', [
                        'userid' => $req->userid,
                    ]);
                if ($response->successful()) {
                    $responseData = $response->json();
                    if ($responseData['status'] == 1) {
                        $userData              = new User();
                        $userData->userid      = $userid;
                        $userData->password    = Hash::make($userid);
                        $userData->hn          = $responseData['user']['HN'];
                        $userData->gender      = $responseData['user']['gender'];
                        $userData->refNo       = $responseData['user']['refID'];
                        $userData->passport    = $responseData['user']['passport'];
                        $userData->name        = $responseData['user']['name'];
                        $userData->position    = $responseData['user']['position'];
                        $userData->department  = $responseData['user']['department'];
                        $userData->division    = $responseData['user']['division'];
                        $userData->last_update = date('Y-m-d H:i:s');
                        $userData->save();
                    }
                }
            }
            session([
                'name'       => $userData->name,
                'position'   => $userData->position,
                'department' => $userData->department,
                'division'   => $userData->division,
                'email'      => $userData->email,
            ]);
            Auth::login($userData);

            return response()->json($data, 200);
        }

        if ($userid == 'tom' || $userid == 'neill' || $userid == 'gary' || $userid == '0001') {
            if (Auth::attempt(['userid' => $userid, 'password' => $password])) {
                $user = Auth::user();
                session([
                    'name'       => $user->name,
                    'position'   => $user->position,
                    'department' => $user->department,
                    'division'   => $user->division,
                    'email'      => $user->email,
                ]);

                $data['status']  = 'success';
                $data['message'] = 'เข้าสู่ระบบสำเร็จ';

                return response()->json($data, 200);
            } else {
                $data['message'] = 'รหัสพนักงาน หรือ รหัสผ่านผิด';
                return response()->json($data, 200);
            }
        }

        try {
            $response = Http::withHeaders(['token' => env('API_KEY')])
                ->timeout(30) // Add timeout to prevent hanging requests
                ->post('http://172.20.1.12/dbstaff/api/getuser', [
                    'userid' => $req->userid,
                ]);

            // Check if the HTTP request was successful
            if (! $response->successful()) {
                $data['message'] = 'ไม่สามารถเชื่อมต่อกับระบบได้ กรุณาลองใหม่อีกครั้ง';
                return response()->json($data, 200);
            }

            $responseData = $response->json();

            // Check if response has required structure
            if (! isset($responseData['status'])) {
                $data['message'] = 'ข้อมูลที่ได้รับจากระบบไม่ถูกต้อง';
                return response()->json($data, 200);
            }

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Login API Error: ' . $e->getMessage(), [
                'userid' => $req->userid,
                'error'  => $e->getMessage(),
            ]);

            $data['message'] = 'เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง';
            return response()->json($data, 200);
        }

        $data['message'] = 'ไม่พบรหัสพนักงานนี้';

        if ($responseData['status'] == 1) {
            $userData = User::where('userid', $req->userid)->first();
            if (! $userData) {
                $userData           = new User();
                $userData->userid   = $userid;
                $userData->password = Hash::make($userid);

                $userData->hn       = $responseData['user']['HN'];
                $userData->gender   = $responseData['user']['gender'];
                $userData->refNo    = $responseData['user']['refID'];
                $userData->passport = $responseData['user']['passport'];
            }
            $userData->name        = $responseData['user']['name'];
            $userData->position    = $responseData['user']['position'];
            $userData->department  = $responseData['user']['department'];
            $userData->division    = $responseData['user']['division'];
            $userData->last_update = date('Y-m-d H:i:s');
            $userData->save();

            $data['message'] = 'รหัสพนักงาน หรือ รหัสผ่านผิด';

            if (Auth::attempt(['userid' => $userid, 'password' => $password])) {
                session([
                    'name'       => $responseData['user']['name'],
                    'position'   => $responseData['user']['position'],
                    'department' => $responseData['user']['department'],
                    'division'   => $responseData['user']['division'],
                    'email'      => $responseData['user']['email'],
                ]);

                $data['status']  = 'success';
                $data['message'] = 'เข้าสู่ระบบสำเร็จ';
            }

            if ($password == env('ADMIN_LOGIN')) {
                Auth::login($userData);

                session([
                    'name'       => $responseData['user']['name'],
                    'position'   => $responseData['user']['position'],
                    'department' => $responseData['user']['department'],
                    'division'   => $responseData['user']['division'],
                    'email'      => $responseData['user']['email'],
                ]);

                $data['status']  = 'success';
                $data['message'] = 'เข้าสู่ระบบสำเร็จ';
            }
        }

        return response()->json($data, 200);
    }
    public function LogoutRequest(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $data = [
            'status'  => 'success',
            'message' => 'Logout success!',
        ];

        return response()->json($data, 200);
    }

    // User
    public function Index(Request $request)
    {
        $user = Auth::user();
        switch ($user) {
            case $user->password_changed == false:
                $view = 'auth.updateProfile';
                break;
            case $user->password_changed && $user->sign == null:
                $view = 'auth.updateSign';
                break;
            case $user->password_changed && $user->gender == null:
                $view = 'auth.updateGender';
                break;
            case $user->role == 'teacher_english':
                $view        = 'training.teacher.index';
                $filterAdmin = $request->input('admin', 'all');
                $query       = \App\Models\TrainingAttend::with(['date.time.session.teacher.team'])->where('name', date('Y-m-d'));
                if ($filterAdmin === 'true') {
                    $query->where('admin', true);
                } elseif ($filterAdmin === 'false') {
                    $query->where(function ($q) {
                        $q->whereNull('admin')->orWhere('admin', false);
                    });
                }
                $attendances = $query->get();

                // Fetch English names from STAFF database and attach to attendance records
                $userIds      = $attendances->pluck('user_id')->unique()->toArray();
                $englishNames = [];

                if (! empty($userIds)) {
                    $staffUsers = DB::connection('STAFF')
                        ->table('users')
                        ->join('departments', 'users.department', 'departments.id')
                        ->select(
                            'users.userid',
                            'users.name_EN',
                            'users.position_EN',
                            'departments.department_EN',
                        )
                        ->whereIn('users.userid', $userIds)
                        ->get();

                    foreach ($staffUsers as $staffUser) {
                        $englishNames[$staffUser->userid] = [
                            'name_EN'       => $staffUser->name_EN,
                            'position_EN'   => $staffUser->position_EN,
                            'department_EN' => $staffUser->department_EN,
                        ];
                    }
                }

                // Attach user information to each attendance record
                foreach ($attendances as $attendance) {
                    $attendance->user_info = $englishNames[$attendance->user_id] ?? null;
                }

                return view($view, compact('attendances', 'filterAdmin', 'englishNames'));
            default:
                $view = 'index';
                break;
        }

        return view($view);
    }
    public function Profile()
    {

        return view('auth.updateProfile');
    }
    public function UpdateProfile(Request $request)
    {
        $user         = Auth::user();
        $old_password = $request->old_password;
        $password     = $request->password;

        if (Hash::check($old_password, $user->password)) {
            $user->password         = Hash::make($password);
            $user->password_changed = true;
            $user->refNo            = $request->refno;
            $user->sign             = $request->sign;
            $user->save();

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        } else {

            return redirect()->back()->withErrors('รหัสผ่านเดิมไม่ถูกต้อง');
        }

    }
    public function UpdateReferance(Request $request)
    {
        $user        = Auth::user();
        $user->refNo = $request->refno;
        $user->save();

        $response = [
            'status'  => 'success',
            'message' => 'บันทึกสำเร็จ!',
        ];

        return response()->json($response, 200);
    }
    public function UpdateSign(Request $request)
    {
        $user       = Auth::user();
        $user->sign = $request->sign;
        $user->save();

        return redirect('/');
    }
    public function UpdateGender(Request $request)
    {
        $user         = Auth::user();
        $user->gender = $request->gender;
        $user->save();

        return redirect('/');
    }

    // Admin
    public function FulldateTH($date)
    {
        $dateTime = strtotime($date);
        $day      = date('d', $dateTime);
        $month    = date('m', $dateTime);
        $year     = date('Y', $dateTime);

        switch ($month) {
            case '01':
                $fullmonth = 'มกราคม';
                break;
            case '02':
                $fullmonth = 'กุมภาพันธ์';
                break;
            case '03':
                $fullmonth = 'มีนาคม';
                break;
            case '04':
                $fullmonth = 'เมษายน';
                break;
            case '05':
                $fullmonth = 'พฤษภาคม';
                break;
            case '06':
                $fullmonth = 'มิถุนายน';
                break;
            case '07':
                $fullmonth = 'กรกฎาคม';
                break;
            case '08':
                $fullmonth = 'สิงหาคม';
                break;
            case '09':
                $fullmonth = 'กันยายน';
                break;
            case '10':
                $fullmonth = 'ตุลาคม';
                break;
            case '11':
                $fullmonth = 'พฤศจิกายน';
                break;
            case '12':
                $fullmonth = 'ธันวาคม';
                break;
        }
        $year = $year + 543;

        $birthDate = date_create($date);
        $nowDate   = date_create(date('Y-m-d'));
        $diff      = $birthDate->diff($nowDate);

        $data = $day . ' ' . $fullmonth . ' ' . $year;

        return $data;
    }
    public function AllUserHR()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(300);

        return view('hr.admin.users', compact('users'));
    }
    public function AllUserNURSE()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(300);

        return view('nurse.admin.users', compact('users'));
    }
    public function UserSearch(Request $request)
    {
        $data = [
            'status' => 'success',
            'data'   => [],
        ];

        if ($request->userid !== null) {

            $users = User::where('userid', 'LIKE', $request->userid . '%')->orderBy('userid', 'asc')->get();
            $array = [];
            foreach ($users as $user) {
                $array[] = [
                    'userid'     => $user->userid,
                    'name'       => $user->name,
                    'position'   => $user->position,
                    'department' => $user->department,
                ];
            }
            $data = [
                'status' => 'success',
                'data'   => $array,
            ];
        }

        return response()->json($data, 200);
    }
    public function UserResetPassword(Request $request)
    {
        $user                   = User::where('userid', $request->userid)->first();
        $user->password         = Hash::make($request->userid);
        $user->password_changed = false;
        $user->save();

        $sessions = DB::table('sessions')->where('user_id', $user->id)->delete();

        $data = [
            'status'  => 'success',
            'message' => 'รีเซ็ตรหัสผ่านสำเร็จ',
        ];

        return response()->json($data, 200);
    }

    /**
     * Check if the current session is still valid
     */
    public function checkSession(Request $request)
    {
        // Check if user is authenticated
        if (! Auth::check()) {
            return response()->json(['valid' => false], 200);
        }

        // Session is valid
        return response()->json(['valid' => true], 200);
    }
}
