@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-4xl py-10">
        <a class="mb-3 inline-flex items-center text-gray-600 transition-colors hover:text-gray-800" href="{{ route("training.admin.index") }}">
            <i class="fa-solid fa-arrow-left mr-2"></i>Back to Management
        </a>
        <h1 class="mb-8 flex items-center gap-2 text-3xl font-bold text-green-800">
            <i class="fa-solid fa-users"></i> User Group Management
        </h1>
        <!-- Search Form -->
        <div class="mb-6 rounded-lg border bg-white p-4 shadow-sm">
            <form class="flex flex-wrap items-end gap-4" method="GET" action="{{ route("training.admin.users.index") }}">
                <div class="min-w-64 flex-1">
                    <label class="mb-2 block text-sm font-medium text-gray-700" for="search">
                        Search User ID
                    </label>
                    <input class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500" id="search" type="text" name="search" value="{{ request("search") }}" placeholder="Enter User ID to search...">
                </div>
                <div class="flex gap-2">
                    <button class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-white transition-colors hover:bg-green-700" type="submit">
                        <i class="fa-solid fa-search mr-1"></i> Search
                    </button>
                    @if (request("search"))
                        <a class="inline-flex items-center rounded-md bg-gray-500 px-4 py-2 text-white transition-colors hover:bg-gray-600" href="{{ route("training.admin.users.index") }}">
                            <i class="fa-solid fa-times mr-1"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">All Users</h2>
                @if (request("search"))
                    <p class="mt-1 text-sm text-gray-600">
                        Search results for "{{ request("search") }}": {{ $users->total() }} user(s) found
                    </p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <a class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-blue-700" href="{{ route("training.admin.users.create") }}">
                    <i class="fa-solid fa-user-plus mr-1"></i> Add/Update User
                </a>
                <form class="flex items-center gap-2" id="importForm" enctype="multipart/form-data">
                    <label class="block">
                        <input class="hidden" id="importFile" type="file" name="import_file" accept=".xlsx,.xls,.csv" onchange="importExcel()">
                        <span class="inline-block cursor-pointer rounded-md bg-green-600 px-4 py-2 text-white transition-colors hover:bg-green-700">
                            <i class="fa-solid fa-file-import mr-1"></i> Import Excel
                        </span>
                    </label>
                </form>
            </div>
        </div>
        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="mb-3">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
        <div class="overflow-x-auto rounded-lg border shadow">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">User ID</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Name</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Group</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Created At</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">#</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $user->user_id }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $user->userData ? $user->userData->name : null }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $user->team }}</td>
                            <td class="px-4 py-2 text-xs text-gray-500">{{ $user->created_at }}</td>
                            <td class="px-4 py-2 text-sm">
                                <button class="delete-user-btn inline-flex items-center rounded-md bg-red-600 px-3 py-1 text-white hover:bg-red-700" data-user-id="{{ $user->id }}" data-user-name="{{ $user->userData ? $user->userData->name : $user->user_id }}">
                                    <i class="fa-solid fa-trash mr-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-8 text-center text-gray-400" colspan="6">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function importExcel() {
            const fileInput = document.getElementById('importFile');
            if (!fileInput.files.length) return;
            const formData = new FormData();
            formData.append('import_file', fileInput.files[0]);
            axios.post("{{ route("training.admin.users.import") }}", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(res => {
                if (res.data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Import Complete',
                        text: res.data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Import Failed',
                        text: res.data.message
                    });
                }
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Import Failed',
                    text: err.response?.data?.message || 'An error occurred.'
                });
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-user-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const userName = this.getAttribute('data-user-name');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `Delete user: ${userName}? This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.delete(`{{ url("training/admin/users") }}/${userId}`)
                                .then(res => {
                                    if (res.data.status === 'success') {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Deleted!',
                                            text: res.data.message,
                                            timer: 1500,
                                            showConfirmButton: false
                                        }).then(() => window.location.reload());
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Delete Failed',
                                            text: res.data.message
                                        });
                                    }
                                })
                                .catch(err => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Delete Failed',
                                        text: err.response?.data?.message || 'An error occurred.'
                                    });
                                });
                        }
                    });
                });
            });
        });
    </script>
@endsection
