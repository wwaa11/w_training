@php
    $onlyOthers = $onlyOthers ?? false;
@endphp

<div class="space-y-4">
    @foreach ($scheduleView as $d)
        @php
            $timesToShow = collect($d["times"]);
            if ($onlyOthers) {
                $timesToShow = $timesToShow->filter(fn ($t) => ! $t["userRegistered"] && ! $t["hasAttended"]);
            }
        @endphp
        @if ($timesToShow->isEmpty())
            @continue
        @endif
        <div class="hrd-panel overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
                <h3 class="text-base font-semibold text-slate-900">{{ $d["title"] }}</h3>
                <p class="text-xs text-slate-500">{{ $d["formatted"] }}</p>
            </div>
            <div class="space-y-2 p-3 sm:p-4">
                @if (! $onlyOthers && ! empty($d["detail"]))
                    <p class="text-sm text-slate-600">{{ $d["detail"] }}</p>
                @endif
                @if (! $onlyOthers && ! empty($d["location"]))
                    <p class="flex items-center text-sm text-slate-600">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                        {{ $d["location"] }}
                    </p>
                @endif
                @foreach ($timesToShow as $t)
                    @include("hrd.partials.schedule-time-row", [
                        "t" => $t,
                        "d" => $d,
                        "project" => $project,
                        "compact" => $onlyOthers,
                        "showCapacity" => ! $onlyOthers || ! $t["userRegistered"],
                        "showUnregister" => ! $onlyOthers,
                    ])
                @endforeach
            </div>
        </div>
    @endforeach
</div>
