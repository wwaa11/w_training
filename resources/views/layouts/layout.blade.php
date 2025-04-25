<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    @yield("meta")
    <title inertia>PR9 Training</title>
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
    @yield("content")
</body>
<script>
    function logout() {
        axios.post('{{ env("APP_URL") }}/logout').then((res) => {
            window.location.href = '{{ env("APP_URL") }}/login';
        });
    }
</script>
@yield("scripts")

</html>
