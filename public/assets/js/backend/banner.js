define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'banner/index' + location.search,
                    add_url: 'banner/add',
                    edit_url: 'banner/edit',
                    del_url: 'banner/del',
                    multi_url: 'banner/multi',
                    table: 'banner',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'image', title: __('Image'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'type', title: __('Type'), searchList: {"seckill":__('Type seckill'),"coupon":__('Type coupon'),"goods":__('Type goods'),"fullcut":__('Type fullcut'),"category":__('Type category'),"vip":__('Type vip'),"other":__('Type other')}, formatter: Table.api.formatter.normal},
                        {field: 'link_url_text', title: __('Link_url')},
                        {field: 'status', title: __('Status'), searchList: {"normal":__('Status normal'),"hidden":__('Status hidden')}, formatter: Table.api.formatter.status},
                        {field: 'weigh', title: __('Weigh')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'banner/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'banner/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'banner/destroy',
                                    refresh: true
                                }
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

            $('#c-type').change(function () {
                var type = $('#c-type').val()
                console.log(type);
                switch (type) {
                    case "seckill" : //1.秒杀专区
                        $("#goods").attr("hidden",true);

                        $("#category").attr("hidden",true);

                        $("#other").attr("hidden",true);
                        
                        $("#fullcut").attr("hidden",true);
                        break;
                    case "coupon" : //2.领券中心
                        $("#goods").attr("hidden",true);

                        $("#category").attr("hidden",true);

                        $("#other").attr("hidden",true);
                        
                        $("#fullcut").attr("hidden",true);
                        break;
                    case "goods" : //3.商品详情
                        $("#goods").attr("hidden",false);

                        $("#category").attr("hidden",true);

                        $("#other").attr("hidden",true);
                        
                        $("#fullcut").attr("hidden",true);
                        break;
                    case "fullcut" : //4.满减列表
                        $("#goods").attr("hidden",true);

                        $("#category").attr("hidden",true);

                        $("#other").attr("hidden",true);
                        
                        $("#fullcut").attr("hidden",false);
                        break;
                    case "category" : //5.商品列表(某个二级分类下的)
                        $("#goods").attr("hidden",true);

                        $("#category").attr("hidden",false);

                        $("#other").attr("hidden",true);
                        
                        $("#fullcut").attr("hidden",true);
                        break;
                    case "vip" : //6.会员卡
                        $("#goods").attr("hidden",true);

                        $("#category").attr("hidden",true);

                        $("#other").attr("hidden",true);
                        
                        $("#fullcut").attr("hidden",true);
                        break;
                    case "other" : //7.外链
                        $("#goods").attr("hidden",true);

                        $("#category").attr("hidden",true);

                        $("#other").attr("hidden",false);
                        
                        $("#fullcut").attr("hidden",true);
                        break;
                }
            })

            Controller.api.bindevent();
        },
        edit: function () {
            $('#c-type').change(function () {
                var type = $('#c-type').val()
                console.log(type);
                switch (type) {
                    case "seckill" : //1.秒杀专区
                        $("#goods").attr("hidden",true);

                        $("#category").attr("hidden",true);

                        $("#other").attr("hidden",true);
                        
                        $("#fullcut").attr("hidden",true);
                        break;
                    case "coupon" : //2.领券中心
                        $("#goods").attr("hidden",true);

                        $("#category").attr("hidden",true);

                        $("#other").attr("hidden",true);
                        
                        $("#fullcut").attr("hidden",true);
                        break;
                    case "goods" : //3.商品详情
                        $("#goods").attr("hidden",false);

                        $("#category").attr("hidden",true);

                        $("#other").attr("hidden",true);
                        
                        $("#fullcut").attr("hidden",true);
                        break;
                    case "fullcut" : //4.满减列表
                        $("#goods").attr("hidden",true);

                        $("#category").attr("hidden",true);

                        $("#other").attr("hidden",true);
                        
                        $("#fullcut").attr("hidden",false);
                        break;
                    case "category" : //5.商品列表(某个二级分类下的)
                        $("#goods").attr("hidden",true);

                        $("#category").attr("hidden",false);

                        $("#other").attr("hidden",true);
                        
                        $("#fullcut").attr("hidden",true);
                        break;
                    case "vip" : //6.会员卡
                        $("#goods").attr("hidden",true);

                        $("#category").attr("hidden",true);

                        $("#other").attr("hidden",true);
                        
                        $("#fullcut").attr("hidden",true);
                        break;
                    case "other" : //7.外链
                        $("#goods").attr("hidden",true);

                        $("#category").attr("hidden",true);

                        $("#other").attr("hidden",false);
                        
                        $("#fullcut").attr("hidden",true);
                        break;
                }
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