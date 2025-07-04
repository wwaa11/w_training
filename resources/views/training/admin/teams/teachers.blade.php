@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-6xl py-10">
        <div class="mb-6">
            <a class="inline-flex items-center text-gray-600 transition-colors hover:text-gray-800" href="{{ route("training.admin.teams.index") }}">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Teams
            </a>
        </div>

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="mb-2 text-3xl font-bold text-purple-800">
                    <i class="fa-solid fa-users mr-2"></i>Teachers in Group : {{ $team->name }}
                </h1>
                <div class="flex items-center gap-4">
                    <span class="text-lg text-gray-600">Team Status:</span>
                    @if ($team->status === "active")
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
            <a class="inline-flex items-center rounded-md bg-purple-600 px-4 py-2 text-white transition-colors hover:bg-purple-700" href="{{ route("training.admin.teachers.create", ["team_id" => $team->id]) }}">
                <i class="fa-solid fa-plus mr-2"></i>Create New Teacher
            </a>
        </div>

        @if (session("success"))
            <div class="mb-6 rounded-md border border-green-400 bg-green-100 p-4 text-green-700">
                {{ session("success") }}
            </div>
        @endif

        <div class="overflow-hidden rounded-lg border bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Teachers ({{ $teachers->count() }})</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Teacher Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Created At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($teachers as $teacher)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $teacher->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($teacher->status === "active")
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
                                    {{ $teacher->created_at->format("M d, Y H:i") }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a class="inline-flex items-center px-2 py-1 text-xs text-blue-600 transition-colors hover:text-blue-900" href="{{ route("training.admin.teachers.sessions", $teacher->id) }}" title="View Sessions">
                                            <i class="fa-solid fa-calendar mr-1"></i>View Sessions
                                        </a>
                                        <a class="inline-flex items-center px-2 py-1 text-xs text-indigo-600 transition-colors hover:text-indigo-900" href="{{ route("training.admin.teachers.edit", $teacher->id) }}" title="Edit Teacher">
                                            <i class="fa-solid fa-edit mr-1"></i>Edit
                                        </a>
                                        <form class="inline" method="POST" action="{{ route("training.admin.teachers.delete", $teacher->id) }}" onsubmit="return confirm('Are you sure you want to delete this teacher?')">
                                            @csrf
                                            <button class="inline-flex items-center px-2 py-1 text-xs text-red-600 transition-colors hover:text-red-900" type="submit" title="Delete Teacher">
                                                <i class="fa-solid fa-trash mr-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-gray-500" colspan="5">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-user-graduate mb-2 text-4xl text-gray-300"></i>
                                        <p>No teachers found in this team</p>
                                        <a class="mt-2 text-purple-600 hover:text-purple-800" href="{{ route("training.admin.teachers.create", ["team_id" => $team->id]) }}">Create your first teacher</a>
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
