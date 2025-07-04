@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-4xl py-10">
        <div class="mb-6">
            <a class="inline-flex items-center text-gray-600 transition-colors hover:text-gray-800" href="{{ route("training.admin.teachers.sessions", $time->session->teacher->id) }}">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Sessions
            </a>
        </div>

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-purple-800">
                <i class="fa-solid fa-clock mr-2"></i>Edit Time
            </h1>
            <div class="mt-2 text-sm text-purple-700">
                <span class="font-semibold">Session:</span> {{ $time->session->name }} &nbsp;|&nbsp;
                <span class="font-semibold">Teacher:</span> {{ $time->session->teacher->name }} &nbsp;|&nbsp;
                <span class="font-semibold">Group:</span> {{ $time->session->teacher->team->name }}
            </div>
        </div>

        <div class="rounded-lg border bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route("training.admin.times.update", $time->id) }}">
                @csrf
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    inp
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700" for="name">
                            Time Title <span class="text-red-500">*</span>
                        </label>
                        <input class="@error("name") border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-500" id="name" type="text" name="name" value="{{ old("name", $time->name) }}" placeholder="e.g., Morning Session, Afternoon Session" required>
                        @error("name")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Available Seat -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700" for="available_seat">
                            Available Seats <span class="text-red-500">*</span>
                        </label>
                        <input class="@error("available_seat") border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-500" id="available_seat" type="number" name="available_seat" value="{{ old("available_seat", $time->available_seat) }}" min="0" required>
                        @error("available_seat")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Max Seat -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700" for="max_seat">
                            Max Seats
                        </label>
                        <input class="@error("max_seat") border-red-500 @enderror w-full rounded-md border border-gray-300 bg-gray-300 px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-500" id="max_seat" readonly type="number" name="max_seat" value="{{ old("max_seat", $time->max_seat) }}" min="1" required>
                        @error("max_seat")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a class="rounded-md bg-gray-100 px-4 py-2 text-gray-700 transition-colors hover:bg-gray-200" href="{{ route("training.admin.teachers.sessions", $time->session->teacher->id) }}">Cancel</a>
                    <button class="rounded-md bg-purple-600 px-6 py-2 text-white transition-colors hover:bg-purple-700" type="submit">
                        <i class="fa-solid fa-save mr-2"></i>Update Time
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
