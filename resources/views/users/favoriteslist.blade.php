@if (count($favoriteposts) > 0)
    <ul class="list-unstyled">
        @foreach ($favoriteposts as $favoritepost)
            <li class="media mb-3">
            <img class="mr-2 rounded" src="{{ Gravatar::src($favoritepost->user->email, 50) }}" alt="">
            <div class="media-body">
                <div>
                    {!! link_to_route('users.show', $favoritepost->user->name, ['id' => $favoritepost->user->id]) !!} <span class="text-muted">posted at {{ $favoritepost->created_at }}</span>
                </div>
                <div>
                    <p class="mb-0">{!! nl2br(e($favoritepost->content)) !!}</p>
                </div>
                @include('user_favorite.favorite_button', ['micropost' => $favoritepost])
            </div>
        </li>
        @endforeach
    </ul>
    {{ $favoriteposts->links('pagination::bootstrap-4') }}
@endif