define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'seckill_goods/index' + location.search,
                    add_url: "seckill_goods/add?seckill_id="+Config.seckill_id,
                    edit_url: 'seckill_goods/edit',
                    del_url: 'seckill_goods/del',
                    multi_url: 'seckill_goods/multi',
                    table: 'seckill_goods',
                }
            });

            var table = $("#table");

            //给添加按钮添加`data-area`属性
            $(".btn-add").data("area", ["100%", "100%"]);
            //当内容渲染完成给编辑按钮添加`data-area`属性
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                $(".btn-editone").data("area", ["100%", "100%"]);
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'seckill_id', title: __('Seckill_id')},
                        {field: 'goods_id', title: __('Goods_id')},
                        {field: 'goods.name', title: __('Goods.name')},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            $(document).on("fa.event.appendfieldlist", "#add-form .btn-append", function (e, obj) {
                // Form.events.plupload(obj);
            });

            $('#c-goods_id').change(function () {
                Fast.api.ajax({
                    url: 'seckill_goods/goods_specs',
                    data : {
                        goods_id : $('#c-goods_id').val(),
                    }
                },function (data, ret) {
                    //清掉之前的
                    $("#basictpl").empty();
                    $("#seckill_specs").empty();
                    $(".dynamic_del").remove();

                    var seckill_specs_arr = JSON.parse(data.seckill_specs);
                    var text_str = "";
                    seckill_specs_arr.map((item, index)=>{
                        text_str += "<dd class=\"form-inline options dynamic_del\"  style=\"display: flex;justify-content: flex-start;\">\n" +
                            "                    <div id=\"ins\" style=\"width: 90%;display: flex;justify-content: space-between;\">\n" +
                            "                        <ins >\n" +
                            "                            <input type=\"text\" data-rule=\"required\" name=\"<%=name%>["+ index +"][specs]\" class=\"form-control\" placeholder=\"规格\" value=\""+ item.specs +"\" readonly>\n" +
                            "                        </ins>\n" +
                            "                        <ins>\n" +
                            "                            <input type=\"number\" data-rule=\"required\" name=\"<%=name%>["+ index +"][price]\" class=\"form-control\" placeholder=\"原价\" value=\""+ item.price +"\" readonly>\n" +
                            "                        </ins>\n" +
                            "                        <ins>\n" +
                            "                            <input type=\"number\" data-rule=\"required\" name=\"<%=name%>["+ index +"][stock]\" class=\"form-control\"  placeholder=\"剩余库存\" value=\""+ item.stock +"\" readonly>\n" +
                            "                        </ins>\n" +
                            "                        <ins>\n" +
                            "                            <input type=\"number\" data-rule=\"required\" name=\"<%=name%>["+ index +"][seckill_price]\" class=\"form-control\" placeholder=\"秒杀价\" value=\""+ item.seckill_price +"\">\n" +
                            "                        </ins>\n" +
                            "                    </div>\n" +
                            "                    <!--下面的两个按钮务必保留-->\n" +
                            "                    <div>\n" +
                            "                    </div>\n" +
                            "                </dd>";
                    });

                    $("#basictpl").text(text_str);
                    $("#fast_dd").after(text_str);
                    $("#seckill_specs").text(data.seckill_specs);

                },function (data, ret) {
                    console.log(data);
                    console.log(ret);

                });
            });


            Controller.api.bindevent();
        },
        edit: function () {
            $(document).on("fa.event.appendfieldlist", "#add-form .btn-append", function (e, obj) {
                // Form.events.plupload(obj);
            });

            $('#c-goods_id').change(function () {
                Fast.api.ajax({
                    url: 'seckill_goods/goods_specs',
                    data : {
                        goods_id : $('#c-goods_id').val(),
                    }
                },function (data, ret) {
                    //清掉之前的
                    $("#basictpl").empty();
                    $("#seckill_specs").empty();
                    $(".dynamic_del").remove();

                    var seckill_specs_arr = JSON.parse(data.seckill_specs);
                    var text_str = "";
                    seckill_specs_arr.map((item, index)=>{
                        text_str += "<dd class=\"form-inline options dynamic_del\"  style=\"display: flex;justify-content: flex-start;\">\n" +
                            "                    <div id=\"ins\" style=\"width: 90%;display: flex;justify-content: space-between;\">\n" +
                            "                        <ins >\n" +
                            "                            <input type=\"text\" data-rule=\"required\" name=\"<%=name%>["+ index +"][specs]\" class=\"form-control\" placeholder=\"规格\" value=\""+ item.specs +"\" readonly>\n" +
                            "                        </ins>\n" +
                            "                        <ins>\n" +
                            "                            <input type=\"number\" data-rule=\"required\" name=\"<%=name%>["+ index +"][price]\" class=\"form-control\" placeholder=\"原价\" value=\""+ item.price +"\" readonly>\n" +
                            "                        </ins>\n" +
                            "                        <ins>\n" +
                            "                            <input type=\"number\" data-rule=\"required\" name=\"<%=name%>["+ index +"][stock]\" class=\"form-control\"  placeholder=\"剩余库存\" value=\""+ item.stock +"\" readonly>\n" +
                            "                        </ins>\n" +
                            "                        <ins>\n" +
                            "                            <input type=\"number\" data-rule=\"required\" name=\"<%=name%>["+ index +"][seckill_price]\" class=\"form-control\" placeholder=\"秒杀价\" value=\""+ item.seckill_price +"\">\n" +
                            "                        </ins>\n" +
                            "                    </div>\n" +
                            "                    <!--下面的两个按钮务必保留-->\n" +
                            "                    <div>\n" +
                            "                    </div>\n" +
                            "                </dd>";
                    });

                    $("#basictpl").text(text_str);
                    $("#fast_dd").after(text_str);
                    $("#seckill_specs").text(data.seckill_specs);

                },function (data, ret) {
                    console.log(data);
                    console.log(ret);

                });
            });
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});