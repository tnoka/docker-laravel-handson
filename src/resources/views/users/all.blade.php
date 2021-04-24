@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h3 class="col-md-8 mb-3 text-center text-dark">登録者一覧</h3>

        <div class="col-md-8">
            @foreach ($all_users as $user)
                <div class="card">
                    <div class="card-haeder p-3 w-100 d-flex">
                        <a href="{{ url('users/' .$user->id) }}"><img src="{{ asset('https://s3-ap-northeast-1.amazonaws.com/virtualbookshelf/' .$user->profile_image) }}" class="rounded-circle" width="50" height="50"></a>
                        <div class="ml-3 flex-column">
                            <h5><a href="{{ url('users/' .$user->id) }}" class="text-muted font-weight-bold">{{ $user->name }}</a></h5>
                        </div>
                        <div class="d-flex align-items-center justify-content-end flex-grow-1">
                            <form action="{{ url('login') }}"><button type="submit" class="btn btn-secondary"  onclick="return confirm('フォロー機能を使う場合は、ログインしてください');">フォローする</button></form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="my-4 d-flex justify-content-center">
        {{ $all_users->links() }}
    </div>
</div>
@endsection