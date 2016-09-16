window.onload = function() {
    //$("input[type='checkbox']").each(function(){
    //    var sanji_zi=$(this).attr('sanji_zi');
    //    if(sanji_zi){
    //        $(this).on('ifChecked', function(){
    //            //alert(sanji_zi);
    //            $("input[sanji_fu='"+sanji_zi+"']").iCheck("check");
    //        });
    //    }
    //});
};

$("input[type='checkbox']").on('ifClicked', function(){

    var sanji_fu = $(this).attr('sanji_fu');//三级checkbox父checkbox标识
    var sanji_zi =$(this).attr('sanji_zi');//三级checkbox标识

    //alert(sanji_fu);
    //alert(sanji_zi);

    if(sanji_fu){//判断是否为三级checkbox父类
        //三级checkbox全部选中
        $(this).on('ifChecked', function(){
            $('tr[sanji_tr="'+sanji_fu+'"]').show();
            $('i[zhankai="'+sanji_fu+'"]').attr('class','fa fa-minus');


            $("input[type='checkbox']").each(function(){
                var sanji_zi = $(this).attr('sanji_zi');
                if(sanji_fu == sanji_zi){ //判断是否为当前三级checkbox父类下面的子checkbox
                    $(this).iCheck("check");
                }
            });
        });
        //三级checkbox全部取消选中
        $(this).on('ifUnchecked', function(){
            $('tr[sanji_tr="'+sanji_fu+'"]').show();
            //$('tr[sanji_tr="'+sanji_fu+'"]').hide();
            //$('i[zhankai="'+sanji_fu+'"]').attr('class','fa fa-plus');

            $("input[type='checkbox']").each(function(){
                var sanji_zi = $(this).attr('sanji_zi');
                if(sanji_fu == sanji_zi){//判断是否为当前三级checkbox父类下面的子checkbox
                    $(this).iCheck("uncheck");
                }
            });

        });
    }else if(sanji_zi){//选择的是三级checkbox
        //alert(sanji_zi);
        //$(this).on('ifUnchecked', function(){
        //    $("input[sanji_fu='"+sanji_zi+"']").iCheck("uncheck");
        //});
    }

});

    $('i').click(function(){
        var now_class=$(this).attr('class');
        var add_class='fa fa-plus';
        var zhankai=$(this).attr('zhankai');

        var erji_fu=$(this).attr('erji_fu');

        if(erji_fu){
            if(now_class == add_class){
                $('tr[erji_tr="'+erji_fu+'"]').show();
                $(this).attr('class','fa fa-minus');
            }else{
                $('tr[erji_tr="'+erji_fu+'"]').hide();
                $('tr[erji_tr_shou="'+erji_fu+'"]').hide();
                $(this).attr('class','fa fa-plus');
                $('i[erji_zhankai="'+erji_fu+'"]').attr('class','fa fa-plus');
            }
        }else if(zhankai){
            if(now_class == add_class){
                $('tr[sanji_tr="'+zhankai+'"]').show();
                $(this).attr('class','fa fa-minus');
            }else{
                $('tr[sanji_tr="'+zhankai+'"]').hide();
                $(this).attr('class','fa fa-plus');
            }
        }

    });




