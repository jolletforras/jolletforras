<?php

namespace App\Http\Controllers\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait TagTrait {

/**
	 * If there are new tags, it will save and their name will change to their ID in the array
	 *
	 * @param  array
	 * @return array
	 */
	private function getTagList(array $tag_list, $tag_class, $group_id=0) {
		for($i=0; $i<count($tag_list); $i++) {
			if (!is_numeric($tag_list[$i])) {
			    $data = [
                    'name' =>$tag_list[$i],
                    'user_id' => Auth::user()->id,
                    'slug' => Str::slug($tag_list[$i])
                ];
                if($tag_class=='App\Models\ForumTag') {
                    $data += ['group_id' => $group_id];
                }
				$tag = $tag_class::create($data);
				//echo $tag_list[$i];
				$tag_list[$i]=(string)$tag->id;
			}
		}
	
		return $tag_list;
	}

}