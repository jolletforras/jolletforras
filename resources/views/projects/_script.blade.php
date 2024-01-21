<script>
    $('#member_list').select2({
        placeholder: 'Írd be ide a társaid felhasználónevét',
        $members: true
    });

    $(document).ready(function() {
        if($('#local').is(':checked')) {
            $('#local-block').show();
            $("#local-block input").prop('required',true);
            $("#local-block select").prop('required',true);
        }
        else {
            $('#local-block').hide();
            $("#local-block input").prop('required',false);
            $("#local-block select").prop('required',false);
        }

        $('#local').change(function() {

            if($('#local').is(':checked')) {
                $('#local-block').show();
                $("#local-block input").prop('required',true);
                $("#local-block select").prop('required',true);
            }
            else {
                $('#local-block').hide();
                $("#local-block input").prop('required',false);
                $("#local-block select").prop('required',false);

                $("#local-block input").val('');
                $("#local-block select").val('');
            }
        });

        $('#public').change(function() {
            if($('#public').is(':checked')) {
                $('#meta-description-block').show();
            }
            else {
                $('#meta-description-block').hide();
            }
        });

    });
</script>