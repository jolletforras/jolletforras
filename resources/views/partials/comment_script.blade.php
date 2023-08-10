<script>

    function save(){
        var comment = $("#comment").val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        //console.log(response);
        var to_user_id = $("#to_user_id").val();
        var to_comment_id = $("#to_comment_id").val();
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
                to_comment_id: to_comment_id,
                to_user_id: to_user_id,
                comment: comment
            },
            success: function(data) {
                if(data['status']=='success') {
                    $("#to_user_id").val("");
                    $("#to_comment_id").val("");
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

    function answer(comment_id,to_user_id) {
        $("#comment-for-answer").html($("#comment-"+comment_id).html());
        $("#comment-for-answer").show();
        $("#to_user_id").val(to_user_id);
        $("#to_comment_id").val(comment_id);
        $("#comment").attr("placeholder", "Ide írva válaszolhatsz neki");
        $("#cancel").show();
    }

    function cancel() {
        location.reload();
    }
</script>