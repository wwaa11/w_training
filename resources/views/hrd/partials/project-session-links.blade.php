@php
    $now = now();
    $availableLinks = ($project->links ?? collect())
        ->filter(fn ($link) => ! ($link->link_delete ?? false))
        ->filter(function ($link) use ($now) {
            if (! $link->link_limit) {
                return true;
            }

            return (! $link->link_time_start || $now >= $link->link_time_start)
                && (! $link->link_time_end || $now <= $link->link_time_end);
        })
        ->values();
@endphp

@if ($availableLinks->isNotEmpty())
    <div class="mt-3 rounded-2xl border border-blue-200 bg-blue-50/80 p-3 sm:p-4 {{ $class ?? '' }}">
        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-blue-800">
            <i class="fas fa-link mr-1" aria-hidden="true"></i>
            {{ $heading ?? 'ลิงก์สำหรับเซสชัน' }}
        </p>
        <div class="space-y-2">
            @foreach ($availableLinks as $link)
                <a
                    class="flex min-h-[44px] items-center justify-between gap-2 rounded-xl border border-white bg-white px-3 py-2.5 text-sm font-medium text-blue-900 shadow-sm hover:bg-blue-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                    href="{{ $link->link_url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span class="min-w-0 truncate">{{ $link->link_name }}</span>
                    <i class="fas fa-external-link-alt shrink-0 text-blue-600" aria-hidden="true"></i>
                </a>
            @endforeach
        </div>
    </div>
@endif
