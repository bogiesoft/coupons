function material(){control(),$(".image-text").data("cur_id",$(".image-text").attr("id")),pasteVal(),$(".image-text-btn").click(function(){var t=$(".image-text").size(),a=/\d+/g,e=parseInt(getMaxNum(".image-text"))+1;return t>=8?(alert("你最多只可以加入8篇文章！"),!1):($(".image-text").off(),$('<div class="image-text" id="imageText'+e+'">'+$(".image-text:last").html()+"</div>").insertAfter($(".image-text:last")),$(".imageText-t:last").html("标题").parent().prev().children("img").attr("src",""),$(".image-text:last input").val(""),$(".image-text:last input").each(function(){var t=$(this).attr("name").replace(a,e);$(this).attr("name",t)}),$('.image-text:last input[name="Material[Data'+e+'][Rate]"]').val(e),$(".imageText-num").html(7-t),void control())}),$(document).on("click",".image-text",function(){var t=prev_id.slice(10);$(prev_id).find('input[name="Material[Data'+t+'][Content]"]').val(UE.getEditor("right_content").getContent());var a="#"+$(this).attr("id");prev_id=a;var e=$(this).attr("id").slice(9);$(".image-text").data("cur_id",a),$(this).addClass("image-text-cur").siblings().removeClass("image-text-cur"),pasteVal($(this),e)}),$(document).on("click","a[href=#del]",function(){var t=$("input[name='MaterDelId']"),a=$(this).parents(".image-text").attr("id"),e=a.slice(9),i=t.val();$("#"+a+' input[name="Material[Data'+e+'][id]"]').val()&&(i+=$("#"+a+' input[name="Material[Data'+e+'][id]"]').val()+","),t.val(i),$(this).parents(".image-text").remove(),control()}),$("#mater-add-r input").filter('[name="Title[]"]').on("keyup change paste blur",function(){var t=$(".image-text").data("cur_id"),a=t.slice(10);""==$(this).val()?($(t+' input[name="Material[Data'+a+'][Title]"]').val(""),$(t+" .imageText-t").html("标题")):($(t+' input[name="Material[Data'+a+'][Title]"]').val($(this).val()),$(t+" .imageText-t").html($(this).val())),textLimit($(this),"64")}),$("#mater-add-r input").filter('[name="Author[]"]').on("keyup change paste blur",function(){var t=$(".image-text").data("cur_id"),a=t.slice(10);""==$(this).val()?$(t+' input[name="Material[Data'+a+'][Author]"]').val(""):$(t+' input[name="Material[Data'+a+'][Author]"]').val($(this).val()),textLimit($(this),"8")}),$("#mater-add-r textarea").filter('[name="Abstract[]"]').on("keyup change paste blur",function(){var t=$(".image-text").data("cur_id"),a=t.slice(10);""==$(this).val()?$(t+' input[name="Material[Data'+a+'][Abstract]"]').val(""):$(t+' input[name="Material[Data'+a+'][Abstract]"]').val($(this).val()),textLimit($(this),"120")}),$("#mater-add-r input").filter('[name="Url[]"]').on("keyup paste blur",function(){var t=$(".image-text").data("cur_id"),a=t.slice(10);""==$(this).val()?$(t+' input[name="Material[Data'+a+'][Url]"]').val(""):$(t+' input[name="Material[Data'+a+'][Url]"]').val($(this).val())}),$("input[name='coverText']").click(function(){var t=$(".image-text").data("cur_id"),a=t.slice(10);0==$(this).prop("checked")?$(t+' input[name="Material[Data'+a+'][If_show]"]').val(""):$(t+' input[name="Material[Data'+a+'][If_show]"]').val("1")}),$(document).on("click",".js-mater-up",function(){var t=$(this).parents(".image-text");materMove(t,"up")}),$(document).on("click",".js-mater-down",function(){var t=$(this).parents(".image-text");materMove(t,"down")})}function textLimit(t,a){var e=$(t).val().length;a>=e&&$(t).prev().find("em").html(e)}function getMaxNum(t){var a,e=new Array;$(t).each(function(){e.push($(this).attr("id").slice(9))}),a=e[0];for(var i=1;i<e.length;i++)a<e[i]&&(a=e[i]);return e=[],a}function control(){var t=$(".image-text");t.each(function(){$(this).hover(function(){$(this).find(".control").show()},function(){$(this).find(".control").hide()})})}function pasteVal(t,a){t||(t=$(".image-text:first")),a||(a=$(".image-text:first").attr("id").slice(9)),$("#mater-add-r input").filter('[name="Title[]"]').val(t.find('input[name="Material[Data'+a+'][Title]"]').val()),textLimit($("#mater-add-r input").filter('[name="Title[]"]'),"120"),$("#mater-add-r input").filter('[name="Author[]"]').val()&&($("#mater-add-r input").filter('[name="Author[]"]').val(t.find('input[name="Material[Data'+a+'][Author]"]').val()),textLimit($("#mater-add-r input").filter('[name="Author[]"]'),"120")),$('#mater-add-r textarea[name="Abstract[]"]').val(t.find('input[name="Material[Data'+a+'][Abstract]"]').val()),textLimit($('#mater-add-r textarea[name="Abstract[]"]'),"120"),$("#mater-add-r input").filter('[name="Url[]"]').val()&&$("#mater-add-r input").filter('[name="Url[]"]').val(t.find('input[name="Material[Data'+a+'][Url]"]').val()),UE.getEditor("right_content").setContent(t.find('input[name="Material[Data'+a+'][Content]"]').val()),""==t.find('input[name="Material[Data'+a+'][If_show]"]').val()?$("input[name='coverText']").prop("checked",!1):$("input[name='coverText']").prop("checked",!0)}function materMove(t,a){var t=$(t);"down"==a?t.next().after(t):t.prev().before(t),control()}function menuValFill(t){$(".menu-manage-r input").filter('[name="name[]"]').val(t.children("input").eq(0).val()),$(".menu-manage-r input").filter('[name="menuId"]').val(t.children("input").eq(1).val()),$(".menu-manage-r textarea").filter('[name="textarea[]"]').val(t.children("input").eq(4).val()),$(".menu-manage-r input").filter('[name="url[]"]').val(t.children("input").eq(5).val())}function getString(){var t=childId="",a=$(".js-parent-menu");return a.each(function(){t+=$(this).children("input").eq(1).val()+":",$(this).find(".menu-child-item").each(function(a){t+=$(this).children("input").eq(1).val()+","}),t=t.substring(0,t.length-1),t+=";"}),t}function getMaxSeq(t){var a,e=$(t).siblings(),i=[];1==t.hasClass("js-add-parent-menu")?e.each(function(){i.push($(this).children("input").eq(0).attr("name").slice(5,6))}):e.each(function(){i.push($(this).children("input").eq(0).attr("name").slice(-8,-7))}),a=i[0];for(var n=1;n<i.length;n++)a<i[n]&&(a=i[n]);return i=[],a||(a=-1),a}function delParentMenu(){var t=$("#menu-manage .menu-parent-item").length,a=$(".js-menu-sort-btn");$(".js-add-parent-menu").css("width",33.333*(3-t)+"%"),2>t?a.prop("disabled",!0).addClass("disabled"):a.prop("disabled",!1).removeClass("disabled")}