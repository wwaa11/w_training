@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-6xl py-10">
        <div class="mb-6">
            <a class="inline-flex items-center text-gray-600 transition-colors hover:text-gray-800" href="{{ route("training.admin.teams.teachers", $teacher->team_id) }}">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Teachers
            </a>
        </div>

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="mb-2 text-3xl font-bold text-purple-800">
                    <i class="fa-solid fa-calendar mr-2"></i>Sessions for {{ $teacher->name }}
                </h1>
                <div class="mt-2 text-sm text-purple-700">
                    <span class="font-semibold">Group:</span> {{ $teacher->team->name }}
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-lg text-gray-600">Teacher Status:</span>
                    @if ($teacher->status === "active")
                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800">
                            <i class="fa-solid fa-check-circle mr-1"></i>Active
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800">
                            <i class="fa-solid fa-times-circle mr-1"></i>Inactive
                        </span>
                    @endif
                </div>
            </div>
            <a class="inline-flex items-center rounded-md bg-purple-600 px-4 py-2 text-white transition-colors hover:bg-purple-700" href="{{ route("training.admin.sessions.create", ["teacher_id" => $teacher->id]) }}">
                <i class="fa-solid fa-plus mr-2"></i>Create New Session
            </a>
        </div>

        @if (session("success"))
            <div class="mb-6 rounded-md border border-green-400 bg-green-100 p-4 text-green-700">
                {{ session("success") }}
            </div>
        @endif

        <div class="overflow-hidden rounded-lg border bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Training Sessions ({{ $sessions->count() }})</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Session Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Created At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($sessions as $session)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $session->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($session->status === "active")
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                            <i class="fa-solid fa-check-circle mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                            <i class="fa-solid fa-times-circle mr-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $session->created_at->format("M d, Y H:i") }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a class="inline-flex items-center px-2 py-1 text-xs text-indigo-600 transition-colors hover:text-indigo-900" href="{{ route("training.admin.sessions.edit", $session->id) }}" title="Edit Session">
                                            <i class="fa-solid fa-edit mr-1"></i>Edit
                                        </a>
                                        <form class="inline" method="POST" action="{{ route("training.admin.sessions.delete", $session->id) }}" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                            @csrf
                                            <button class="inline-flex items-center px-2 py-1 text-xs text-red-600 transition-colors hover:text-red-900" type="submit" title="Delete Session">
                                                <i class="fa-solid fa-trash mr-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-gray-50 px-0 py-0" colspan="5">
                                    <div class="px-8 py-4">
                                        <div class="mb-2 flex items-center justify-between">
                                            <span class="font-semibold text-gray-700">Times for this session</span>
                                            <a class="inline-flex items-center rounded bg-purple-500 px-3 py-1 text-xs text-white hover:bg-purple-700" href="{{ route("training.admin.times.create", ["session_id" => $session->id]) }}">
                                                <i class="fa-solid fa-plus mr-1"></i>Create Time
                                            </a>
                                        </div>
                                        <table class="min-w-full divide-y divide-gray-200 border">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Title</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Available Seat ( Max Seat )</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($session->times as $time)
                                                    <tr>
                                                        <td class="px-4 py-2 text-sm">{{ $time->name }}</td>
                                                        <td class="px-4 py-2 text-sm">{{ $time->available_seat }} ( {{ $time->max_seat }} ) </td>
                                                        <td class="px-4 py-2 text-sm">
                                                            <div class="flex items-center space-x-2">
                                                                <a class="inline-flex items-center px-2 py-1 text-xs text-blue-600 transition-colors hover:text-blue-900" href="{{ route("training.admin.dates.index", $time->id) }}" title="View Dates">
                                                                    <i class="fa-solid fa-calendar mr-1"></i>View Dates ( {{ count($time->dates) }} )
                                                                </a>
                                                                <a class="inline-flex items-center px-2 py-1 text-xs text-indigo-600 transition-colors hover:text-indigo-900" href="{{ route("training.admin.times.edit", $time->id) }}" title="Edit Time">
                                                                    <i class="fa-solid fa-edit mr-1"></i>Edit
                                                                </a>
                                                                <form class="inline" method="POST" action="{{ route("training.admin.times.delete", $time->id) }}" onsubmit="return confirm('Are you sure you want to delete this time?')">
                                                                    @csrf
                                                                    <button class="inline-flex items-center px-2 py-1 text-xs text-red-600 transition-colors hover:text-red-900" type="submit" title="Delete Time">
                                                                        <i class="fa-solid fa-trash mr-1"></i>Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td class="px-4 py-4 text-center text-gray-400" colspan="4">No times found for this session.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-gray-500" colspan="5">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-calendar mb-2 text-4xl text-gray-300"></i>
                                        <p>No sessions found for this teacher</p>
                                        <a class="mt-2 text-purple-600 hover:text-purple-800" href="{{ route("training.admin.sessions.create", ["teacher_id" => $teacher->id]) }}">Create your first session</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
