define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/index' + location.search,
                    // add_url: 'order/add',
                    // edit_url: 'order/edit',
                    // del_url: 'order/del',
                    multi_url: 'order/multi',
                    table: 'order',
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
                        {field: 'user_id', title: __('User_id')},
                        {field: 'order_num', title: __('Order_num')},
                        // {field: 'address_id', title: __('Address_id')},
                        // {field: 'user_coupon_id', title: __('User_coupon_id')},
                        // {field: 'order_notes', title: __('Order_notes')},
                        {field: 'total_goods_price', title: __('Total_goods_price'), operate:'BETWEEN'},
                        {field: 'postage_price', title: __('Postage_price'), operate:'BETWEEN'},
                        {field: 'fullcut_price', title: __('Fullcut_price'), operate:'BETWEEN'},
                        {field: 'coupon_price', title: __('Coupon_price'), operate:'BETWEEN'},
                        {field: 'reward_price', title: __('Reward_price'), operate:'BETWEEN'},
                        {field: 'tax_price', title: __('Tax_price'), operate:'BETWEEN'},
                        {field: 'pay_price', title: __('Pay_price'), operate:'BETWEEN'},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3'),"4":__('Status 4'),"5":'退款订单'}, formatter: Table.api.formatter.status},
                        {field: 'pay_status', title: __('Pay_status'), searchList: {"0":__('Pay_status 0'),"1":__('Pay_status 1')}, formatter: Table.api.formatter.status},
                        {field: 'pay_state', title: __('Pay_state'), searchList:
                                {
                                    "wechat":'微信',"alipay":'支付宝',"card":'信用卡'
                                },
                            formatter: Table.api.formatter.normal},
                        {field: 'refund_status', title: __('退款状态'), searchList:
                                {
                                    "0":'未退款',"1":'部分退款',"2":'全部退款'
                                },
                            formatter: Table.api.formatter.normal},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'paytime', title: __('Paytime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'finishtime', title: __('Finishtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'sendtime', title: __('Sendtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    id: 'remarks',
                                    name: 'detail',
                                    title: __('备注'),
                                    text: __('备注'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    // icon: 'fa fa-list',
                                    extend:'data-area=\'["50%","50%"]\'',
                                    url: 'order/remarks',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },
                                {
                                    id: 'detail',
                                    name: 'detail',
                                    title: __('订单详情'),
                                    text: __('详情'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    // icon: 'fa fa-list',
                                    // extend:'data-area=\'["100%","100%"]\'',
                                    url: 'order/detail',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },
                                {
                                    id: 'order_detail',
                                    name: 'order_detail',
                                    title: __('订单商品'),
                                    text: __('商品'),
                                    classname: 'btn btn-xs btn-primary btn-orderdetail btn-dialog',
                                    icon: 'fa fa-list',
                                    // extend:'data-area=\'["100%","100%"]\'',
                                    url: 'order_detail/index?order_id={ids}',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },
                                {
                                    id: 'address_detail',
                                    name: 'address_detail',
                                    title: __('地址地址'),
                                    text: __('地址'),
                                    classname: 'btn btn-xs btn-primary  btn-dialog',
                                    icon: 'fa fa-list',
                                    extend:'data-area=\'["100%","100%"]\'',
                                    url: 'order/address_detail',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },
                                {
                                    name: 'ajax',
                                    title: __('确认发货'),
                                    text: __('发货'),
                                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                                    icon: 'fa fa-magic',
                                    confirm: '确认已经发货？',
                                    url: 'order/send',
                                    success: function (data, ret) {
                                        // Layer.alert(ret.msg + ",返回数据：" + JSON.stringify(data));
                                        Layer.alert("发货成功");
                                        //如果需要阻止成功提示，则必须使用return false;
                                        //return false;
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        Layer.alert(ret.msg);
                                        return false;
                                    }
                                },
                            ],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ],
                
                onLoadSuccess:function(){
                    // 这里就是数据渲染结束后的回调函数
                    $(".btn-orderdetail").data("area", ['100%','100%']);
                }
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        remarks: function () {
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
