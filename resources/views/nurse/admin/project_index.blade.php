@extends("layouts.nurse")
@section("content")
    <div class="m-auto w-full p-3 md:w-3/4">
        <div class="text-2xl font-bold">Training Management</div>
        <hr>
        @foreach ($projects as $project)
            <a href="{{ env("APP_URL") }}/nurse/admin/project/{{ $project->id }}">
                <div class="mt-3 rounded border bg-white">
                    <div class="p-3 py-6 text-xl font-bold">{{ $project->title }}</div>
                </div>
            </a>
        @endforeach
    </div>
@endsection
@section("scripts")
@endsection
