@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-4 bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-4 text-indigo-700">📢 Feed</h1>

    {{-- Post Form --}}
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="mb-6">
        @csrf

        <!-- Caption -->
        <textarea name="content" rows="3" class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-indigo-300" placeholder="What's on your mind?"></textarea>

        <!-- Image and video Upload -->
        <label for="mediaInput" class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded cursor-pointer hover:bg-gray-300 transition mt-2">
            📷🎥 Photo/Video
        </label>
        <input type="file" name="media" id="mediaInput" class="hidden" accept="image/*,video/*">
        
        <div class="mt-2" id="mediaPreviewWrapper">
            <img id="imagePreview" class="hidden mt-2 max-w-xs rounded shadow" />
            <video id="videoPreview" class="hidden mt-2 max-w-xs rounded shadow" controls></video>
        </div>
        
        
        <script>
            const mediaInput = document.getElementById('mediaInput');
            const imagePreview = document.getElementById('imagePreview');
            const videoPreview = document.getElementById('videoPreview');
        
            mediaInput.addEventListener('change', function () {
                const file = this.files[0];
        
                if (!file) return;
        
                const reader = new FileReader();
        
                reader.onload = function (e) {
                    const fileType = file.type;
        
                    if (fileType.startsWith('image/')) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        videoPreview.classList.add('hidden');
                    } else if (fileType.startsWith('video/')) {
                        videoPreview.src = e.target.result;
                        videoPreview.classList.remove('hidden');
                        imagePreview.classList.add('hidden');
                    }
                }
        
                reader.readAsDataURL(file);
            });
        </script>
        

        <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
            Post
        </button>
    </form>

    {{-- Image Preview Script --}}
    <script>
        const mediaInput = document.getElementById('mediaInput');
        const imagePreview = document.getElementById('imagePreview');
        const videoPreview = document.getElementById('videoPreview');
    
        mediaInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
    
            const fileType = file.type;
    
            const reader = new FileReader();
            reader.onload = function (e) {
                if (fileType.startsWith('image/')) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    videoPreview.classList.add('hidden');
                } else if (fileType.startsWith('video/')) {
                    videoPreview.src = e.target.result;
                    videoPreview.classList.remove('hidden');
                    videoPreview.load(); // Important for reloading video preview
                    imagePreview.classList.add('hidden');
                }
            };
    
            reader.readAsDataURL(file);
        });
    </script>
    

    {{-- All Posts --}}
    @foreach ($posts as $post)
        <div class="bg-white p-4 rounded-lg shadow-md mb-6">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-bold text-lg text-indigo-700">{{ $post->user->name }}</h3>
                <span class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
            </div>

            @if ($post->content)
                <p class="mb-3 text-gray-800">{{ $post->content }}</p>
            @endif

            @if ($post->image_path)
                @php
                    $ext = pathinfo($post->image_path, PATHINFO_EXTENSION);
                @endphp

            @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                <img src="{{ asset('storage/' . $post->image_path) }}" class="rounded-lg shadow mb-3 max-h-96 w-auto">
            @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                <video controls class="rounded-lg shadow mb-3 max-h-96 w-auto">
                    <source src="{{ asset('storage/' . $post->image_path) }}" type="video/{{ $ext }}">
                    Your browser does not support the video tag.
                </video>
            @endif
@endif


            <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4 text-sm text-gray-600 mt-2">
                {{-- Like Button --}}
                <form action="{{ route('posts.like', $post) }}" method="POST">
                    @csrf
                    <button class="text-indigo-600 hover:underline">
                        👍 {{ $post->likes->count() }} Like{{ $post->likes->count() !== 1 ? 's' : '' }}
                    </button>
                </form>

                {{-- Who liked --}}
                @if ($post->likes->count())
                    <p class="text-xs text-gray-500">
                        Liked by {{ $post->likes->pluck('user.name')->join(', ') }}
                    </p>
                @endif

                {{-- Only show edit/delete to post owner--}}
                @if ($post->user_id === auth()->id())
                    <a href="{{ route('posts.edit', $post) }}" class="hover:text-blue-600 mt-2 sm:mt-0">✏️ Edit</a>

                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete post?');" class="mt-2 sm:mt-0">
                        @csrf
                        @method('DELETE')
                        <button class="hover:text-red-600">🗑️ Delete</button>
                    </form>
                @endif
            </div>

            {{-- Comments --}}
            <div class="mt-4 border-t pt-4">
                <h4 class="font-semibold text-sm text-gray-700 mb-2">💬 Comments ({{ $post->comments->count() }})</h4>

                @foreach ($post->comments as $comment)
                    <div class="mb-2">
                        <span class="font-semibold text-indigo-700">{{ $comment->user->name }}</span>
                        <span class="text-sm text-gray-600">• {{ $comment->created_at->diffForHumans() }}</span>
                        <p class="text-gray-800 ml-2">{{ $comment->body }}</p>
                    </div>
                @endforeach

                <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-2">
                    @csrf
                    <input name="body" class="w-full border p-2 rounded text-sm" placeholder="Write a comment..." required>
                    <button type="submit" class="mt-1 px-3 py-1 bg-indigo-500 text-white rounded text-sm hover:bg-indigo-600">Comment</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
