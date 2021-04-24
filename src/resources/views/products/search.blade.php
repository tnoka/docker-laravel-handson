@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3 mx-auto">
                <form class="">
                    <div class="form-group text-center px-5">
                        <input type="text" class="form-control py-4 text-center" name="keyword" value="{{ $keyword }}"
                        placeholder="タイトルか著者名を入力">
                        <input type="submit" class="btn btn-lg btn-dark mt-2 px-5" value="検索">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        @if(count($products) > 0)
            <div class="row">
                @foreach($products as $product)
                <div class="col-md-4">
                    <a href="{{ url("products/". $product->id) }}"><img src="{{ asset('https://s3-ap-northeast-1.amazonaws.com/virtualbookshelf/' .$product->product_image) }}" width="300" height="300" class="mt-4 d-block mx-auto img-fluid img-responsive thumbnail aligncenter size-full wp-image-425" style="cursor:pointer" /></a>
                </div>
                @endforeach
            </div>
            @else
            <p>ヒットしませんでした。</p>
            @endif

            <div class="my-4 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
    </div>
@endsection