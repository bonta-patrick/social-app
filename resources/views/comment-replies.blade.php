<x-layout doctitle="{{$baseComment->user->username}} | Replies">
    <div class="container py-md-5 container--narrow">
    <div class="card mt-4">
        <h5 class="card-header"><img class="avatar-tiny" src={{$baseComment->user->avatar}}> {{$baseComment->user->username}} <span class="text-muted small">'s comment</h5>
        <div class="card-body">
          <p class="card-text">{{$baseComment->content}}</p>
        </div>
      </div>
    </div>
    @if(auth()->check())
    <div class="container py-md-5 container--narrow">
        <form action="/comment/{{$baseComment->id}}/replies/create-reply" method="POST">
          @csrf
          <div class="form-group">
            <label for="post-body" class="text-muted mb-1"><small>Reply to {{$baseComment->user->username}}'s comment as {{auth()->user()->username}}</small></label>
            <textarea name="content" id="post-body" class="body-content form-control" type="text">{{old('content')}}</textarea>
            @error('content')
            <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
            @enderror
          </div>
  
          <button class="btn btn-primary">Create Reply</button>
        </form>
      </div>
      @endif

      <div class="container py-md-5 container--narrow">
        @if($replies->count() == 0)
        <p>No replies here...</p>
        @endif
        @foreach($replies as $reply)
        <div class="card mt-4">
            <h5 class="card-header"><img class="avatar-tiny" src={{$reply->user->avatar}}> {{$reply->user->username}} <span class="text-muted small"> replied to <a href="/profile/{{$baseComment->user->username}}">{{$baseComment->user->username}}</a>'s comment</span></h5>
            <div class="card-body">
              <p class="card-text">{{$reply->content}}</p>
            </div>
          </div>
        @endforeach

        <div class="mt-4">
            {{$replies->links()}}
          </div>
      </div>
</x-layout>