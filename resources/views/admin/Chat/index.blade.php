@extends('admin.layout.app')

@section('title', 'index')
@section('css')
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
        integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.js">
    </script>

    <style>
        .favorit-delete {
            background: #343a40;
            border: none;
            position: absolute;
            top: 21px;
            right: 7px;
            outline: none;
        }

        .card {
            height: 500px;
            border-radius: 15px !important;
            background-color: #212529 !important
        }

        .card .card-body p {
            color: #FFF;
        }

        .contacts_body {
            padding: 0.75rem 0 !important;
            overflow-y: auto;
            white-space: nowrap;
        }

        .msg_card_body {
            overflow-y: auto;
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
            border-bottom: 0 !important;
        }

        .card-footer {
            border-radius: 0 0 15px 15px !important;
            border-top: 0 !important;
        }

        .container {
            align-content: center;
        }

        .search {
            border-radius: 15px 0 0 15px !important;
            background-color: rgba(0, 0, 0, 0.3) !important;
            border: 0 !important;
            color: white !important;
        }

        .search:focus {
            box-shadow: none !important;
            outline: 0px !important;
        }

        .type_msg {
            background-color: rgba(0, 0, 0, 0.3) !important;
            border: 0 !important;
            color: white !important;
            overflow-y: auto;
        }

        .type_msg:focus {
            box-shadow: none !important;
            outline: 0px !important;
        }

        .attach_btn {
            border-radius: 15px 0 0 15px !important;
            background-color: rgba(0, 0, 0, 0.3) !important;
            border: 0 !important;
            color: white !important;
            cursor: pointer;
        }

        .send_btn {
            border-radius: 0 15px 15px 0 !important;
            background-color: rgba(0, 0, 0, 0.3) !important;
            border: 0 !important;
            color: white !important;
            cursor: pointer;
        }

        .search_btn {
            border-radius: 0 15px 15px 0 !important;
            background-color: rgba(0, 0, 0, 0.3) !important;
            border: 0 !important;
            color: white !important;
            cursor: pointer;
        }

        .contacts {
            list-style: none;
            padding: 0;
        }

        .contacts li {
            width: 100% !important;
            padding: 5px 10px;
            border-bottom: 2px solid #ffffff15;
        }

        .contacts li:last-child {
            border-bottom: none;
        }

        .active {
            background-color: rgba(0, 0, 0, 0.3);
        }

        .user_img {
            height: 50px;
            width: 50px;
            border: 1.5px solid #f5f6fa;
        }

        .user_img_msg {
            height: 40px;
            width: 40px;
            border: 1.5px solid #f5f6fa;

        }

        .img_cont {
            position: relative;
            height: 70px;
            width: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .img_cont_msg {
            height: 40px;
            width: 40px;
        }

        .online_icon {
            position: absolute;
            height: 10px;
            width: 10px;
            background-color: #4cd137;
            border-radius: 50%;
            bottom: 0.8em;
            right: 0.7em;
            border: 1.5px solid white;
        }

        .offline {
            background-color: #c23616 !important;
        }

        .user_info {
            margin-top: auto;
            margin-bottom: auto;
            margin-left: 15px;
        }

        .user_info>span {
            font-size: 16px;
            color: #FFF;
            text-transform: capitalize;
            font-weight: bold;
        }

        .user_info>p {
            text-transform: capitalize;
            margin-bottom: 0;
        }

        .contacts .bd-highlight {
            background: #343a40;
        }

        .user_info p span {
            font-size: 12px;
            color: white;
        }

        .user_info p {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.6);
        }

        .video_cam {
            margin-left: 50px;
            margin-top: 5px;
        }

        .video_cam span {
            color: white;
            font-size: 20px;
            cursor: pointer;
            margin-right: 20px;
        }

        .msg_cotainer {
            margin-top: auto;
            margin-bottom: auto;
            margin-left: 10px;
            background-color: #82ccdd;
            position: relative;
            padding: 8px 16px;
            border-radius: 4px;
        }

        .msg_cotainer_send {
            background-color: #dcb626;
            padding: 8px 16px;
            position: relative;
            border-radius: 4px;
        }

        .del-btn {
            position: absolute;
            right: 3px;
            top: 3px;
            cursor: pointer;
            font-size: 10px;
            color: #bd0707;
        }

        .msg_time {
            position: absolute;
            left: 0;
            bottom: -15px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 10px;
            width: max-content;
            text-align: left;
        }

        .msg_time_send {
            position: absolute;
            right: 0;
            bottom: -15px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 10px;
            width: max-content;
            text-align: right;
        }

        .msg_head {
            position: relative;
        }

        #action_menu_btn {
            position: absolute;
            right: 10px;
            top: 10px;
            color: white;
            cursor: pointer;
            font-size: 20px;
        }

        .action_menu {
            z-index: 1;
            position: absolute;
            padding: 15px 0;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border-radius: 15px;
            top: 30px;
            right: 15px;
            display: none;
        }

        .action_menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .action_menu ul li {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 5px;
        }

        .action_menu ul li i {
            padding-right: 10px;

        }

        .action_menu ul li:hover {
            cursor: pointer;
            background-color: rgba(0, 0, 0, 0.2);
        }


        @media(max-width: 576px) {
            .contacts_card {
                margin-bottom: 15px !important;
            }
        }
    </style>
@endsection
@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="container-fluid h-100">
                <div class="row justify-content-center h-100">
                    <div class="col-md-4 col-xl-3 chat">
                        <div class="card mb-sm-3 mb-md-0 contacts_card">
                            <div class="card-header">
                                <div class="input-group">
                                    <input type="text" placeholder="Search..." name=""
                                        class="form-control search" data-search>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text search_btn" style="height: 100%"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body contacts_body">
                                <ul class="contacts">
                                    @foreach ($data['chatfavourites'] as $favourites)
                                        <li>
                                            <div id="favorit{{ $favourites['id'] }}" class="position-relative">
                                                <div class="d-flex bd-highlight favourites"
                                                    data-id="{{ $favourites['id'] }}">
                                                    <div class="img_cont">
                                                        <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg"
                                                            class="rounded-circle user_img">
                                                        <span class="online_icon"></span>
                                                    </div>
                                                    <div class="pt-2 user_info">
                                                        <span data-filter-item data-filter-name="{{ strtolower($favourites['User']['name']) }}">{{ $favourites['User']['name'] }}</span>
                                                        <p>{{ $favourites['User']['role'] }}<span
                                                                id="unread{{ $favourites['id'] }}"
                                                                class="badge position-absolute w-auto rounded aaa"
                                                                style="left: 8px; top:3px; background: #dc3545"></span></p>
                                                    </div>
                                                </div>
                                                <button class="favorit-delete" id="{{ $favourites['id'] }}">
                                                    <span class="fa fa-trash text-white small delete"></span>
                                                </button>
                                                <!-- <a href="{{ url('/admin/chat-messages') }}" class="nav-link">
                                                                                                    <i class="fa fa-calendar-plus"></i><span>Booking</span><span class="badge position-absolute w-auto rounded"
                                                                                                    style="right: 16px;background: red">{{ count($data) }}</span></a> -->
                                            </div>
                                        </li>
                                    @endforeach


                                </ul>

                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                    <div class="col-md-8 col-xl-6 chat-section">
                    </div>

                </div>
            </div>
        </section>
    </div>

