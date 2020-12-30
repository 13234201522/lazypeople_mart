define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'real/index' + location.search,
                    add_url: 'real/add',
                    //edit_url: 'real/edit',
                    del_url: 'real/del',
                    multi_url: 'real/multi',
                    table: 'real',
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
                        {field: 'user.nickname', title: __('User.nickname')},
                        {field: 'user.avatar', title: __('User.avatar'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'front_image', title: '证件（图一）', events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'behind_image', title: '证件（图二）', events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'hold_image', title: '证件（图三）', events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3')}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'pass',
                                    title: __('通过'),
                                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                                    icon: 'fa fa-check',
                                    url: 'real/pass?status=2',
                                    visible: function (row) {
                                        //返回true时按钮显示,返回false隐藏
                                        if ( row.status == 1) {
                                            return true;
                                        }
                                        return false;
                                    },
                                    success: function (data, ret) {

                                        //如果需要阻止成功提示，则必须使用return false;
                                        //return false;
                                        $(".btn-refresh").trigger("click");
                                    },
                                },
                                {
                                    name: 'no',
                                    title: __('驳回'),
                                    classname: 'btn btn-xs btn-danger btn-magic btn-ajax',
                                    icon: 'fa fa-times',
                                    url: 'real/pass?status=3',
                                    visible: function (row) {
                                        //返回true时按钮显示,返回false隐藏
                                        if ( row.status == 1) {
                                            return true;
                                        }
                                        return false;
                                    },
                                    success: function (data, ret) {
                                        //如果需要阻止成功提示，则必须使用return false;
                                        //return false;
                                        $(".btn-refresh").trigger("click");
                                    },
                                },
                            ],
                            formatter: Table.api.formatter.operate
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