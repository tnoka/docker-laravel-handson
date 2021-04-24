@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3 class="col-md-8 mb-3 text-center text-muted">編集</h3>
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">
                        <div class="text-center font-weight-bold text-danger my-3">
                            {{ $errors->first('title') }}
                            {{ $errors->first('author') }}
                            {{ $errors->first('recommend') }}
                            {{ $errors->first('text') }}
                        </div>
                        <form action="{{ route('products.update',['products' => $products]) }}" method="POST" > 
                            @csrf
                            @method('PUT')

                            <div class="form-froup row mb-0">
                                <div class="col-md-12 p-3 w-100 d-flex">
                                    <a href="{{ url('users/' .$user->id) }}"><img src="{{ asset('https://s3-ap-northeast-1.amazonaws.com/virtualbookshelf/' .$user->profile_image) }}" class="rounded-circle" width="50" height="50"></a>
                                    <div class="ml-3 d-flex flex-column">
                                        <h5><a href="{{ url('users/' .$user->id) }}" class="text-dark font-weight-bold">ユーザー名 : {{ $user->name }}</a></h5>
                                        <p class="mb-0 text-secondary">ユーザーID : {{ $user->id }}</p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="title" class="col-md-4 col-form-label text-md-right">タイトル</label>
                                        <div class="col-md-6">
                                            <input type="text" id="title" class="form-control" name="title" value="{{ old('title') ? : $products->title }}" required autocomplete="title" autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="author" class="col-md-4 col-form-label text-md-right">著者</label>
                                        <div class="col-md-6">
                                            <input type="text" id="author" class="form-control" name="author" value="{{ old('author') ? : $products->author }}" required autocomplete="author" autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="recommend" class="col-md-4 col-form-label text-md-right">おすすめ度</label>
                                            <div class="col-md-6">
                                                <select name="recommend" class="form-control mb-3" value="{{ old('recommend') ? : $products->recommend }}" required autocomplete="recommend" autofocus>
                                                    <option value="-" @if($products->recommend =="-") selected @endif>選択してください</option>
                                                    <option value="★★★★★" @if($products->recommend =="★★★★★") selected @endif>★★★★★</option>
                                                    <option value="★★★★" @if($products->recommend =="★★★★") selected @endif>★★★★</option>
                                                    <option value="★★★" @if($products->recommend =="★★★") selected @endif>★★★</option>
                                                    <option value="★★" @if($products->recommend =="★★") selected @endif>★★</option>
                                                    <option value="★" @if($products->recommend =="★") selected @endif>★</option>
                                                </select>
                                            </div>
                                    <textarea name="text" class="form-control mb-3" autocomplete="text" rows="10">{{ old('text') ? : $products->text }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-0 mx-auto">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn button--inverse btn-lg">更 新</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
