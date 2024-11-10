<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function createNewComment(Post $post, Request $request) {
        $incomingContent = $request->validate([
            'content' => 'required'
        ]);

        $newComment = new Comment();
        $newComment->user_id = auth()->user()->id;
        $newComment->post_id = $post->id;
        $newComment->content = $incomingContent['content'];
        $newComment->save();

        return back()->with('SuccesSTAT', 'Comment added on this post succesfully!');
    }

    public function showCommentsPage(Post $post) {
        $allComments = $post->getPostComments()->latest()->paginate(4);
        return view('post-comments', ['post' => $post, 'postComments' => $allComments]);
    }

    public function search($term) {
        $posts = Post::search($term)->get();
        $posts->load('user:id,username,avatar');
        return $posts;
    }

    public function updatePost(Post $post, Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);

        return back()->with('SuccesSTAT', 'Post was succesfully updated!');
    }

    public function showEditForm(Post $post) {
        return view('edit-post', ['post' => $post]);
    }

    public function delete(Post $post) {
        $post->delete();

        return redirect('/profile/' . auth()->user()->username)->with('SuccesSTAT', 'The post was deleted succesfully!');
    }

    public function viewSinglePost(Post $post) {
        $filteredText = Str::markdown($post->body);
        $post['body'] = $filteredText;

        return view('single-post', ['post' => $post,'postTitle' => $post->title, 'postContent' => $post->body, 'postAuthor' => $post->user->username, 'postDate' => $post->created_at->format('n/j/Y')]);
    }

    public function storeNewPost(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newPost = Post::create($incomingFields);

        return redirect("/post/{$newPost->id}")->with('SuccesSTAT', 'New unit succesfully created.');
    }

    public function showCreateForm() {
        return view('create-post');
    }
}
