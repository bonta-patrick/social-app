<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function createNewReply(Comment $comment, Request $request) {
        $incomingContent = $request->validate([
            'content' => 'required'
        ]);

        $newReply = new Reply();

        $newReply->user_id = auth()->user()->id;
        $newReply->comment_id = $comment->id;
        $newReply->content = $incomingContent['content'];

        $newReply->save();

        return back()->with('SuccesSTAT', 'Reply created succesfully!');

    }

    public function showRepliesPage(Comment $comment) {
        $allReplies = $comment->getCommentReplies()->latest()->paginate(4);
        return view('comment-replies', [
            'replies' => $allReplies,
            'baseComment' => $comment
        ]);
    }
}
