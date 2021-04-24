<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Comment;

class CommentsController extends Controller
{
    // コメント登録
    public function store(Request $request, Comment $comment)
    {
        $user = auth()->user();
        $data = $request->all();
        $validator = Validator::make($data, [
            'product_id' => 'required|string',
            'text' => 'required|max:200'
        ]);

        $validator->validate();
        $comment->commentStore($user->id, $data);

        return back();
    }

    // コメント削除
    public function destroy(Comment $comment)
    {
        $user = auth()->user();
        $comment->commentDelete($user->id, $comment->id);

        return back();
    }
}
