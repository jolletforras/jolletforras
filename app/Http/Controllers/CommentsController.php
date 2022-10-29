<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Comment;
use Mail;
use Illuminate\Support\Facades\DB;
use Auth;

class CommentsController extends Controller
{

    public function comment(Request $request)
    {

        $comment = $request->get('comment');

        if(!empty($comment)) {
            $c_type = $request->get('commentable_type');

            $c_class = $c_type=="GroupTheme" ? "Forum" : $c_type; //GroupTheme esetén Forum

            $commentable_class = "\\App\Models\\".$c_class;
            $commentable_id = $request->get('commentable_id');
            $commentable_url = $request->get('commentable_url');
            $commenter_id = $request->get('commenter_id');

            $commentable = $commentable_class::findOrFail($commentable_id);

            $c = new Comment();
            $comment = htmlspecialchars($comment);
            $url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
            $comment = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $comment);
            $c->body = $comment;
            $c->commenter_id = $commenter_id;
            $commentable->comments()->save($c);
            ++$commentable->counter;
            $commentable->save();

            $commentable_types = ['GroupTheme'=>'csoport téma','Forum'=>'fórum', 'Project'=>'kezdeményezés'];

            $commenter=User::findOrFail($commenter_id);

            $email=$request->get('email');
            $user=User::where('email','=', $email)->first(); //a bejegyzés létrehozója

          //ha én vagyok a kezdeményezés, fórum v. csoport téma létrehozó és kérek értesítést
            if($user->my_post_comment_notice) {
                $data['commentable_type']=$commentable_types[$c_type];
                $data['name']=$request->get('name');
                $data['email']=$email;
                $data['commentable_url'] = $commentable_url;
                $data['commenter_name']=$commenter->name;
                $data['comment']=$comment;

                Mail::send('comments.email', $data, function($message) use ($data)
                {
                    $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
                    $message->subject("értesítés - ".$data['commentable_type']." hozzászólás");
                    $message->to($data['email']);
                });
            }

            if($c_type=="GroupTheme") {

                if($commenter->theme_comment_notice) {
                    $notice = DB::table('forum_user')->where('forum_id',$commentable_id)->where('user_id',$commenter->id)->first();
                    if($notice) {               //fel van e véve a kommentelő a forum_id-val a  forum_user-ban
                        if($notice->new==1) {   //ha igen és a new=1 akkor azt 0-ba állítja
                            DB::table('forum_user')->where('forum_id',$commentable_id)->where('user_id',$commenter->id)->update(['new' => 0]);
                        }
                    }
                    else {                      //ha nincs fenn, akkor felveszi
                        DB::table('forum_user')->insert(['forum_id' => $commentable_id,'user_id' => $commenter->id]);
                    }

                    /*DB::table('forum_user')->upsert( //laravel 8-tól
                        [['forum_id' => $commentable_id,'user_id' => $commenter->id, 'new' => 0]],
                        ['forum_id', 'user_id'],
                        ['new']
                    );*/
                }

                //adott forum_id-nál minden  email_sent=0 lesz és beállítódik a a legutolsó comment_id
                DB::table('forum_user')->where('forum_id',$commentable_id)->update(['comment_id'=>$c->id,'email_sent' => 0,'updated_at' => date("Y-m-d H:i:s", strtotime('now'))]);
            }

            //dd($request->all());
            $response = array(
                'status' => 'success',
                'msg' => $request->get('comment'),
            );
        }
        else {
            $response = array(
                'status' => 'error',
                'msg' => "Empty comment",
            );
        }

        return \Response::json($response);
    }

    //ha értesítést kér új hozzászólás esetén
    public function ask_comment_notice(Request $request)
    {
        $forum_id = $request->get('forum_id');
        $user_id = Auth::user()->id;
        $ask_notice = $request->get('ask_notice');

        $notice = DB::table('forum_user')->where('forum_id',$forum_id)->where('user_id',$user_id)->first();

        if($notice) {               //fel van e véve a kommentelő a forum_id-val a  forum_user-ban
            DB::table('forum_user')->where('forum_id',$forum_id)->where('user_id',$user_id)->update(['ask_notice' => $ask_notice]);
        }
        else {                      //ha nincs fenn, akkor felveszi
            DB::table('forum_user')->insert(['forum_id' => $forum_id,'user_id' =>$user_id,'email_sent' =>1,'ask_notice' => $ask_notice,'updated_at' => date("Y-m-d H:i:s", strtotime('now'))]);
        }

        $response = array('status' => 'success');

        return \Response::json($response);
    }


    public function comment_delete(Request $request)
    {
        $commentable_type = '\\App\\'.$request->get('commentable_type');
        $commentable_id = $request->get('commentable_id');
        $facebook_id = $request->get('facebook_id');

        $commentable = $commentable_type::findOrFail($commentable_id);
        --$commentable->counter;
        $commentable->save();

        $comment = Comment::where('facebook_id', '=', $facebook_id)->first();
        if (count($comment)>0) {
            $msg='Ezt töröltem: '.$comment->body;
            //$comment->update(['deleted' => 1 ]);
            $comment->deleted=1;
            $comment->save();
        }
        else {
            $msg='Nem találtam meg: '.$facebook_id;
        }


        $response = array(
            'status' => 'success',
            'msg' => $msg,
        );
        return \Response::json($response);
    }
}
