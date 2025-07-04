@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-4xl py-10">
        <div class="mb-6">
            <a class="inline-flex items-center text-gray-600 transition-colors hover:text-gray-800" href="{{ route("training.admin.dates.index", $time->id) }}">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Dates
            </a>
        </div>
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-purple-800">
                <i class="fa-solid fa-calendar-days mr-2"></i>Edit Date for Time: {{ $date->name }}
            </h1>
            <div class="mt-2 text-sm text-purple-700">
                <span class="font-semibold">Group:</span> {{ $time->session->teacher->team->name }} &nbsp;|&nbsp;
                <span class="font-semibold">Teacher:</span> {{ $time->session->teacher->name }} &nbsp;|&nbsp;
                <span class="font-semibold">Session:</span> {{ $time->session->teacher->name }} &nbsp;|&nbsp;
                <span class="font-semibold">Time:</span> {{ $time->name }}
            </div>
        </div>
        <div class="rounded-lg border bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route("training.admin.dates.update", $date->id) }}">
                @csrf
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700" for="name">
                            Date Name <span class="text-red-500">*</span>
                        </label>
                        <input class="@error("name") border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-500" id="name" type="date" name="name" value="{{ old("name", $date->name) }}" required>
                        @error("name")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Location -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700" for="location">
                            Location <span class="text-red-500">*</span>
                        </label>
                        <input class="@error("location") border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-500" id="location" type="text" name="location" value="{{ old("location", $date->location) }}" required>
                        @error("location")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700" for="status">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select class="@error("status") border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-500" id="status" name="status" required>
                            <option value="active" {{ old("status", $date->status) == "active" ? "selected" : "" }}>Active</option>
                            <option value="inactive" {{ old("status", $date->status) == "inactive" ? "selected" : "" }}>Inactive</option>
                        </select>
                        @error("status")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a class="rounded-md bg-gray-100 px-4 py-2 text-gray-700 transition-colors hover:bg-gray-200" href="{{ route("training.admin.dates.index", $time->id) }}">Cancel</a>
                    <button class="rounded-md bg-purple-600 px-6 py-2 text-white transition-colors hover:bg-purple-700" type="submit">
                        <i class="fa-solid fa-save mr-2"></i>Update Date
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
