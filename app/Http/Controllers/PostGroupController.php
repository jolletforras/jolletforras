<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Traits\TagTrait;
use App\Http\Requests;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Models\Group;
use App\Models\Usernotice;
use App\Models\GroupTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostGroupController extends Controller
{
    public function __construct() {
		$this->middleware('auth');
	}

    public function get_group_admin_block($post_type, $post_id)
    {
        $classes = ['article'=>'Article', 'commendation'=>'Commendation'];
        $class = "\\App\Models\\".$classes[$post_type];

        $post = $class::findOrFail($post_id);

        //ezekben a csoportokban van az írás
        $post_groups = $post->groups()->where('status','active')->get();
        $post_group_ids =  $post_groups->pluck('id')->toArray();

        //ebben a csoportokban admin
        $qroups_where_admin = Auth()->user()->member_of_groups()->where('status','active')->where('group_user.admin',1);

        //ebben a csoportokban van az írás, ahol admin
        $qroups_where_admin_have_post = $qroups_where_admin->whereIn('groups.id',$post_group_ids)->get();
        $qroup_where_admin_have_post_ids = $qroups_where_admin_have_post->pluck('id')->toArray();

        $delete_groups = $qroups_where_admin_have_post->pluck('name', 'id')->all();

        $group_where_admin_have_post_url = array();
        foreach($qroups_where_admin_have_post as $group) {
            $group_where_admin_have_post_url[] = '<a href="'.url('csoport',$group->id).'/'.$group->slug.'" target="_blank">'.$group->name.'</a>';
        }

        //ebben a csoportokban nincs az írás, ahol admin
        $qroups_where_admin_have_not_post =  Auth()->user()->member_of_groups()->where('status','active')->where('group_user.admin',1)->whereNotIn('groups.id', $qroup_where_admin_have_post_ids)->get();

        $add_groups = $qroups_where_admin_have_not_post->pluck('name', 'id')->all();

        $returnHTML = view('partials.group_admin_block', compact('group_where_admin_have_post_url','delete_groups','add_groups', 'post_type'))->render();

        $response = array(
            'status' => 'success',
            'html' => $returnHTML,
        );
        return \Response::json($response);
    }

    public function delete_post_from_group($post_id, Request $request)
    {
        $group_id = $request->get('group_id');
        $post_type = $request->get('post_type');
        DB::table($post_type.'_group')->where($post_type.'_id',$post_id)->where('group_id',$group_id)->delete();
        return $this->get_group_admin_block($post_type, $post_id);
    }

    public function add_post_to_group($post_id, Request $request)
    {
        $group_id = $request->get('group_id');
        $post_type = $request->get('post_type');
        DB::table($post_type.'_group')->insert([$post_type.'_id' => $post_id, 'group_id' => $group_id, 'created_at' => date('Y-m-d H:i:s')]);

        return $this->get_group_admin_block($post_type, $post_id);
    }
}
