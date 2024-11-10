<x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Followers">
    <div class="list-group">
        @foreach($followers as $follow)
        <a href="/profile/{{$follow->userDoingTheFollowing->username}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{$follow->userDoingTheFollowing->avatar}}" />
            {{$follow->userDoingTheFollowing->username}}
            @if($follow->userDoingTheFollowing->isAdmin)
            <span class="badge badge-info">Waxe</span>
            @endif
        </a>
        @endforeach
    </div>
</x-profile>