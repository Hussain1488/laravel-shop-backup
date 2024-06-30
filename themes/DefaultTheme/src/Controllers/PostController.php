<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\ParticipantPosts;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::detectLang()->published()->latest()->paginate(12);

        return view('front::posts.index', compact('posts'));
    }

    public function category(Category $category)
    {

        if ($category->type != 'postcat') {
            abort(404);
        }

        $posts = Post::detectLang()->published()->whereIn('category_id', $category->allChildCategories())->latest()->paginate(9);

        return view('front::posts.category', compact('posts', 'category'));
    }

    public function show(Post $post)
    {
        if (!$post->isShowable()) {
            abort(404);
        }

        $comments_count = $post->comments()->where('status', 'accepted')->count();

        $post->load(['comments' => function ($query) {
            $query->whereNull('comment_id')->where('status', 'accepted');
        }]);

        $post->update([
            'view' => $post->view + 1
        ]);

        // dd(request());
        $participants = null;
        if ($post->type == 'match') {
            $participants = $post->participant()->with(['participantPost' => function ($query) {
                $query->accepted();
            }])->get();

            foreach ($participants as $item) {
                foreach ($item->participantPost as $key) {
                    if (Auth()->check()) {
                        $key->like()->where('type', 'like')->where('user_id', Auth::user()->id)->exists() ? $key->like = 'active-like' : $key->like = '';
                        $key->like()->where('type', 'dislike')->where('user_id', Auth::user()->id)->exists() ? $key->dislike = 'active-dislike' : $key->dislike = '';
                    }
                    $key->comments_count = $key->acceptedCommentCount();
                    $comment = $key->comments()->accepted()->first();
                    $key->comment = $comment ? $comment->body : null;
                }
            }
        }

        // dd($participants);


        $latest_posts    = Post::latest()->take(5)->get();
        $most_view_posts = Post::orderBy('view', 'desc')->take(5)->get();

        return view('front::posts.show', compact('post', 'comments_count', 'most_view_posts', 'latest_posts', 'participants'));
    }

    public function comments(Post $post, Request $request)
    {
        $this->validate($request, [
            'body'       => 'required|string|max:1000',
            'comment_id' => [
                'nullable',
                Rule::exists('comments', 'id')->where(function ($query) {
                    $query->where('comment_id', null);
                }),
            ],
        ]);

        $comment = $post->comments()->create([
            'body'       => $request->body,
            'comment_id' => $request->comment_id,
            'user_id'    => auth()->user()->id
        ]);

        if (auth()->user()->isAdmin()) {
            $comment->update([
                'status' => 'accepted'
            ]);
        }
    }
}