@endsection
@section('scripts')
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    <script>
        var pusher = new Pusher('0bb7ddc001fee99ef030', {
            cluster: 'ap2',
            encrypted: true
        });
        var channel = pusher.subscribe('chat');
        channel.bind('new-message', function(data) {
            // if(data.message.chatdata.chat_favourites_id == )
            if (data.message.chatdata.sender_type === 'Admin') {
                const date = new Date(data.message.chatdata.created_at).getDate()
                const month = new Date(data.message.chatdata.created_at).getMonth() + 1
                const year = new Date(data.message.chatdata.created_at).getFullYear()
                const hour = new Date(data.message.chatdata.created_at).getHours().toString().padStart(2, '0')
                const minute = new Date(data.message.chatdata.created_at).getMinutes().toString().padStart(2, '0')
                $(`#b${data.message.chatdata.chat_favourites_id}`).append(`<div class="d-flex justify-content-end mb-4"  id="delete-message${data.message.chatdata.id}" data-id = ${data.message.chatdata.id}>
                                    <div class="msg_cotainer_send">
                                        ${data.message.chatdata.body}
                                        <span class="fa fa-trash del-btn messages" message-id=${data.message.chatdata.id}></span>
                                        <span class="msg_time_send">${date+"-"+month+"-"+year+ " "+ hour+":"+minute}</span>
                                    </div>
                                    <div class="img_cont_msg">
                                    </div>
                                </div>`)
            } else {
                const date = new Date(data.message.chatdata.created_at).getDate()
                const month = new Date(data.message.chatdata.created_at).getMonth() + 1
                const year = new Date(data.message.chatdata.created_at).getFullYear()
                const hour = new Date(data.message.chatdata.created_at).getHours().toString().padStart(2, '0')
                const minute = new Date(data.message.chatdata.created_at).getMinutes().toString().padStart(2, '0')
                $(`#b${data.message.chatdata.chat_favourites_id}`).append(`
                                <div class="d-flex justify-content-start mb-4" data-id = ${data.message.chatdata.id}>
                                    <div class="img_cont_msg">
                                        <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg">
                                    </div>
                                    <div class="msg_cotainer">
                                        ${data.message.chatdata.body}
                                        <span class="fa fa-trash del-btn messages" message-id=${data.message.chatdata.id}></span>

                                        <span class="msg_time">${date+"-"+month+"-"+year+ " "+ hour+":"+minute}</span>
                                    </div>
                                </div>`)

            }
            /*get unread messages of all users when new message*/
            $.ajax({
                type: "GET",
                dataType: "json",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                url: '{{ url('admin/unread-message') }}',
                data: {},
                success: function(response) {
                    $.each(response.data, function(index, data) {
                    //   console.log(data.chatId);
                        $('#unread'+data.chatId).empty();
                        $('#unread'+data.chatId).append(data.message);
                    });
                }
            });
        });
        /*get unread messages of all users when reload page*/
        $.ajax({
                type: "GET",
                dataType: "json",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                url: '{{ url('admin/unread-message') }}',
                data: {},
                success: function(response) {
                    $.each(response.data, function(index, data) {
                    //   console.log(data.chatId);
                        $('#unread'+data.chatId).empty();
                        $('#unread'+data.chatId).append(data.message);
                    });
                }
            });
    </script>
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $(document).on('click', '#send_admin_btn', function(e) {
                e.preventDefault();
                // console.log('dsasa');
                let body = $('.type_msg').val();
                $('.type_msg').val('');
                let chat_favourites_id = $('div.message-card').attr('id');
                console.log(chat_favourites_id);
                let chat_user_id = $('div.message-card').data('user_id');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "{{ route('chat.store') }}",
                    data: {
                        'user_id': chat_user_id,
                        'chat_favourites_id': chat_favourites_id,
                        'sender_type': 'Admin',
                        'body': body
                    },
                    success: function(response) {
                        console.log(response, 'ssad');
                    }
                });

                $ ('.msg_card_body').animate({
                    scrollTop: $ (".msg_card_body") .offset().top + $(".msg_card_body")[0].scrollHeight
                }, 2000);
            });

            /*trigger send msg button on clicking enter key*/
            $(document).on('keyup', '.type_msg', function (e) {
                if (e.keyCode == 13) {
                    $('#send_admin_btn').click();
                }
            });

            /*scroll to chat bottom last msg*/
            $('.contacts > li > div').click(function() {
                setTimeout(() => {
                    $(".msg_card_body").scrollTop($(".msg_card_body")[0].scrollHeight);
                }, 500);
            });

            /*chat users search filter*/
            $('[data-search]').on('keyup', function() {
                var searchVal = $(this).val();
                var filterItems = $('[data-filter-item]');
                if ( searchVal != '' ) {
                    filterItems.closest('li').addClass('d-none');
                    $('[data-filter-item][data-filter-name*="' + searchVal.toString().toLowerCase() + '"]').closest('li').removeClass('d-none');
                } else {
                    filterItems.closest('li').removeClass('d-none');
                }
            });

            $('.favourites').click(function() {
                let id = $(this).data('id');
                // alert(id);
                $('.favourites').removeClass('active');
                $(this).addClass('active');
                $('.message-card').remove();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "GET",
                    url: "{{ route('chat.messages') }}",
                    data: {
                        'chatfavourite_id': id
                    },
                    success: function(response) {
                        $('#unread'+id).empty();
                        $('#unread'+id).append(0);


                        //alert(response.chat_favourite.user.id);
                        $('.chat-section').append(` <div class="card message-card" id='${response.chat_favourite.id}' data-user_id='${response.chat_favourite.user.id}' >
                            <div class="card-header msg_head">
                                <div class="d-flex">
                                    <div class="img_cont">
                                        <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
                                        {{-- <span class="online_icon"></span> --}}
                                    </div>
                                    <div class="user_info">
                                        <span>${response.chat_favourite.user.name}(${response.chat_favourite.user.role})</span>
                                    </div>
                                </div>
                                <span id="action_menu_btn"><span class="fa fa-trash allmessages" all-message-id=${response.chat_favourite.id}></span></span>
                                <div class="action_menu">
                                    <ul>
                                        <li><i class="fas fa-user-circle"></i> View profile</li>
                                        <li><i class="fas fa-users"></i> Add to close friends</li>
                                        <li><i class="fas fa-plus"></i> Add to group</li>
                                        <li><i class="fas fa-ban"></i> Block</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body msg_card_body" id='b${response.chat_favourite.id}'>
                            </div>
                            <div class="card-footer">
                                <div class="input-group">
                                    <input type="text" class="form-control type_msg" placeholder="Type your message...">
                                    <div class="input-group-append">
                                        <button class="input-group-text send_admin_btn" id='send_admin_btn'><i class="fas fa-location-arrow send-admin-message"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>`)
                        response.chat_messages.forEach(messages => {
                            const date = new Date(messages.created_at).getDate()
                            const month = new Date(messages.created_at).getMonth() + 1
                            const year = new Date(messages.created_at).getFullYear()
                            const hour = new Date(messages.created_at).getHours()
                                .toString().padStart(2, '0')
                            const minute = new Date(messages.created_at).getMinutes()
                                .toString().padStart(2, '0')

                            if (messages.sender_type === 'Admin') {

                                $('.msg_card_body').append(`<div class="d-flex justify-content-end mb-4"  id="delete-message${messages.id}" data-id = ${messages.id}>

                                    <div class="msg_cotainer_send">
                                        ${messages.body}
                                        <span class="fa fa-trash del-btn messages" message-id= ${messages.id} ></span>
                                        <span class="msg_time_send" class="ml-2 fa fa-trash text-danger small">${date+"-"+month+"-"+year+ " "+ hour+":"+minute}</span>
                                    </div>

                                    <div class="img_cont_msg">
                                    </div>
                                </div>`)
                            } else {
                                $('.msg_card_body').append(`
                                <div class="d-flex justify-content-start mb-4" data-id = ${messages.id} id="delete-message${messages.id}">
                                    <div class="img_cont_msg">
                                        <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg">
                                    </div>
                                    <div class="msg_cotainer">
                                        ${messages.body}
                                        <span class="fa fa-trash del-btn messages" message-id= ${messages.id} ></span>
                                        <span class="msg_time">${date+"-"+month+"-"+year+ " "+ hour+":"+minute}</span>
                                    </div>
                                </div>`)

                            }



                        });

                    }
                });
            });
        });


        $(document).on('click', '.favorit-delete', function() {

            var id = $(this).attr('id');
            // alert(id);
            $.ajax({
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                url: '{{ url('admin/chat-deleted') }}',
                data: {
                    'id': id
                },
                success: function(response) {
                    console.log(response);
                    console.log(response.user.user_id);
                    $(`#favorit${id}`).removeClass('d-flex');
                    $(`#favorit${id}`).addClass('d-none');
                    var a = $(".message-card").attr("data-user_id");
                    if (a = response.user.user_id) {
                        $(".message-card").addClass("d-none");
                    }
                    toastr.success("Chat Deleted Successfully", 'success');



                }
            });
        });

        $(document).on('click', '.messages', function() {
            var id = $(this).attr('message-id');
            $.ajax({
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                url: '{{ url('admin/message-deleted') }}',
                data: {
                    'id': id
                },
                success: function(response) {

                    console.log(response);
                    // $(`${hide-id}`).removeClass('d-flex');
                    // let a = $(`#delete-message${id}`).attr('data-id');
                    // if (a = response.user.id) {
                    $(`#delete-message${id}`).removeClass('d-flex');
                    $(`#delete-message${id}`).addClass('d-none');
                    //}
                    // toastr.success("Chat Deleted Successfully", 'success');



                }
            });
        });
        // All-messages-deleted
        $(document).on('click', '.allmessages', function(event) {
            event.preventDefault();
            var id = $(this).attr('all-message-id');

            swal({
                    title: `Are you sure you want to delete this chat?`,
                    // text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            headers: {
                                'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            url: '{{ url('admin/all-message-deleted') }}',
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                console.log(response);
                                location.reload();
                                toastr.success("All Messages Deleted Successfully", 'success');
                            }

                        });

                    }

                });

        });
    </script>


    <script>
        $(document).ready(function() {
            $('#table_chat').DataTable();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js">
        < /scrip> <
        script type = "text/javascript" >
    </script>
    <script>
        $(document).ready(function() {

        });
    </script>
    <script>
        $(document).ready(function() {
            $('#action_menu_btn').click(function() {
                $('.action_menu').toggle();
            });
        });
    </script>
@endsection
