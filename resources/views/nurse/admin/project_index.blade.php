@extends("layouts.nurse")
@section("content")
    <div class="m-auto w-full p-3 md:w-3/4">
        <div class="flex">
            <div class="flex-1 text-2xl font-bold">Training Management</div>
            <div>
                <a href="{{ route("nurse.admin.create.index") }}">
                    <div class="mb-2 rounded bg-blue-500 p-2 text-white">เพิ่มการฝึกอบรมใหม่</div>
                </a>
            </div>
        </div>
        <hr>
        <table class="w-full">
            <tbody>
                @foreach ($projects as $index => $project)
                    <tr class="cursor-pointer hover:bg-blue-200" onclick="changePage('{{ route("nurse.admin.project.management", $project->id) }}')">
                        <td class="p-1">{{ $index + 1 }}. {{ $project->title }}</td>
                        <td>{{ $project->detail }}</td>
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
