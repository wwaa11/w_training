@extends("layouts.hr")
@section("content")
    <div class="m-auto flex">
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="text-2xl font-bold">Projects Management</div>
            <hr>
            @foreach ($projects as $project)
                <a href="{{ env("APP_URL") }}/hr/admin/project/{{ $project->id }}">
                    <div class="mt-3 rounded border bg-white">
                        <div class="p-3 py-6 text-xl font-bold">{{ $project->project_name }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
@section("scripts")
@endsection
