<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Jobs\VeryLongJob;
use App\Mail\NewCommentMail;
use App\Notifications\NewCommentNotify;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::latest()->paginate(10);
        return view('comment.index', ['comments' => $comments]);
    }

    public function accept(Comment $comment) {
        $article = Article::findOrFail($comment->article_id);
        $users = User::where('id', '!=', auth()->user()->id)->get();
        $comment->accept = true;
        if ($comment->save()) Notification::send($users, new NewCommentNotify($article, $comment->name));
        return redirect()->route('comment.index');
    }

    public function reject(Comment $comment) {
        $comment->accept = false;
        $comment->save();
        return redirect()->route('comment.index');
    }

    public function store(Request $request) {
        
        $request->validate([
            'name'=>'required|min:4',
            'desc'=>'required|max:256'
        ]);

        $comment = new Comment;
        $comment->name = request('name');
        $comment->desc = request('desc');
        $comment->article_id = request('article_id');
        $comment->user_id = Auth::id();
        if ($comment->save()) {
            VeryLongJob::dispatch($comment);
            return redirect()->back()->with('status', 'Add new comment');
        } else {
            return redirect()->back()->with('status', 'Add failed');
        };
    }

    public function edit($id) {
        $comment = Comment::findOrFail($id);
        Gate::authorize('update_comment', $comment);
        return view('comment.update', ['comment'=>$comment]);
    }

    public function update(Request $request, Comment $comment) {
        Gate::authorize('update_comment', $comment);
        $request->validate([
            'name'=>'required|min:4',
            'desc'=>'required|max:256'
        ]);

        $comment->name = request('name');
        $comment->desc = request('desc');
        if ($comment->save()) return redirect()->route('articles.show', $comment->article_id)->with('status', 'Comment update');
        else return redirect()->back()->with('status', 'Update failed');
    }

    public function destroy(Comment $comment) {
        Gate::authorize('update_comment', $comment);
        if ($comment->delete()) {
            return redirect()->route('articles.show', $comment->article_id)->with('status', 'Delete succes');
        }
        else return redirect()->route('articles.show', $comment->article_id)->with('status', 'Delete comment failed');
    }
}
