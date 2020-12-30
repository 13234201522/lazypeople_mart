define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'province_tax/index' + location.search,
                    add_url: 'province_tax/add',
                    edit_url: 'province_tax/edit',
                    del_url: 'province_tax/del',
                    multi_url: 'province_tax/multi',
                    table: 'province_tax',
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
                        {field: 'name', title: __('Name')},
                        {field: 'place_id', title: __('Place_id')},
                        {field: 'tax_rate', title: __('Tax_rate')},
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
            const input = document.getElementById("c-name");
            const searchBox = new google.maps.places.SearchBox(input);

            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();
                console.log(places);
                //判断当前输入的是不是城市
                const level = places[0].types[0];
                const place_id = document.getElementById("c-place_id");
                if (level == 'administrative_area_level_1') {
                    const placeId = places[0].place_id;
                    console.log(placeId);
                    place_id.setAttribute("value", placeId);
                } else {
                    place_id.setAttribute("value", '');

                    Layer.alert('请输入省');
                }


            });
            console.log(input);
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