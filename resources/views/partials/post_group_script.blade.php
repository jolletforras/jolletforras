<script type="text/javascript">

    function get_group_admin_block() {

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $('#btn_group_admin_block').hide();
        $('#group_admin_block').show();

        $.ajax({
            type: "POST",
            url: '{{ url('getPostGroupAdminBlock') }}/{{$post_type}}/{{$post_id}}',
            data: {
                _token: CSRF_TOKEN
            },
            success: function(data) {
                if(data['status']=='success') {
                    $("#group_admin_block").html(data.html);
                }
            },
            error: function(error){
                console.log(error.responseText);
            }
        });
    }

    function delete_post_from_group() {

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var group_id = $('#delete_from_group').val();

        $.ajax({
            type: "POST",
            url: '{{ url('deletePostFromGroup') }}/{{$post_id}}',
            data: {
                _token: CSRF_TOKEN,
                group_id: group_id,
                post_type: '{{$post_type}}'
            },
            success: function(data) {
                if(data['status']=='success') {
                    $("#group_admin_block").html(data.html);
                }
            },
            error: function(error){
                console.log(error.responseText);
            }
        });
    }

    function add_post_to_group() {

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var group_id = $('#add_to_group').val();

        $.ajax({
            type: "POST",
            url: '{{ url('addPostToGroup') }}/{{$post_id}}',
            data: {
                _token: CSRF_TOKEN,
                group_id: group_id,
                post_type: '{{$post_type}}'
            },
            success: function(data) {
                if(data['status']=='success') {
                    $("#group_admin_block").html(data.html);
                }
            },
            error: function(error){
                console.log(error.responseText);
            }
        });
    }
</script>