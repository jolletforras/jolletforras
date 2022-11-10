<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Comment;
use App\Models\Notice;
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

            $commentable_types = ['GroupTheme'=>'csoport téma','Forum'=>'fórum', 'Project'=>'kezdeményezés', 'Event'=>'esemény'];

            $commenter=User::findOrFail($commenter_id);

            $email=$request->get('email');
            $user=User::where('email','=', $email)->first(); //a bejegyzés létrehozója

          //ha én vagyok a kezdeményezés, fórum v. csoport téma létrehozó és kérek értesítést
            if($user->my_post_comment_notice) {
                $data['commentable_type']=$commentable_types[$c_type];
                $data['name']=$request->get('name');
                $data['email']=$email;
                $data['commentable_url'] = $commentable_url;
                $data['commenter_id']=$commenter->id;
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
                //adott forum_id-nál minden usernek beállítódik a legutolsó comment_id és az  email_sent=0-ra
                Notice::where('notifiable_id',$commentable_id)->where('type','Forum')->update(['comment_id'=>$c->id,'email_sent' => 0]);

                //a hozzászólást létrehozó nem kap értesítést
                Notice::findBy($commentable_id,Auth::user()->id,'Forum')->update(['email_sent' => 1]);

                //amennyiben kérek értesítést arra az esetre ha hozzászólnak általam hozzászólt témához (most még nem kapok)
                if($commenter->theme_comment_notice) {
                    $notice = Notice::findBy($commentable_id,$commenter->id,'Forum')->first();
                    //ha nincs fenn, akkor felveszi, de én levelet most nem kapok
                    if(is_null($notice)) {
                        Notice::create(['notifiable_id' => $commentable_id,'user_id' => $commenter->id,'type' => 'Forum','comment_id'=>$c->id,'email_sent' => 1]);
                    }
                }
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

    //ha értesítést kér új hozzászólás esetén adott témánál
    public function ask_comment_notice(Request $request)
    {
        $forum_id = $request->get('forum_id');
        $user_id = Auth::user()->id;
        $ask_notice = $request->get('ask_notice');

        $notice = Notice::findBy($forum_id,$user_id,'Forum')->first();

        //ha értesítést kér adott témánál
        if($ask_notice==1) {
            if($notice) {               //fel van e véve a kommentelő a forum_id-val a  notices-ban
                $notice->update(['ask_notice' => $ask_notice]);
            }
            else {                      //ha nincs fenn, akkor felveszi
                Notice::create(['notifiable_id' => $forum_id,'user_id' =>$user_id,'type' => 'forum','comment_id'=>-1,'email_sent' =>1,'ask_notice' => $ask_notice]);
            }
        }
        else { //ha nem kér, akkor törli az értesítés (akkor se kapok ha korábban hozzászóltam, csak ha újból és arra értesítést kérek)
            $notice->delete();
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
