@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">プロフィール編集</div>
                    <div class="card-body pb-0">
                        <div class="text-center font-weight-bold text-danger my-3">
                            {{ $errors->first('profile_image') }}
                            {{ $errors->first('name') }}
                            {{ $errors->first('email') }}
                        </div>
                        <form action="{{ url('users/' .$user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group row align-items-center">
                                <label for="profile_image" class="col-md-4 col-form-label text-md-right">プロフィール画像</label>
                                <div class="col-md-6 d-flex align-items-center">
                                    <img src="{{ asset('https://s3-ap-northeast-1.amazonaws.com/virtualbookshelf/' .$user->profile_image) }}" class="mr-2 rounded-circle" width="50" height="50" alt="profile_image">
                                    <input type="file" name="profile_image" autocomplete="profile_image">
                                </div>
                            </div>                             

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">ユーザーネーム</label>
                                <div class="col-md-6">
                                    <input type="text" id="name" class="form-control" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>

                                <div class="col-md-6">
                                    <input type="email" id="email" class="form-control" name="email" value="{{ $user->email }}"  required autocomplete="email">
                                </div>
                            </div>

                            @if(Auth::user()->id === 1)
                                <div class="form-group text-center my-5">
                                    <p class="btn button--inverse btn-lg" onclick="return confirm('テストユーザーは変更できません');">更新する</p>
                                </div>
                            @else
                                <div class="form-group text-center my-5">
                                    <button class="btn button--inverse btn-lg" type="submit">更新する</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
                @if(Auth::user()->id === 1)
                    <form class="mt-5 d-flex">
                        <div class="form-group row mb-0 mx-auto">
                            <button class="btn btn-danger" type="submit" onclick="return confirm('テストユーザーは削除できません');">アカウントを削除する</button>
                        </div>
                    </form>
                @else
                    <form action="{{ url('users/' .$user->id) }}" method="POST" class="mt-5 d-flex">
                        @csrf
                        @method('DELETE')
                        <div class="form-group row mb-0 mx-auto">
                            <button class="btn btn-danger" type="submit" onclick="return confirm('アカウントを削除してもよろしいですか？');">アカウントを削除する</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection