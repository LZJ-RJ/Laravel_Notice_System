<?php use App\NoticeManagers;?>
<!--TODO : Extend Layout-->

    <div class="flex-center">
        <div class="container">
            <section class="info-box pb-4">
                <nav>
                    <label class="col-12 p-3 m-0">{{trans('admin.notice.noticeSetting')}}</label>
                </nav>
                <hr class="m-0">

                <!-- 列表 -->
                <div class="table-responsive mt-5 px-3">
                    <table class="table">
                        <thead class="thead-primary">
                        <tr>
                            <th>{{trans('admin.notice.eventTrigger')}}</th>
                            <th>{{trans('admin.notice.target')}}</th>
                            <th>{{trans('admin.notice.emailActivate')}}</th>
                            <th>{{trans('admin.notice.smsActivate')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($event_array as $value)
                            <?php
                                //$value[0]是事件的字串
                                //$value[1]是對象的陣列
                                $target_string = '';
                                $notice_event = NoticeManagers::where('event', $value[0])->get()->first();
                            ?>
                            <tr>
                                <td>{{$event_name_to_chinese[$value[0]]}}</td>
                                <td>@foreach($value[1] as $index => $target)
                                        <?php
                                        if(count($value[1]) > $index+1){
                                            $target_string .= $target.',';
                                        }else if(count($value[1]) == $index+1){
                                            $target_string .= $target;
                                        }?>
                                        {{$event_target_to_chinese[$target]}}
                                    @endforeach</td>
                                <td>
                                    <span class="text-{{isset($notice_event->email_activated)&&($notice_event->email_activated)=='Y'?'primary':'secondary'}}">{{isset($notice_event->email_activated)&&($notice_event->email_activated)=='Y'?'啟用':'停用'}}</span>
                                <td>
                                    <span class="text-{{isset($notice_event->sms_activated)&&($notice_event->sms_activated)=='Y' && $value[0]!='register'?'primary':'secondary'}}">{{isset($notice_event->sms_activated)&&($notice_event->sms_activated)=='Y' && $value[0]!='register'?'啟用':'停用'}}</span>
                                <td class="justify-content-between">
                                    <button class="btn btn-xs bg-yellow text-white" data-type="{{$value[0]}}" data-target="{{$target_string}}"
                                    onclick="location.href='{{route('notice.edit',[$value[0], 'type' => 'email', 'target' => $target_string])}}'"
                                    >{{trans('admin.notice.emailEdit')}}</button>
                                    <button class="btn btn-xs btn-primary text-white" data-type="{{$value[0]}}" data-target="{{$target_string}}" onclick="location.href='{{route('notice.edit',[$value[0], 'type' => 'sms' , 'target' => $target_string])}}'"  {{$value[0]=='register'?'disabled':''}}>{{trans('admin.notice.smsEdit')}}</button>
                               </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            <form method="post" action="{{route('notice.update')}}" class="row info-box mb-0">
                @csrf
                <input type="hidden" name="event_name" value="all_user">
                <input type="hidden" name="activated" value="Y">
                <input type="hidden" name="target" value="all">
                <div class="col-12 p-3 m-0 info-box-header d-flex flex-wrap justify-content-between">
                    <label class="m-0 col-12 col-md-6 pl-1" for="content">{{trans('admin.notice.globalSite')}}</label>
                </div>
                <div class="d-flex flex-wrap col-12 info-item my-5">
                    <div class="form-group col-12">
                        <textarea id="content" class="form-control" name="content" rows="5" placeholder="{{trans('admin.notice.description')}}"></textarea>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-xs btn-primary text-white float-right">{{trans('admin.notice.submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>