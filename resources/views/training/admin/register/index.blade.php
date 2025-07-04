@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-6xl py-10">
        <h1 class="mb-8 flex items-center gap-2 text-3xl font-bold text-blue-800">
            <i class="fa-solid fa-user-check"></i> Register User View
        </h1>
        <div class="mb-8 rounded-xl bg-white p-6 shadow-lg">
            <div class="mb-4 flex items-center gap-2 border-b pb-2">
                <i class="fa-solid fa-filter text-blue-600"></i>
                <span class="text-lg font-semibold text-blue-800">Filter Registered Users</span>
            </div>
            <form class="grid grid-cols-1 gap-4 md:grid-cols-5" method="GET">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-gray-700" for="team_id">Group</label>
                    <select class="form-select w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" id="team_id" name="team_id" title="Filter by group">
                        <option value="">All Groups</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" {{ request("team_id") == $team->id ? "selected" : "" }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-gray-700" for="teacher_id">Teacher</label>
                    <select class="form-select w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" id="teacher_id" name="teacher_id" title="Filter by teacher">
                        <option value="">All Teachers</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request("teacher_id") == $teacher->id ? "selected" : "" }}>Group : {{ $teacher->team->name }} {{ $teacher->name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="mb-1 block text-sm font-semibold text-gray-700" for="session_id">Session</label>
                    <select class="form-select w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" id="session_id" name="session_id" title="Filter by session">
                        <option value="">All Sessions</option>
                        @foreach ($sessions as $session)
                            <option value="{{ $session->id }}" {{ request("session_id") == $session->id ? "selected" : "" }}>Group : {{ $session->teacher->team->name }} Teacher : {{ $session->teacher->name }} {{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-gray-700" for="time_id">Time</label>
                    <select class="form-select w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" id="time_id" name="time_id" title="Filter by time">
                        <option value="">All Times</option>
                        @foreach ($times as $time)
                            <option value="{{ $time->id }}" {{ request("time_id") == $time->id ? "selected" : "" }}>Group : {{ $time->session->teacher->team->name }} Teacher : {{ $time->session->teacher->name }} Session : {{ $time->session->name }} {{ $time->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="mb-1 block text-sm font-semibold text-gray-700" for="user_id">User ID</label>
                    <input class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" id="user_id" type="text" name="user_id" value="{{ request("user_id") }}" placeholder="Enter User ID" title="Filter by User ID">
                </div>
                <div class="col-span-1 mt-2 flex flex-wrap justify-end gap-2 md:col-span-5">
                    <button class="btn btn-primary flex items-center gap-2 rounded-md px-6 py-2 shadow transition hover:bg-blue-700" type="submit">
                        <i class="fa-solid fa-filter"></i> <span>Filter</span>
                    </button>
                    <a class="btn btn-secondary flex items-center gap-2 rounded-md px-6 py-2 shadow transition hover:bg-gray-300" href="{{ route("training.admin.register.index") }}">
                        <i class="fa-solid fa-rotate-left"></i> <span>Clear</span>
                    </a>
                </div>
            </form>
        </div>
        <div class="mb-4 flex items-center justify-between">
            <span class="text-lg font-semibold text-gray-700">Registered Users: <span class="text-blue-700">{{ $users->count() }}</span></span>
        </div>
        <div class="overflow-x-auto rounded-xl bg-white shadow-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="sticky top-0 z-10 bg-blue-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-blue-800">User ID</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-blue-800">Team</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-blue-800">Teacher</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-blue-800">Session</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-blue-800">Time</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-blue-800">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($users as $user)
                        <tr class="transition hover:bg-blue-50">
                            <td class="px-4 py-3 font-mono text-blue-900">{{ $user->user_id }}</td>
                            <td class="px-4 py-3">{{ optional(optional(optional($user->time)->session)->teacher)->team->name ?? "-" }}</td>
                            <td class="px-4 py-3">{{ optional(optional($user->time)->session)->teacher->name ?? "-" }}</td>
                            <td class="px-4 py-3">{{ optional($user->time)->session->name ?? "-" }}</td>
                            <td class="px-4 py-3">{{ optional($user->time)->name ?? "-" }}</td>
                            <td class="px-4 py-3">
                                @if ($user->time_id)
                                    <button class="btn btn-danger rounded bg-red-500 px-3 py-1 text-xs text-white hover:bg-red-700" onclick="unregisterUser('{{ $user->user_id }}')">
                                        <i class="fa-solid fa-user-xmark mr-1"></i> Unregister
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-6 text-center text-gray-500" colspan="5">
                                <i class="fa-solid fa-circle-exclamation mr-2 text-xl text-gray-400"></i>No registered users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script>
        // Cascading dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const teacherSelect = document.getElementById('teacher_id');
            const sessionSelect = document.getElementById('session_id');
            const timeSelect = document.getElementById('time_id');
            const teamSelect = document.getElementById('team_id');

            // When teacher changes, reset group to "All Groups"
            teacherSelect.addEventListener('change', function() {
                if (this.value !== '') {
                    teamSelect.value = '';
                }
            });

            // When session changes, reset teacher and group to "All"
            sessionSelect.addEventListener('change', function() {
                if (this.value !== '') {
                    teacherSelect.value = '';
                    teamSelect.value = '';
                }
            });

            // When time changes, reset session, teacher, and group to "All"
            timeSelect.addEventListener('change', function() {
                if (this.value !== '') {
                    sessionSelect.value = '';
                    teacherSelect.value = '';
                    teamSelect.value = '';
                }
            });
        });

        function unregisterUser(userId) {
            if (!confirm('Are you sure you want to unregister this user?')) return;
            fetch("{{ route("training.admin.unregister.user") }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        user_id: userId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('User unregistered successfully');
                        location.reload();
                    } else {
                        alert('Failed to unregister user');
                    }
                })
                .catch(() => alert('Failed to unregister user'));
        }
    </script>
@endsection
