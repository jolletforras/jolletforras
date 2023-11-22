<script>

    $(document).ready(function(){
        if(navigator.userAgent.match(/Android/i)) {
            $("#eszkoz").html("Android");
        }
        else {
            $("#eszkoz").html("NEM Android");
        }
    });

    function save(){
        var comment = $("#comment").val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        //console.log(response);
        var lev1_comment_id = $("#lev1_comment_id").val();
        var to_comment_id = $("#to_comment_id").val();
        var to_user_id = $("#to_user_id").val();
        var url = $(location).attr('href');
        if(to_comment_id=='') {
            url = url.substr(0,url.indexOf('#'));
            url = url+"#hozzaszol";
        }
        else {
            url = url.replace('#hozzaszol', '#full-'+to_comment_id);
        }

        $.ajax({
            type: "POST",
            url: '{{ url('comment') }}',
            data: {
                _token: CSRF_TOKEN,
                commentable_type: "{{$commentable_type}}",
                commentable_url: "{{$commentable_url}}",
                commentable_id: "{{$commentable_id}}",
                name: "{{$name}}",
                email: "{{$email}}",
                commenter_id: "{{Auth::user()->id}}",
                lev1_comment_id: lev1_comment_id,
                to_comment_id: to_comment_id,
                to_user_id: to_user_id,
                comment: comment
            },
            success: function(data) {
                if(data['status']=='success') {
                    $("#to_user_id").val("");
                    $("#to_comment_id").val("");
                    window.location.href = url;
                    location.reload();
                }
            },
            error: function(error){
                console.log(error.responseText);
            }
        });
        $("#comment").val('');
    }

    $('.more').on('click', function(event) {
        event.preventDefault();
        var comment_id = $(this).closest('.more').data('value');
        $("#shorted-"+comment_id).hide();
        $("#full-"+comment_id).show();
    });

    function answer(lev1_comment_id,comment_id,to_user_id) {
        $("#comment-for-answer").html($("#comment-"+comment_id).html());
        $("#comment-for-answer").show();
        $("#lev1_comment_id").val(lev1_comment_id);
        $("#to_user_id").val(to_user_id);
        $("#to_comment_id").val(comment_id);
        $("#comment").attr("placeholder", "Ide írva válaszolhatsz neki");
        $("#cancel").show();
    }

    function edit(comment_id) {
        $("#update_comment_id").val(comment_id);
        $("#comment").val($("#full-"+comment_id).text());
        $("#update_comment_btn").show();
        $("#add_comment_btn").hide();
    }

    function update(comment_id) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var comment_id = $("#update_comment_id").val();
        var comment = $("#comment").val();
        var url = $(location).attr('href');
        url = url.replace('#hozzaszol', '#full-'+comment_id);

        $.ajax({
            type: "POST",
            url: '{{ url('comment_update') }}',
            data: {
                _token: CSRF_TOKEN,
                comment_id: comment_id,
                comment: comment
            },
            success: function(data) {
                if(data['status']=='success') {
                    $("#comment_id").val("");
                    $("#comment").val("");
                    window.location.href = url;
                    location.reload();
                }
            },
            error: function(error){
                console.log(error.responseText);
            }
        });
    }

    function cancel() {
        location.reload();
    }
</script>