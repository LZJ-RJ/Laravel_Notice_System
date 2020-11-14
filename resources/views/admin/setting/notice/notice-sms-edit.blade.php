{{--TODO : Extend Layout--}}

    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

    <div class="notice-manager">
        <div class="container content">
            <form method="post" action="{{route('notice.update')}}">
                @csrf
                <input type="hidden" name="event_name" value="{{$event_name}}">
                <input type="hidden" name="type" value="{{$type}}">
                <input type="hidden" name="target" value="{{$target}}">
                <section class="row info-box mb-0">
                    <div class="col-12 p-3 m-0 info-box-header d-flex flex-wrap justify-content-between">
                        <label class="m-0 col-12 col-md-6 pl-1">{{$type_to_chinese.trans('admin.notice.noticeSetting')}}-{{$event_name_to_chinese.trans('admin.notice.notice')}}</label>
                        <div class="col-12 col-md-6 text-md-right pl-1">
                            <label>{{trans('admin.notice.activate')}}
                                <i class="fas fa-question-circle ml-md-2 tooltip-icon m-0" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{trans('admin.notice.emailEditText01')}}"></i>
                            </label>
                            <input name="sms_activated" value="Y" type="checkbox" data-toggle="toggle" data-on="{{trans('admin.notice.on')}}" data-off="{{trans('admin.notice.off')}}" {{!empty($edit_notice_event)&&($edit_notice_event['sms_activated']=='Y')?'checked':''}}>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap col-12 info-item my-4">
                        <div class="form-group col-12">
                            <label for="sms-content">{{trans('admin.notice.description')}}</label>
                            <textarea id="sms-content" class="form-control" rows="10" name="sms_content">{{!empty($edit_notice_event)?$edit_notice_event['sms_content']:''}}</textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-xs btn-primary text-white float-right">{{trans('admin.notice.save')}}</button>
                            <button class="btn btn-xs bg-yellow text-white float-right mr-4 used-to-return"  data-redirect-url="{{route('notice.list')}}">{{trans('admin.notice.return')}}</button>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>