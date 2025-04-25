<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    @yield("meta")
    <title inertia>PR9 HRD</title>
    <link href="{{ url("images/Logo.ico") }}" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="{{ url("css/all.min.css") }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite("resources/css/app.css")
</head>
<style>
    @font-face {
        font-family: 'Prompt';
        src: url({{ asset("fonts/Prompt.ttf") }});
    }

    .prompt {
        font-family: "Prompt", sans-serif;
        font-weight: 400;
        font-style: normal;
    }
</style>

<body class="prompt relative bg-[#fff]">
    <div class="h-24"></div>
    <div class="fixed left-0 right-0 top-0 z-10 flex h-24 w-full flex-row bg-[#c1dccd] p-3 shadow lg:justify-end lg:gap-6">
        <div class="flex-1 lg:flex-none">
            <a href="{{ env("APP_URL") }}/">
                <img class="aspect-auto h-16" src="{{ url("images/Side Logo.png") }}" alt="">
            </a>
            <div class="hidden text-blue-700 lg:block">
                Training HRD Division
            </div>
        </div>
        <div class="flex text-end text-[#143429] lg:hidden">
            <a class="pe-6 pt-6" href="{{ env("APP_URL") }}/hr/main"><i class="fa-solid fa-house"></i>Home <span class="block text-blue-700 lg:hidden">Training HRD Division</span></a>
            <button class="cursor-pointer p-4 text-end text-2xl text-[#1a3f34]" type="button" onclick="mobileMenu()">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <div class="hidden pt-7 text-[#143429] lg:flex lg:flex-1 lg:gap-6">
            <a href="{{ env("APP_URL") }}/">ประเภทการฝึกอบรม</a>
            <a href="{{ env("APP_URL") }}/hr/main">รายการที่เปิดลงทะเบียน</a>
            <a href="{{ env("APP_URL") }}/hr/history">ประวัติการลงทะเบียน</a>
            @if (auth()->user()->admin)
                <a href="{{ env("APP_URL") }}/hr/admin">Projects Management</a>
                <a href="{{ env("APP_URL") }}/admin/users">Users Management</a>
            @endif
        </div>
        <div class="hidden w-[20%] pt-1 text-center lg:block lg:text-end">
            <div class="">{{ Auth::user()->userid }} {{ session("name") }}</div>
            <div class="">{{ session("department") }}</div>
        </div>
        <span class="absolute bottom-0 left-4 text-sm text-[#143429] lg:hidden">{{ Auth::user()->userid }} {{ session("name") }}</span>
    </div>
    <div class="fixed left-0 right-0 top-24 z-10 hidden bg-white px-3 text-[#143429] shadow lg:hidden" id="mobileMenu">
        <a class="mt-3 block" href="{{ env("APP_URL") }}/">ประเภทการฝึกอบรม</a>
        <a class="mt-3 block" href="{{ env("APP_URL") }}/hr/main">รายการที่เปิดลงทะเบียน</a>
        <a class="mt-3 block" href="{{ env("APP_URL") }}/hr/history">ประวัติการลงทะเบียน</a>
        @if (auth()->user()->admin)
            <a class="mt-3 block" href="{{ env("APP_URL") }}/hr/admin">Projects Management</a>
            <a class="mt-3 block" href="{{ env("APP_URL") }}/admin/users">Users Management</a>
        @endif
        <div class="mt-3 block">{{ Auth::user()->userid }} {{ session("name") }}</div>
        <div class="mb-3 block">{{ session("department") }}</div>
    </div>
    @yield("content")
</body>

<script>
    function mobileMenu() {
        $("#mobileMenu").toggle();
    }

    function refreshPage() {
        window.location.reload();
    }
</script>
@yield("scripts")

</html>
