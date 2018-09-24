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
        $query = Comment::with('user')->where('product_id',$data->id);
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
        $comment_id = $data->id;
        if($comment_id>0) {
            $comment = Comment::where('id',$comment_id)->first();
            if($comment->user_id == $user_id) return response(null,400);
            $user_comment_like = UserCommentsLike::where('comment_id',$comment_id)->where('user_id',$user_id)->first();
            switch ($user_comment_like['is_liked']) {
                case 1:
                    if($data->x==-1)
                    {
                        $comment->increment('dislike');
                        $comment->decrement('like');
                        $user_comment_like->update(['is_liked'=>-1]);
                    }
                    else 
                    {
                        $comment->decrement('like');
                        $user_comment_like->delete();
                    }
                    break;
                case -1:
                    if($data->x==1)
                    {
                        $comment->increment('like');
                        $comment->decrement('dislike');
                        $user_comment_like->update(['is_liked'=>1]);
                    }
                    else 
                    {
                        $comment->decrement('dislike');
                        $user_comment_like->delete();
                    }
                    break;
                default:
                    if($data->x==1) 
                    {
                        $comment->increment('like');
                        UserCommentsLike::insert(['user_id' =>$user_id,'comment_id' =>$comment_id,'is_liked' =>1]);
                    }
                    else if($data->x==-1) 
                    {
                        $comment->increment('dislike');
                        UserCommentsLike::insert(['user_id' =>$user_id,'comment_id' =>$comment_id,'is_liked' =>-1]);
                    }
                    break;       
            }
            return Comment::with('user')->where('id',$comment_id)->with(['vote' => function ($query) use ($user_id) {
                $query->where('user_id',$user_id);
            }])->first();
        }
    }
    public function store(Request $data)
    {
        $user_id = Auth::id();
        // $reply = Comment::where('id',$data->cid);//->value('id');
        $prod = Product::find($data->pid);
        if($prod->id&&$data->rating>=0&&$data->rating<=5)
        {
            if($data->rating>0){
                if(Comment::where('user_id',$user_id)->where('product_id',$data->pid)->where('rating','>',0)->value('id')) 
                    return response(null,400);
                $product = Product::where('id',$data->pid)->first();
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
            $comment->reply_id = $data->cid;
            $comment->save();
            $res = $comment->toArray();
            $res['user'] = array('name'=> Auth::user()->name);

            $prod->rating = ($prod->rating * $prod->vote_count + $data->rating) / ($prod->vote_count + 1);
            $prod->vote_count = $prod->vote_count+1;
            $prod->save(); 

            try { 
                event(new \App\Events\SomeEvent($data->pid,$res));
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
