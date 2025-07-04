@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-2xl py-10">
        <div class="mb-6">
            <a class="inline-flex items-center text-gray-600 transition-colors hover:text-gray-800" href="{{ route("training.admin.teams.index") }}">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Groups
            </a>
        </div>

        <div class="rounded-lg border bg-white shadow-sm">
            <div class="border-b border-gray-200 px-8 py-6">
                <h1 class="text-xl font-semibold text-gray-900">Edit Group</h1>
                <p class="mt-1 text-sm text-gray-600">Update Group information</p>
            </div>

            <form class="p-8" method="POST" action="{{ route("training.admin.teams.update", $team->id) }}">
                @csrf

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-gray-700" for="name">Group Name</label>
                    <input class="@error("name") border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-500" id="name" type="text" name="name" value="{{ old("name", $team->name) }}" placeholder="Enter team name" required>
                    @error("name")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-gray-700" for="status">Status</label>
                    <select class="@error("status") border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-500" id="status" name="status" required>
                        <option value="active" {{ old("status", $team->status) === "active" ? "selected" : "" }}>Active</option>
                        <option value="inactive" {{ old("status", $team->status) === "inactive" ? "selected" : "" }}>Inactive</option>
                    </select>
                    @error("status")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-200 pt-6">
                    <a class="rounded-md bg-gray-100 px-4 py-2 text-gray-700 transition-colors hover:bg-gray-200" href="{{ route("training.admin.teams.index") }}">
                        Cancel
                    </a>
                    <button class="rounded-md bg-purple-600 px-6 py-2 text-white transition-colors hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500" type="submit">
                        Update Group
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
