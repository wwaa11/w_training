@extends("layout")
@section("content")
    <div class="m-auto flex">
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="text-2xl font-bold"><a class="text-blue-600" href="{{ env("APP_URL") }}/admin">Admin Management</a> / <a class="text-blue-600" href="{{ env("APP_URL") }}/admin/project/{{ $project->id }}">{{ $project->project_name }}</a> / Setting</div>
            <hr>
            <div class="mt-6 rounded border border-gray-200 bg-gray-50 p-3 shadow">
                <form action="{{ env("APP_URL") }}/admin/update" method="POST">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    @csrf
                    <div class="flex flex-row">
                        <div class="mb-3 flex-1 text-xl">URL ข้อสอบ</div>
                        <div class="cursor-pointer p-3 text-xl text-green-600" onclick="addUrl()"><i class="fa-solid fa-plus"></i> URL ข้อสอบ</div>
                    </div>
                    <div id="url_section">
                        @if ($project->link !== null)
                            @foreach ($project->link->links as $index => $link)
                                <div class="mt-3 flex flex-row gap-3" id="url_{{ $index }}">
                                    <div class="flex-none p-3">หัวข้อ</div>
                                    <input class="flex-1 rounded border p-3" name="link[{{ $index }}][title]" type="text" value="{{ $link["title"] }}">
                                    <div class="flex-none p-3">URL</div>
                                    <input class="flex-1 rounded border p-3" name="link[{{ $index }}][url]" type="text" value="{{ $link["url"] }}">
                                    <div class="cursor-pointer p-3 text-red-400" onclick="removeUrl('#url_{{ $index }}')">Remove</div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button class="mt-3 w-full cursor-pointer rounded p-3 text-center text-xl text-green-600 hover:bg-green-400 hover:text-white" type="submit">บันทึก</button>
                </form>
            </div>

        </div>
    </div>
@endsection
@section("scripts")
    <script>
        var index = 500;

        function addUrl() {
            var indexID = index;

            html = '<div class="mt-3 flex flex-row gap-3" id="url_' + indexID + '">';
            html += '<div class="flex-none p-3">หัวข้อ</div>';
            html += '<input class="flex-1 rounded border p-3" name="link[' + indexID + '][title]" type="text">';
            html += '<div class="flex-none p-3">URL</div>';
            html += '<input class="flex-1 rounded border p-3" name="link[' + indexID + '][url]" type="text">';
            html += '<div class="cursor-pointer p-3 text-red-400" onclick="removeUrl(\'#url_' + indexID + '\')">Remove</div>';

            $('#url_section').append(html);
            index += 1
        }

        function removeUrl(id) {
            $(id).remove();
        }
    </script>
@endsection
