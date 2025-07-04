@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-4xl py-10">
        <div class="mb-6">
            <a class="inline-flex items-center text-gray-600 transition-colors hover:text-gray-800" href="{{ route("training.admin.users.index") }}">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Users
            </a>
        </div>

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-green-800">
                <i class="fa-solid fa-user-plus mr-2"></i>Add New User
            </h1>
        </div>

        <div class="rounded-lg border bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route("training.admin.users.store") }}">
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- User ID -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700" for="user_id">
                            User ID <span class="text-red-500">*</span>
                        </label>
                        <input class="@error("user_id") border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500" id="user_id" type="text" name="user_id" value="{{ old("user_id") }}" placeholder="Enter user ID" required>
                        @error("user_id")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Team Selection -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700" for="team">
                            Team <span class="text-red-500">*</span>
                        </label>
                        <select class="@error("team") border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500" id="team" name="team" required>
                            <option value="">Select a team</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->name }}" {{ old("team") == $team->name ? "selected" : "" }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                        @error("team")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a class="rounded-md bg-gray-100 px-4 py-2 text-gray-700 transition-colors hover:bg-gray-200" href="{{ route("training.admin.users.index") }}">
                        Cancel
                    </a>
                    <button class="rounded-md bg-green-600 px-6 py-2 text-white transition-colors hover:bg-green-700" type="submit">
                        <i class="fa-solid fa-save mr-2"></i>Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
