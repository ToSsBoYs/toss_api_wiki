$(function () {
    //select初始化
    $(".select2").select2();
    //时间弹出
    $('.datepicker').datepicker({
        "singleDatePicker": true,
        "showDropdowns": true,
        "showISOWeekNumbers": true,
        "timePicker": true,
        "timePicker24Hour": true,
        "linkedCalendars": false,
        "autoUpdateInput": false,
        "startDate": "06/08/2016",
        "endDate": "07/12/2016"

//            format: 'yyyy/mm/dd',
//            autoclose: true,
//            todayHighlight: true,
//            autoclose: true
    });
    //minimal-red radio初始化
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
    });
    //flat-red radio初始化
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });
    //minimal radio初始化
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
});

//全选，全不选
$("#checkAll").click(function () {
    if ($("#checkAll").val() == "全选") {
        $("input[type='checkbox']").iCheck("check");
        $(".icheckbox_minimal-blue").addClass('checked');
        $("#checkAll").val("全不选");
    } else {
        $("input[type='checkbox']").iCheck("uncheck");
        $(".icheckbox_minimal-blue").removeClass('checked');
        $("#checkAll").val("全选");
    };
});

//反选
$("#check").click(function(){
    $(".icheckbox_minimal-blue").each(function(){
        if($(this).attr("aria-checked")=="true"){
            $(this).children("input[type='checkbox']").iCheck("uncheck");
            $(this).removeClass('checked');
        }else{
            $(this).addClass('checked');
            $(this).children("input[type='checkbox']").iCheck("check");
        }
    });
});

$('#search').click(function(){
    document.getElementById('search_form').submit();
});

$(".data").click(function () {
    WdatePicker(
        {
            skin:'twoer',
//                    dateFmt:'yyyy-MM-dd HH:mm:ss'
            dateFmt:'yyyy-MM-dd HH:mm'
        }
    );
});
