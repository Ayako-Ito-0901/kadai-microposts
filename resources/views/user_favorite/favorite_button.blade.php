
    @if (Auth::user()->is_favorite($micropost->id))
        {!! Form::open(['route' => ['favorites.unfavorite', $micropost->id], 'method' => 'delete']) !!}
            {!! Form::submit('お気に入りを外す', ['class' => "btn btn-light btn-sm mt-1"]) !!}
        {!! Form::close() !!}
    @else
        {!! Form::open(['route' => ['favorites.favorite', $micropost->id]]) !!}
            {!! Form::submit('お気に入り', ['class' => "btn btn-success btn-sm mt-1"]) !!}
        {!! Form::close() !!}
    @endif
