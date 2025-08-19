@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-2xl py-10">
        <h1 class="mb-8 flex items-center gap-2 text-3xl font-bold text-blue-800"><i class="fa-solid fa-gears"></i> Training Admin</h1>
        <div class="grid gap-6">
            <a class="block items-center gap-4 rounded-lg bg-white p-6 shadow-lg transition hover:bg-blue-50" href="{{ route("training.admin.approve.index") }}">
                <span class="text-2xl text-teal-600"><i class="fa-solid fa-layer-group"></i></span>
                <span class="text-lg font-semibold text-teal-900">Approve Check IN</span>
                <span class="ml-auto text-teal-400"><i class="fa-solid fa-arrow-right"></i></span>
            </a>
            <a class="block items-center gap-4 rounded-lg bg-white p-6 shadow-lg transition hover:bg-blue-50" href="{{ route("training.admin.register.index") }}">
                <span class="text-2xl text-orange-600"><i class="fa-solid fa-user-check"></i></span>
                <span class="text-lg font-semibold text-orange-900">Register User View</span>
                <span class="ml-auto text-orange-400"><i class="fa-solid fa-arrow-right"></i></span>
            </a>
            <a class="block items-center gap-4 rounded-lg bg-white p-6 shadow-lg transition hover:bg-blue-50" href="{{ route("training.admin.move.index") }}">
                <span class="text-2xl text-red-600"><i class="fa-solid fa-exchange-alt"></i></span>
                <span class="text-lg font-semibold text-red-900">Move Training User</span>
                <span class="ml-auto text-red-400"><i class="fa-solid fa-arrow-right"></i></span>
            </a>
            <a class="block items-center gap-4 rounded-lg bg-white p-6 shadow-lg transition hover:bg-blue-50" href="{{ route("training.admin.users.index") }}">
                <span class="text-2xl text-green-600"><i class="fa-solid fa-users"></i></span>
                <span class="text-lg font-semibold text-green-900">User Group Management</span>
                <span class="ml-auto text-green-400"><i class="fa-solid fa-arrow-right"></i></span>
            </a>
            <a class="block items-center gap-4 rounded-lg bg-white p-6 shadow-lg transition hover:bg-blue-50" href="{{ route("training.admin.teams.index") }}">
                <span class="text-2xl text-purple-600"><i class="fa-solid fa-layer-group"></i></span>
                <span class="text-lg font-semibold text-purple-900">Training Management</span>
                <span class="ml-auto text-purple-400"><i class="fa-solid fa-arrow-right"></i></span>
            </a>
            <a class="block items-center gap-4 rounded-lg bg-white p-6 shadow-lg transition hover:bg-blue-50" href="{{ route("training.admin.exports.index") }}">
                <span class="text-2xl text-yellow-600"><i class="fa-solid fa-layer-group"></i></span>
                <span class="text-lg font-semibold text-yellow-900">Exports</span>
                <span class="ml-auto text-yellow-400"><i class="fa-solid fa-arrow-right"></i></span>
            </a>
        </div>
    </div>
@endsection
