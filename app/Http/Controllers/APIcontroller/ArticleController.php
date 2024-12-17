<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Events\NewArticleEvent;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $articles = Cache::remember('articles'.$page, 3000, function() {
            return  Article::latest()->paginate(6); 
        });
        $users = User::all();
        // return view("articles.index", ['articles'=>$articles]);
        return response()->json($articles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key'=>'articles*[0-9]'])->get();
        foreach ($keys as $param) {
            Cache::forget($param->key);
        }
        Gate::authorize('create', [self::class]);
        $request->validate([
            'date'=>'date',
            'name'=>'required|min:5|max:100',
            'desc'=>'required|min:5'
        ]);
        $article = new Article;
        $article->date = $request->date;
        $article->name = $request->name;
        $article->desc = $request->desc;
        $article->user_id = 1;
        if ($article->save()) {
            NewArticleEvent::dispatch($article);
            // return redirect('/articles');
            return response()->json(1);

        };
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {   

        if (isset($_GET['notify'])) auth()->user()->notifications->where('id', $_GET['notify'])->first()->markAsRead();
        $result = Cache::rememberForever('comment_article'.$article->id,function ()use($article) {
            $comments = Comment::where('article_id', $article->id)->where('accept', 1)->latest()->get();
            $user = User::findOrFail($article->user_id)->name;
            return [
                'comments'=>$comments,
                'user'=>$user
            ];
        });
        // Log::alert($result);
        // return view('articles.show', ['article'=>$article, 'user'=>$result['user'], 'comments'=>$result['comments']]);
        return response()->json($result);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        // return view('articles.update', ['article'=>$article]);
        return response()->json($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key'=>'articles*[0-9]'])->get();
        foreach ($keys as $param) {
            Cache::forget($param->key);
        }
        Gate::authorize('update', ['article' => $article]);
        $request->validate([
            'date'=>'date',
            'name'=>'required|min:5|max:100',
            'desc'=>'required|min:5'
        ]);
        $article->date = $request->date;
        $article->name = $request->name;
        $article->desc = $request->desc;
        $article->user_id = 1;
        $article->save();
        if ($article->save()) return response()->json(1);
        else return response()->json(0);
        // return response()->json(1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Cache::flush();
        Gate::authorize('delete', ['article' => $article]);
        if ($article->delete()) return response()->json(1);
        else return response()->json($article);
    }
}
