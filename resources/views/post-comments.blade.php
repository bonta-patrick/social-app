<x-layout doctitle="{{$post->title}} | Comments">
    @if(auth()->check())
    <div class="container py-md-5 container--narrow">
        <form action="/post/{{$post->id}}/comments/create-comment" method="POST">
          @csrf
          <div class="form-group">
            <label for="post-body" class="text-muted mb-1"><small>Comment as {{auth()->user()->username}}</small></label>
            <textarea name="content" id="post-body" class="body-content form-control" type="text">{{old('content')}}</textarea>
            @error('content')
            <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
            @enderror
          </div>
  
          <button class="btn btn-primary">Create Comment</button>
        </form>
      </div>
      @endif

      <div class="container py-md-5 container--narrow">
        @if($postComments->count() == 0)
        <p>No comments here...</p>
        @endif
        @foreach($postComments as $comment)
        <div class="card mt-4">
            @if($post->user->username == $comment->user->username)
            <h5 class="card-header"><img class="avatar-tiny" src={{$comment->user->avatar}}> {{$comment->user->username}} <span class="badge badge-info">OP</span> <span class="text-muted small"> commented on {{$post->user->username}}'s unit</span>  </h5>
            @endif
            @if($post->user->username != $comment->user->username)
            <h5 class="card-header"><img class="avatar-tiny" src={{$comment->user->avatar}}> {{$comment->user->username}} <span class="text-muted small"> commented on {{$post->user->username}}'s unit</span></h5>
            @endif
            <div class="card-body">
              <p class="card-text">{{$comment->content}}</p>
              <a href="/comment/{{$comment->id}}/replies" class="btn btn-primary">{{$comment->getCommentReplies()->get()->count()}} Replies</a>
            </div>
          </div>
        @endforeach

        <div class="mt-4">
            {{$postComments->links()}}
          </div>
      </div>
</x-layout>