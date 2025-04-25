<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class CoreController extends Controller
{
    public function TEST_FUNCTION()
    {
        die();
    }
    public function DispatchServices()
    {
        SeatAssign::dispatch();
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

        $response = Http::withHeaders(['token' => env('API_KEY')])
            ->post('http://172.20.1.12/dbstaff/api/getuser', [
                'userid' => $req->userid,
            ])
            ->json();

        $data['message'] = 'ไม่พบรหัสพนักงานนี้';

        if ($response['status'] == 1) {
            $userData = User::where('userid', $req->userid)->first();
            if (! $userData) {
                $userData           = new User();
                $userData->userid   = $userid;
                $userData->password = Hash::make($userid);

                $userData->hn       = $response['user']['HN'];
                $userData->gender   = $response['user']['gender'];
                $userData->refNo    = $response['user']['refID'];
                $userData->passport = $response['user']['passport'];
            }
            $userData->name        = $response['user']['name'];
            $userData->position    = $response['user']['position'];
            $userData->department  = $response['user']['department'];
            $userData->division    = $response['user']['division'];
            $userData->last_update = date('Y-m-d H:i:s');
            $userData->save();

            $data['message'] = 'รหัสพนักงาน หรือ รหัสผ่านผิด';

            if (Auth::attempt(['userid' => $userid, 'password' => $password])) {
                session([
                    'name'       => $response['user']['name'],
                    'position'   => $response['user']['position'],
                    'department' => $response['user']['department'],
                    'division'   => $response['user']['division'],
                    'email'      => $response['user']['email'],
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
    public function Index()
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
            default:
                $view = 'index';
                break;
        }

        return view($view);
    }
    public function Profile()
    {

        return view('auth.profile');
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
    public function AllUser()
    {
        $users = User::orderBy('admin', 'desc')->orderBy('created_at', 'desc')->paginate(300);

        return view('admin.users', compact('users'));
    }
    public function UserSearch(Request $request)
    {
        $data = [
            'status' => 'success',
            'data'   => [],
        ];

        if ($req->userid !== null) {

            $users = User::where('userid', 'LIKE', $req->userid . '%')->orderBy('userid', 'asc')->get();
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
        $user                   = User::where('userid', $req->userid)->first();
        $user->password         = Hash::make($req->userid);
        $user->password_changed = false;
        $user->save();

        $sessions = DB::table('sessions')->where('user_id', $user->id)->delete();

        $data = [
            'status'  => 'success',
            'message' => 'รีเซ็ตรหัสผ่านสำเร็จ',
        ];

        return response()->json($data, 200);
    }
}
