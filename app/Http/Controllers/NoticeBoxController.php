<?php

namespace App\Http\Controllers;

use App\NoticeBoxes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Matcher\Not;

class NoticeBoxController extends Controller
{

    public function updateRead(Request $request){
        $current_user = Auth::user();
        $now = new \DateTime('now');
        $now->modify('+8 hours');
        if($request->get('user_id')){
            //全部標示為已讀

            $notice_boxes = NoticeBoxes::where('owner', $request->get('user_id'))->where('read_at', 'when')->where('box_type', '!=', 'global_info')->get();
            foreach($notice_boxes as $box){
                $box->update([
                    'read_at' => $now->format('Y/m/d H:i:s')
                ]);
            }

            $all_global_boxes = NoticeBoxes::where('box_type', 'global_info')->get();
            foreach ($all_global_boxes as $single_global_box) {
                if($single_global_box->read_at != 'when'){
                    $tmp_read_at = unserialize($single_global_box->read_at);
                    if(!in_array($current_user->id, $tmp_read_at)){
                        array_push($tmp_read_at, $current_user->id);
                        $single_global_box->update(['read_at'=> serialize($tmp_read_at)]);
                    }
                }else{
                    $tmp_read_at = array();
                    array_push($tmp_read_at, $current_user->id);
                    $single_global_box->update(['read_at'=> serialize($tmp_read_at)]);
                }
            }
        }else if($request->get('box_id')){
            //單個通知
            $single_global_box = NoticeBoxes::find($request->get('box_id'));
            if($single_global_box->box_type == 'global_info'){
                if($single_global_box->read_at == 'when'){
                    $tmp_read_at = array();
                    array_push($tmp_read_at, $current_user->id);
                    $single_global_box->update(['read_at' => serialize($tmp_read_at)]);
                }else{
                    $tmp_read_at = unserialize($single_global_box->read_at);
                    if(!in_array($current_user->id, $tmp_read_at)){
                        array_push($tmp_read_at, $current_user->id);
                        $single_global_box->update(['read_at'=> serialize($tmp_read_at)]);
                    }
                }
            }else{
                $single_global_box->update(['read_at' => $now->format('Y/m/d H:i:s')]);
            }


        }

        return 'read';
    }


    public function index(){}

    public function create(Request $request){}

    public function store(Request $request){}

    public function show(Request $request){}

    public function edit(Request $request){}

    public function update(Request $request){}

    public function destroy(Request $request){}
}
