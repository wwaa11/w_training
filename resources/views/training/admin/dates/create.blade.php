@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-4xl py-10">
        @if ($errors->any())
            <div class="mb-4 rounded bg-red-100 p-4 text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-6">
            <a class="inline-flex items-center text-gray-600 transition-colors hover:text-gray-800" href="{{ route("training.admin.dates.index", $time->id) }}">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Dates
            </a>
        </div>
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-purple-800">
                <i class="fa-solid fa-calendar-days mr-2"></i>Create New Date
            </h1>
            <div class="mt-2 text-sm text-purple-700">
                <span class="font-semibold">Group:</span> {{ $time->session->teacher->team->name }} &nbsp;|&nbsp;
                <span class="font-semibold">Teacher:</span> {{ $time->session->teacher->name }} &nbsp;|&nbsp;
                <span class="font-semibold">Session:</span> {{ $time->session->teacher->name }} &nbsp;|&nbsp;
                <span class="font-semibold">Time:</span> {{ $time->name }}
            </div>
        </div>
        <div class="rounded-lg border bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route("training.admin.dates.store", $time->id) }}">
                @csrf
                <div class="mb-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <input type="hidden" name="time_id" value="{{ $time->id }}">
                    <!-- Date Picker -->
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-medium text-gray-700" for="date-picker">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-500" id="date-picker" type="date" value="{{ date("Y-m-d") }}">
                    </div>
                    <div class="flex items-end md:col-span-1">
                        <button class="w-full rounded-md bg-green-600 px-6 py-2 text-white transition-colors hover:bg-green-700 md:w-auto" id="add-date-btn" type="button">
                            <i class="fa-solid fa-plus mr-2"></i>Add Date
                        </button>
                    </div>
                </div>
                <div class="space-y-4" id="dates-container"></div>
                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a class="rounded-md bg-gray-100 px-4 py-2 text-gray-700 transition-colors hover:bg-gray-200" href="{{ route("training.admin.dates.index", $time->id) }}">Cancel</a>
                    <button class="rounded-md bg-purple-600 px-6 py-2 text-white transition-colors hover:bg-purple-700" type="submit">
                        <i class="fa-solid fa-save mr-2"></i>Create Date(s)
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addDateBtn = document.getElementById('add-date-btn');
            const datePicker = document.getElementById('date-picker');
            const datesContainer = document.getElementById('dates-container');

            addDateBtn.addEventListener('click', function() {
                const dateValue = datePicker.value;
                if (!dateValue) {
                    alert('Please select a date.');
                    return;
                }
                // Prevent duplicate dates
                if (document.getElementById('date-card-' + dateValue)) {
                    alert('This date has already been added.');
                    return;
                }
                // Create card
                const card = document.createElement('div');
                card.className = 'rounded-lg border bg-gray-50 p-4 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between';
                card.id = 'date-card-' + dateValue;
                card.innerHTML = `
                    <div class="font-semibold text-purple-700 mb-2 md:mb-0">${dateValue}</div>
                    <input type="hidden" name="dates[]" value="${dateValue}">
                    <div class="flex-1 md:ml-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location <span class="text-red-500">*</span></label>
                        <input class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-500" type="text" name="locations[]" required>
                    </div>
                    <button type="button" class="ml-4 text-red-500 hover:text-red-700 remove-date-btn"><i class="fa-solid fa-trash"></i></button>
                `;
                // Remove button handler
                card.querySelector('.remove-date-btn').addEventListener('click', function() {
                    card.remove();
                });
                datesContainer.appendChild(card);
                datePicker.value = '';
            });
        });
    </script>
@endsection
