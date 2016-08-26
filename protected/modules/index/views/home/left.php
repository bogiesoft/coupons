<script>
    //隐藏左边菜单栏
    $(function(){
        parent.window.$(".left").hide()
        parent.window.$(".backstage").css("border","none");
        parent.window.$(".left").next().css({"border":"none","background":"transparent"});
    });
</script>