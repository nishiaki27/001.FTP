<script type="text/javascript">//<![CDATA[
$(document).ready(function(){
    $(".printer").click(function(){
        $("body").addClass("print");
        window.print();
        var timeout = setTimeout(function(){
            $("body").removeClass("print");
        }, 1000);
        return false;
    });
});
//]]>
</script>
