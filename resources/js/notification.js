jQuery(function ($){

    //通知系統-站內通知
    $(document).on('click', '.tag-all-read', function () {
        if(parseInt($('.notice-btn .badge.badge-warning').text())>0){
            let this_element = $(this);
            $.ajax({
                url : location.origin + '/notice_box/update_read',
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'user_id': $(this_element).data('user-id'),
                }, success(msg){
                    if(msg=='read'){
                        location.reload();
                    }
                }, fail(msg){
                }
            });
        }else{
            alert('已經全部閱讀完畢');
        }
    });

    //通知系統-站內通知
    $(document).on('click', 'section#notification .notice-menu-body', function () {
        if($(this).find('.card-header.bg-primary').length){
            let box_id = $(this).data('box-id');
            let this_element = $(this);
            $.ajax({
                url : location.origin + '/notice_box/update_read',
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'box_id': box_id,
                }, success(msg){
                    if(msg=='read'){
                        let this_header = $(this_element).find('.card-header.bg-primary');
                        $(this_header).removeClass('bg-primary');
                        $(this_header).css('background-color', '#C8C8C8');
                        $(this_header).css('cursor', 'auto');
                        $(this_element).css('cursor', 'auto');

                        let notice_number = $('.notice-btn .badge.badge-warning').text();
                        $('.notice-btn .badge.badge-warning').text(parseInt(notice_number)-1);

                        //若數量為零，就把數字通知清掉。
                        if(parseInt($('.notice-btn .badge.badge-warning').text())<=0){
                            $('.notice-btn .badge.badge-warning').remove();
                            $('section#notification .tag-all-read').removeClass('text-primary');
                            $('section#notification .tag-all-read').addClass('text-muted');
                            $('section#notification .tag-all-read').css('cursor', 'auto');
                        }
                    }
                }, fail(msg){
                }
            });
        }
    });


    //全域站內通知切換鈕
    $(document).on('change' , '#notification select[name="notice_type"]', function (e) {
        if(location.search.split('?')[1] !== undefined) {
            if(location.search.split('?')[1].split('=')[0] == 'id'){
                let window_location_href = location.origin + location.pathname + '?' + 'id=' + location.search.split('?')[1].split('=')[1];
                if(window_location_href.includes('notice_type')){
                    window.location = window_location_href + '=' + $(this).val();
                }else{
                    window.location = window_location_href + '&notice_type=' + $(this).val();
                }
            }else{
                //頁面URL有參數的
                let window_location_href = new URL(location.origin + location.pathname + location.search);
                let params = window_location_href.searchParams;
                let new_url = location.origin + location.pathname + '?';
                var has_notice_type = false;
                for (let pair of params.entries()) {
                    new_url += pair[0] + '=';
                    if(pair[0] == 'notice_type'){
                        new_url += $(this).val();
                        has_notice_type = true;
                    }else{
                        new_url += pair[1];
                    }
                    new_url += '&';
                    console.log(`key: ${pair[0]}, value: ${pair[1]}`)
                }
                if(!has_notice_type){
                    new_url += 'notice_type=' + $(this).val();
                }
                window.location = new_url;
            }
        }else{
            window.location = location.origin + location.pathname + '?notice_type=' + $(this).val();
        }
    });

    window.onload = function (){
        //一開始讀取通知數量
        if ($('#notification').data('notice-count') != '' || parseInt($('#notification').data('notice-count')) > 0) {
            let count_num = $('#notification').data('notice-count');
            $('.notice-btn .badge.badge-warning').text(count_num);
        }

        //透過網址判斷站內通知有無訊息，如果有就自動打開。
        if (location.search.split('?')[1]) {
            if (location.search.split('?')[1].includes('notice_type')) {
                $('#content-header .notice-btn').click();
            }
        }
    }

    // 通知系統-前台下拉(鈴鐺)
    $(document).on('click', '.notice-btn', function () {
        $('#notification').slideToggle();
    });

    $('.notice-manager .used-to-return').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        location.href=$(this).data('redirect-url');
    });
});