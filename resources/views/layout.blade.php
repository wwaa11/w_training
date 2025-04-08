<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>PR9 HRD</title>
    <link href="{{ url("images/Logo.ico") }}" rel="shortcut icon">
    <script src="https://kit.fontawesome.com/a20e89230f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    @vite("resources/css/app.css")
</head>
<style>
    .prompt-thin {
        font-family: "Prompt", sans-serif;
        font-weight: 100;
        font-style: normal;
    }

    .prompt-extralight {
        font-family: "Prompt", sans-serif;
        font-weight: 200;
        font-style: normal;
    }

    .prompt-light {
        font-family: "Prompt", sans-serif;
        font-weight: 300;
        font-style: normal;
    }

    .prompt-regular {
        font-family: "Prompt", sans-serif;
        font-weight: 400;
        font-style: normal;
    }

    .prompt-medium {
        font-family: "Prompt", sans-serif;
        font-weight: 500;
        font-style: normal;
    }

    .prompt-semibold {
        font-family: "Prompt", sans-serif;
        font-weight: 600;
        font-style: normal;
    }

    .prompt-bold {
        font-family: "Prompt", sans-serif;
        font-weight: 700;
        font-style: normal;
    }

    .prompt-extrabold {
        font-family: "Prompt", sans-serif;
        font-weight: 800;
        font-style: normal;
    }

    .prompt-black {
        font-family: "Prompt", sans-serif;
        font-weight: 900;
        font-style: normal;
    }
</style>

<body class="prompt-regular relative bg-[#fff]">
    @auth
        <form class="h-24" id="logout-form" action="{{ env("APP_URL") }}/logout" method="POST">
            @csrf
        </form>
        <div class="fixed left-0 right-0 top-0 z-10 flex h-24 w-full flex-row bg-[#c1dccd] p-3 shadow md:justify-end md:gap-6">
            <div class="flex-1 md:flex-none">
                <a href="{{ env("APP_URL") }}/">
                    <img class="aspect-auto h-16" src="{{ url("images/Side Logo.png") }}" alt="">
                </a>
            </div>
            <div class="flex text-end text-[#143429] md:hidden">
                <a class="pe-6 pt-6" href="{{ env("APP_URL") }}/"><i class="fa-solid fa-house"></i> Home</a>
                <button class="cursor-pointer p-4 text-end text-2xl text-[#1a3f34]" type="button" onclick="mobileMenu()">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
            <div class="hidden pt-6 text-[#143429] md:flex md:flex-1 md:gap-6">
                <a href="{{ env("APP_URL") }}/">รายการที่เปิดลงทะเบียน</a>
                <a href="{{ env("APP_URL") }}/history">ประวัติการลงทะเบียน</a>
                @if (auth()->user()->admin)
                    <a href="{{ env("APP_URL") }}/admin">Projects Management</a>
                    <a href="{{ env("APP_URL") }}/admin/users">Users Management</a>
                @endif
            </div>
            <div class="hidden text-center md:block md:text-end">
                <div class="">{{ Auth::user()->userid }} {{ session("name") }}</div>
                <div class="">{{ session("department") }}</div>
                <div class="flex flex-row gap-3">
                    <a class="flex-1 text-start" href="{{ env("APP_URL") }}/changePassword">เปลี่ยนรหัสผ่าน</a>
                    <button class="flex-1 cursor-pointer text-end text-red-600 md:p-0" onclick="logout()">ออกจากระบบ</button>
                </div>
            </div>
            <span class="absolute bottom-0 left-4 text-sm text-[#143429] md:hidden">{{ Auth::user()->userid }} {{ session("name") }}</span>
        </div>
        <div class="fixed left-0 right-0 top-24 z-10 hidden bg-white px-3 text-[#143429] shadow md:hidden" id="mobileMenu">

            <a class="mt-3 block" href="{{ env("APP_URL") }}/">รายการที่เปิดลงทะเบียน</a>
            <a class="mt-3 block" href="{{ env("APP_URL") }}/history">ประวัติการลงทะเบียน</a>
            @if (auth()->user()->admin)
                <a class="mt-3 block" href="{{ env("APP_URL") }}/admin">Projects Management</a>
                <a class="mt-3 block" href="{{ env("APP_URL") }}/admin/users">Users Management</a>
            @endif
            <div class="mt-3 block">{{ Auth::user()->userid }} {{ session("name") }}</div>
            <div class="block">{{ session("department") }}</div>
            <div class="mb-3 flex flex-row gap-3">
                <a class="flex-1 text-start" href="{{ env("APP_URL") }}/changePassword">เปลี่ยนรหัสผ่าน</a>
                <button class="flex-1 text-end text-red-600 md:p-0" onclick="logout()">ออกจากระบบ</button>
            </div>
        </div>
    @endauth
    @yield("content")
</body>
@yield("scripts")
<script>
    function logout() {
        $('#logout-form').submit();
    }

    function mobileMenu() {
        $('#mobileMenu').toggle()
    }
</script>

</html>
