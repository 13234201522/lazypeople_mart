define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order_refund/index' + location.search,
                    add_url: 'order_refund/add',
                    edit_url: 'order_refund/edit',
                    // del_url: 'order_refund/del',
                    multi_url: 'order_refund/multi',
                    table: 'order_refund',
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
                        {field: 'user.nickname', title: __('User.nickname')},
                        {field: 'user.avatar', title: __('User.avatar'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'detail_id', title: __('订单id')},
                        {field: 'order_detail.goods_id', title: __('商品id')},
                        {field: 'goods.name', title: __('商品名称')},
                        {field: 'order_detail.image', title: __('商品图'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'order_detail.specs', title: __('规格')},
                        // {field: 'order_detail.price', title: __('单价')},
                        {field: 'num', title: __('退款数量')},
                        // {field: 'refund_num', title: __('Refund_num')},
                        {field: 'price', title: __('总金额'), operate:'BETWEEN'},
                        {field: 'refund_goods_price', title: __('商品'), operate:'BETWEEN'},
                        {field: 'refund_tax_price', title: __('税费'), operate:'BETWEEN'},
                        {field: 'refund_charge_price', title: __('手续费'), operate:'BETWEEN'},
                        {field: 'refund_reward_price', title: __('打赏'), operate:'BETWEEN'},
                        {field: 'images', title: __('Images'), events: Table.api.events.image, formatter: Table.api.formatter.images},
                        {field: 'reason', title: __('Reason')},
                        {field: 'mark', title: __('Mark')},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},
                        {field: 'pay_status', title: __('Pay_status'), searchList: {"1":__('Pay_status 1'),"2":__('Pay_status 2')}, formatter: Table.api.formatter.status},
                        {field: 'checktime', title: __('Checktime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate,
                            // buttons:[
                            //     {
                            //         id: 'order_detail',
                            //         name: 'order_detail',
                            //         title: __('退款商品'),
                            //         text: __('商品'),
                            //         classname: 'btn btn-xs btn-primary btn-dialog',
                            //         icon: 'fa fa-list',
                            //         extend:'data-area=\'["100%","100%"]\'',
                            //         url: 'order_detail/index?refund_id={ids}',
                            //         callback: function (data) {
                            //             Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                            //         }
                            //     },
                            // ]
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
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