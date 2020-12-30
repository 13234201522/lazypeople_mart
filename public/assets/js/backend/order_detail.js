define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order_detail/index' + location.search,
                    add_url: 'order_detail/add',
                    refund_url: 'order_detail/refund',
                    // edit_url: 'order_detail/edit',
                    // del_url: 'order_detail/del',
                    multi_url: 'order_detail/multi',
                    table: 'order_detail',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'order_id', title: __('Order_id')},
                        {field: 'goods_id', title: __('Goods_id')},
                        {field: 'goods.name', title: __('Goods.name')},
                        {field: 'specs', title: __('Specs')},
                        {field: 'price', title: __('Price'), operate:'BETWEEN'},
                        {field: 'num', title: __('Num')},
                        {field: 'refund_status', title: __('退款状态'), searchList:
                                {
                                    "0":'未退款',"1":'部分退款',"2":'全部退款'
                                },
                            formatter: Table.api.formatter.normal},
                        {field: 'refund_num', title: __('退款数量')},
                        {field: 'image', title: __('Image'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                        {field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    id: 'refund',
                                    name: 'refund',
                                    title: __('退款'),
                                    text: __('退款'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    // icon: 'fa fa-list',
                                    extend:'data-area=\'["30%","50%"]\'',
                                    url: 'order_detail/refund',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },
                            ],
                            formatter: Table.api.formatter.operate,
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        refund: function () {
            Controller.api.bindevent();
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
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