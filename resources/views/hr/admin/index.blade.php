@extends("layouts.hr")
@section("content")
    <div class="m-auto w-full p-3 md:w-3/4">
        <div class="flex">
            <div class="flex-1 text-2xl font-bold">Projects Management</div>
            {{-- <div>
                <a href="{{ env("APP_URL") }}/hr/admin/create">
                    <div class="mb-2 rounded bg-blue-500 p-2 text-white">เพิ่มโปรเจกต์ใหม่</div>
                </a>
            </div> --}}
        </div>
        <hr>
        <table class="w-full">
            <tbody>
                @foreach ($projects as $index => $project)
                    <tr class="cursor-pointer hover:bg-blue-200" onclick="changePage('{{ env("APP_URL") }}/hr/admin/project/{{ $project->id }}')">
                        <td class="p-1">{{ $index + 1 }}. {{ $project->project_name }}</td>
                        <td>{{ $project->project_detail ?? "" }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section("scripts")
    <script>
        function changePage(link) {
            window.location.href = link;
        }
    </script>
@endsection
