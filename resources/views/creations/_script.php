<script type="text/javascript">
    $(document).ready(function() {
        if($("#url").is(":checked")) {
            $("#image_block").hide();
        }
        else {
            $("#url_block").hide();
        }
    });


    function source_change(source){
        if(source=="image") {
            $("#url_block").hide();
            $("#image_block").show();
        }
        if(source=="url") {
            $("#url_block").show();
            $("#image_block").hide();
        }
    }
</script>