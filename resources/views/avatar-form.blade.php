<x-layout doctitle="Manage Avatar">
    <div class="container container--narrow py-md-5">
        <h2>Upload a New Avatar</h2>
        <form action="/manage-avatar" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input type="file" name="avatar" required>
                @error('avatar')
                <p class="alert small alert-danger shadow-sm">{{$message}}</p>
                @enderror
            </div>
            <button class="btn btn-primary">Save Avatar Image</button>
        </form>
    </div>
</x-layout>