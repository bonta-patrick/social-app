<x-layout :doctitle="$post->title">
    <div class="container py-md-5 container--narrow">
        <div class="d-flex justify-content-between">
          <h2>{{$postTitle}}</h2>
          @can('update', $post)
          <span class="pt-2">
            <a href="/post/{{$post->id}}/edit" class="text-primary mr-2" data-toggle="tooltip" data-placement="top" title="Edit Unit"><i class="fas fa-edit"></i></a>
            <form class="delete-post-form d-inline" action="/post/{{$post->id}}" method="POST">
              @csrf
              @method('DELETE')
              <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top" title="Delete Unit"><i class="fas fa-trash"></i></button>
            </form>
          </span>
          @endcan
        </div>
  
        <p class="text-muted small mb-4">
          <a href="/profile/{{$post->user->username}}"><img class="avatar-tiny" src="{{$post->user->avatar}}" /></a>
          Posted by <a href="/profile/{{$post->user->username}}">{{$postAuthor}}</a> on {{$postDate}}
        </p>
  
        <div class="body-content">
          {!! $postContent !!}
        </div>

        <form class="ml-2 d-inline mt-4" action="/post/{{$post->id}}/comments" method="GET">
          @csrf
          <button class="btn btn-primary btn-sm">{{$post->getPostComments()->count()}} Comments</button>
          <!-- <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button> -->
        </form>
      </div>
  
</x-layout>

    
   