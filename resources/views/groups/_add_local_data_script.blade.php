            <script>
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
                });
            </script>