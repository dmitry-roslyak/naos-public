<?php

 namespace App\Http\Controllers;
//  namespace App;

use Illuminate\Http\Request;
use App\Product;
use App\Comment;
use App\UserCommentsLike;
use App\User;
use Auth;
class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function show(Request $data)
    {
        $user_id = Auth::id();
        $query = Comment::with('user')->where('product_id',$data->product_id);
        if($user_id)
        {
            $query->with(['vote' => function ($query) use ($user_id) {
                $query->where('user_id',$user_id);
            }]);
        }
        return [$query->count(),$query->skip($data->skip)->take(30)->get()];
    }
    public function like(Request $data)
    {
        $user_id = Auth::id();
        if($data['id'] < 1 || !($data['action'] == 'like' || $data['action'] == 'dislike')) return response(null,400);

        $comment = Comment::with('user')->where('id', $data['id'])->with(['vote' => function ($query) use ($user_id) {
            $query->where('user_id',$user_id);
        }])->first();

        if(!empty($comment->vote)) {
            switch($comment->vote->action) {
                case 'like':
                    $comment->decrement('like');
                break;
                case 'dislike':
                    $comment->decrement('dislike');
                break;
            }
            if($data['action'] != $comment->vote->action) {
                $comment->increment($data['action']);
                $comment->vote()->update(['action' => $data['action']]);
            } else $comment->vote()->delete();
        } else {
            $comment->increment($data['action']);
            $comment->vote()->insert(['user_id' => $user_id, 'comment_id' => $data['id'], 'action' => $data['action']]);
        }
        
        return $comment->load(['vote' => function ($query) use ($user_id) {
            $query->where('user_id',$user_id);
        }]);
    }
    public function store(Request $data)
    {
        $user_id = Auth::id();
        $product = Product::find($data->pid);
        if($product->id && $data->rating >=0 && $data->rating<=5) {
            if($data->rating>0) {
                if(Comment::where('user_id',$user_id)->where('product_id',$data->pid)->where('rating','>',0)->value('id')) 
                    return response(null,400);
                $product->rating = ($product->vote_count*$product->rating+$data->rating)/($product->vote_count+1);
                $product->vote_count = $product->vote_count+1;
                $product->save();
            }
            $comment =  new Comment;
            $comment->rating = $data->rating;
            $comment->product_id = $data->pid;
            $comment->message = $data->message;
            $comment->user_id = $user_id;
            $comment->like = 0;
            $comment->dislike = 0;
            $comment->reply_id = 0;
            $comment->save();
            $comment->load('user');
            
            try { 
                event(new \App\Events\SomeEvent($data->pid, $comment->toArray()));
            } catch(Exception $e){

            }
            // $pusher =  new \Pusher\Pusher("69e878ea5991b6099fb6",
            // "ad8e7edc94006620fe83","373368",array(
            //     'cluster' => 'eu',
            //     'encrypted' => true
            // ));
            // $pusher->trigger('public-chat-product'.$data->pid, 'new-message',$comment);
        } else return response(null,400);
    }
}
