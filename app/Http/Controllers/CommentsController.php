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
use Illuminate\Support\Str;

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

            $comment_length = strlen($comment);

            $c = new Comment();
            $comment = htmlspecialchars($comment);
            //$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
            //$comment = preg_replace($url, '<a href="$0" target="_blank">$0</a>', $comment);
            $comment = $this->get_text_with_shorted_url($comment);
            $c->shorted_text = $comment_length>600 ? $this->subtext_keep_link($comment,600) : null;
            $c->body = $comment;
            $c->commenter_id = $commenter_id;
            if(!empty($request->get('to_user_id'))) {
                $c->lev1_comment_id = $request->get('lev1_comment_id');
                $c->to_comment_id = $request->get('to_comment_id');
                $c->to_user_id = $request->get('to_user_id');
            }
            $commentable->comments()->save($c);
            ++$commentable->counter;
            $commentable->save();

            if(empty($request->get('to_user_id'))) {
                $c->lev1_comment_id = $c->id;
                $c->save();
            }

            //ha hozzászólnak a lezárt téma megnyílik
            if( $c_type=="GroupTheme" && !$commentable->active) {
                $commentable->timestamps = false; //hogy az update_at ne módosuljon
                $commentable->update(['active' => 1]);
            }

            $commentable_types = ['GroupTheme'=>'csoport téma','Forum'=>'fórum', 'Project'=>'kezdeményezés', 'Event'=>'esemény', 'Article'=>'írás', 'Commendation'=>'ajánló', 'Creation'=>'alkotás'];

            $commenter=User::findOrFail($commenter_id);

            $email=$request->get('email');
            $user=User::where('email','=', $email)->first(); //a bejegyzés létrehozója

          //ha én vagyok a kezdeményezés, fórum v. csoport téma létrehozó és kérek értesítést
            if($user->my_post_comment_notice) {
                $data['commentable_type']=$commentable_types[$c_type];
                $data['title']=$commentable->title;
                $data['subject_extra'] = $c_type == "GroupTheme" ? "beszélgetés - ".$commentable->group->name." csoport" : $commentable_types[$c_type];
                $data['name']=$request->get('name');
                $data['email']=$email;
                $data['commentable_url'] = $commentable_url."#".$c->id;
                $data['commenter_id']=$commenter->id;
                $data['commenter_name']=$commenter->name;
                $data['comment']=$comment;

                Mail::send('comments.email', $data, function($message) use ($data)
                {
                    $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
                    $message->subject("Új hozzászólás - '".$data['title']."' ".$data['subject_extra']);
                    $message->to($data['email']);
                });
            }

            if($c_type=="GroupTheme" || ($c_type=="Event" && $commentable->group_id>0)) {
                //növeli a csoporthoz tartozó userek new_post számlálóját 1-el, kivétel a hozzászóló; de a többieknél is csak azoknál, akik több mint két hete nem nézték meg a bejegyzést vagy csak ez a hozzászólás amit még nem látott
                User::incNewPost($commentable_id,$c_class,$commenter->id);

                //megnövelem a számlálóm 1-el mindenkinek a hozzászóló kivételével
                Notice::where('notifiable_id',$commentable_id)->where('type',$c_class)->where('user_id','<>',$commenter->id)->increment('new', 1);

                //növeli a csoporthoz tartozó userek új számlálóját 1-el, kivétel a hozzászóló
                //$commentable->group->members()->where('users.id','<>',$commenter_id)->increment('new_post', 1);
            }

            //ez egyelőre csak a csoport beszélgetésnél, mivel csoport eseménynél csak új esemény létrehozásakor küld ki értesítést (amennyiben kért)
            if($c_type=="GroupTheme") {
                //adott forum_id-nál minden usernek beállítódik a legutolsó comment_id
                Notice::where('notifiable_id',$commentable_id)->where('type','Forum')->update(['comment_id'=>$c->id]);

                //adott forum_id-nál minden usernek aki kér értesítést beállítódik az email_sent=0-ra állítja (csak azoknak kellene aki mindenképp vagy saját után kérnek értesítést)
                Notice::where('notifiable_id',$commentable_id)->where('type','Forum')->where('email',1)->update(['email_sent' => 0]);

                //minden user kap új login_code-ot, mivel ez egyedi mindenkinek, ezért egyenként kell megadni
                $notices = Notice::where('notifiable_id',$commentable_id)->where('type','Forum')->get();
                foreach($notices as $n) {
                    $n->update(['login_code' => Str::random(10)]);
                }

                //amennyiben kér értesítést arra az esetre ha hozzászólnak az általa hozzászólt témához => beállítódik a levél küldés, de most még nem kap értesítést
                if($commenter->theme_comment_notice) {
                    Notice::findBy($commentable_id, $commenter->id, 'Forum')->update(['email' => 1, 'email_sent' => 1]);
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

        //ha értesítést kér adott témánál (email_sent = 1, mert akkor épp látott mindent, mikor beállította a kérést)
        $notice->timestamps = false; //hogy az update_at ne módosuljon
        if($ask_notice==1) {
            $notice->update(['email' => 1, 'email_sent' =>1,'ask_notice' => 1]);
        }
        else {
            //ha nem kér, akkor kiveszi az email értesítés (akkor se kap ha korábban hozzászólt, csak ha újból értesítést kér)
            $notice->update(['email' => 0, 'email_sent' =>0,'ask_notice' => 0]);
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

    public function comment_update(Request $request)
    {
        $comment_id = $request->get('comment_id');
        $comment = $request->get('comment');

        if(!empty($comment)) {
            $c = Comment::findOrFail($comment_id);

            $comment_length = strlen($comment);
            $comment = htmlspecialchars($comment);
            //$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
            //$comment = preg_replace($url, '<a href="$0" target="_blank">$0</a>', $comment);
            $comment = $this->get_text_with_shorted_url($comment);
            $c->shorted_text = $comment_length>600 ? $this->subtext_keep_link($comment,600) : null;
            $c->body = $comment;
            $c->save();
        }

        $response = array('status' => 'success');

        return \Response::json($response);
    }


    private function get_text_with_shorted_url($text) {
        $new_text = preg_replace_callback("/([\w]+\:\/\/[\w\-?&;#~=\.\/\@]+[\w\/])/", function ($m) {
            $url = $m[0];
            $parts = parse_url($url);
            $parts["host"] = str_replace("www.", "", $parts["host"]);
            array_shift($parts); // remove the scheme
            $shortURL = implode("", $parts);
            $shortenedURL = substr($shortURL, 0, 40);
            if (strlen($shortURL) > 40) $shortenedURL .= "...";
            return "<a href=\"".$url."\">".$shortenedURL."</a>";
            var_dump($parts);
        }, $text);

        return $new_text;
    }

/*
    public function set_shorted_text() {
        $comments = Comment::where('shorted_text',NULL)->get();
        foreach($comments as $c) {
            if(strlen($c->body)>600) {
                $c->shorted_text = $this->subtext_keep_link($c->body,600);
                $c->save();
            }
        }
    }
*/
    private function subtext_keep_link($text,$length)
    {
        if(mb_substr($text,$length-1,2)=='<a')
            $length--;
        $text = mb_substr($text,0,$length);             //marad ez ha nincs benne <a tag vagy le van zárva a vágás előtt
        $p1 = mb_strrpos($text,"<a");                   //a végéről kezdve megkeresi az első <a-t
        if(is_numeric($p1)) {
            $p2 = mb_strrpos($text,"</a>");             //a végéről kezdve megkeresi az első </a>-t
            if(!is_numeric($p2) || $p2<$p1) {           //ha a link félbe van vágva (nem talál </a>-t vagy a <a előtt van)
                $text = mb_substr($text,0,$p1);       //levágja a link előtt szöveget
            }
        }

        return $text;
    }
}
