<script type="text/javascript">
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