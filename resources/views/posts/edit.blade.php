@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-4 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-4 text-indigo-700">✏️ Edit Post</h2>

    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Caption --}}
        <textarea name="content" rows="3" class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-indigo-300" placeholder="Update caption...">{{ old('content', $post->content) }}</textarea>

        {{-- Show current image or video --}}
        @if ($post->image_path)
            @php
                $ext = pathinfo($post->image_path, PATHINFO_EXTENSION);
            @endphp

            @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                <img src="{{ asset('storage/' . $post->image_path) }}" class="mt-3 rounded-lg shadow max-h-96 w-auto" alt="Current image">
            @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                <video controls class="mt-3 rounded-lg shadow max-h-96 w-auto">
                    <source src="{{ asset('storage/' . $post->image_path) }}" type="video/{{ $ext }}">
                    Your browser does not support the video tag.
                </video>
            @endif
        @endif

        {{-- Upload new media --}}
        <label for="mediaInput" class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded cursor-pointer hover:bg-gray-300 transition mt-4">
            📷🎥 Change Photo/Video
        </label>
        <input type="file" name="image" id="mediaInput" class="hidden" accept="image/*,video/*">

        {{-- Preview new media before upload --}}
        <div class="mt-2" id="mediaPreviewWrapper">
            <img id="imagePreview" class="hidden mt-2 max-w-xs rounded shadow" />
            <video id="videoPreview" class="hidden mt-2 max-w-xs rounded shadow" controls></video>
        </div>

        <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
            Update Post
        </button>
    </form>
</div>

{{-- JS for media preview --}}
<script>
    const mediaInput = document.getElementById('mediaInput');
    const imagePreview = document.getElementById('imagePreview');
    const videoPreview = document.getElementById('videoPreview');

    mediaInput.addEventListener('change', function () {
        const file = this.files[0];

        if (!file) return;

        const reader = new FileReader();
        const fileType = file.type;

        reader.onload = function (e) {
            if (fileType.startsWith('image/')) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                videoPreview.classList.add('hidden');
            } else if (fileType.startsWith('video/')) {
                videoPreview.src = e.target.result;
                videoPreview.classList.remove('hidden');
                imagePreview.classList.add('hidden');
            }
        };

        reader.readAsDataURL(file);
    });
</script>
@endsection
