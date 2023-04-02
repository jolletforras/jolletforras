<script>

    function save(){
        var comment = $("#comment").val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        //console.log(response);
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
                comment: comment
            },
            success: function(data) {
                if(data['status']=='success') {
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
</script>