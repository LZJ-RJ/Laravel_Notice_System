<?php
//    TODO : 增加分頁、增加角色的套件
use App\NoticeBoxes;
use Illuminate\Support\Facades\Auth;
$notice_type = 'all';
$current_user = Auth::user();
$owner_array = array();
$count_notice = 0;
if($current_user){
    array_push($owner_array, $current_user->id);
    if( $current_user->hasRole('administrator') ){
        array_push($owner_array, 'admin');
    }
}

if(isset($_GET['notice_type'])){
    if($_GET['notice_type'] == 'all'){
        $notice_box = NoticeBoxes::orderBy('created_at', 'DESC')->whereIn('owner', $owner_array)->paginate(10, ['*'], 'notification');
        $count_notice += count(NoticeBoxes::whereIn('owner', $owner_array)->where('read_at', 'when')->get());
    }else{
        $notice_box = NoticeBoxes::orderBy('created_at', 'DESC')->where('box_type', $_GET['notice_type'])->whereIn('owner', $owner_array)->paginate(10, ['*'], 'notification');
        $count_notice += count(NoticeBoxes::where('box_type', $_GET['notice_type'])->whereIn('owner', $owner_array)->where('read_at', 'when')->get());
    }
    $notice_type = $_GET['notice_type'];
}else{
    $notice_box = NoticeBoxes::orderBy('created_at', 'DESC')->whereIn('owner', $owner_array)->paginate(10, ['*'], 'notification');
    $count_notice += count(NoticeBoxes::whereIn('owner', $owner_array)->where('read_at', 'when')->get());
}

// TODO 新增事件處
$notice_box_type_to_chinese = array(
    'global_info' => '全站通知',
    'member_info' => '會員通知',
);
?>

<section id="notification" data-notice-count="{{$count_notice}}">
    <div class="notice-menu">
        <div class="form-group col-12 p-0">
            <label>通知類型</label>
            <select class="form-control form-control-sm" name="notice_type">
                <option value="all" {{$notice_type=='all'?'selected':''}}>所有通知</option>
                <option value="global_info"  {{$notice_type=='global_info'?'selected':''}}>全站通知</option>
                <option value="member_info"  {{$notice_type=='member_info'?'selected':''}}>會員通知</option>
            </select>
        </div>

        @if(!empty($notice_box->toArray()['data']))
            <a class="mt-6 text-{{$count_notice==0?'muted':'primary'}} tag-all-read" data-user-id="{{isset($current_user)?$current_user->id:''}}" href="javascript:void(0)" {{$count_notice==0?'style=cursor:auto;':''}}>全部標示為已讀</a>
        @else
            <label>目前無訊息</label>
        @endif

        @if(isset($notice_box))
            @foreach($notice_box as $single_notice)
                <div class="notice-menu-body mt-4" data-box-id="{{$single_notice->id}}"<?php
                    if($single_notice->read_at =='when'){
                        echo 'style=cursor:pointer;';
                    }
                    ?>>
                    <div class="card">
                        <?php
                            echo '<div class="card-header ';
                            if($single_notice->read_at!='when'){
                                echo '"';
                                echo 'style="background-color:#C8C8C8;">';
                            }else{
                                echo 'bg-primary">';
                            }
                        ?>
                            <?php echo $notice_box_type_to_chinese[$single_notice->box_type]; ?>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?php echo($single_notice->box_content);?></p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    </div>
</section>
