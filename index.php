<?php
header("Content-type: text/html; charset=utf-8");
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Cognitive Testwork</title>
    <link rel="stylesheet" type="text/css" href="extjs-4.1.1/resources/css/ext-all.css">
    <script type="text/javascript" src="extjs-4.1.1/ext-all-debug.js"></script>
    <script type="text/javascript">

    // Определяем модели
    Ext.define('User', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'name',  type: 'string'},
            {name: 'id',   type: 'int'},
            {name: 'education_id',   type: 'int'},
            {name: 'education', type: 'string'},
            {name: 'cities', type: 'string'},
            {name: 'cities_ids', type: 'string'}
        ]
    });
    Ext.define('Education', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'selected',  type: 'int'},
            {name: 'id',   type: 'int'},
            {name: 'name', type: 'string'}
        ]
    });
    Ext.define('City', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'selected',  type: 'int'},
            {name: 'id',   type: 'int'},
            {name: 'name', type: 'string'}
        ]
    });

    // Определяем фильтры для стора
    educFilter = new Ext.util.Filter({
        selectedIds: [],
        filterFn: function(item) {
            // Строка отображается, если ни одной галки не выделено, либо выделена галка нужного образования.
            if (educFilter.selectedIds.length == 0)
                return true;
            return educFilter.selectedIds.indexOf(item.data.education_id)>= 0;
        }
    });

    cityFilter = new Ext.util.Filter({
        selectedIds: [],
        filterFn: function(item) {
            // Строка отображается, если ни одной галки не выделено, либо выделен хотя бы один город пользователя.
            if (cityFilter.selectedIds.length == 0)
                return true;
//            console.log(cityFilter.selectedIds, item.data.cities_ids)
            for (i in cityFilter.selectedIds)
                if (item.data.cities_ids.indexOf(","+cityFilter.selectedIds[i]+",") > -1)
                    return true;
            return false;
        }
    });

    function clickEducation(item_id)   {
        var new_val = !Ext.getStore('EducationsStore').getById(item_id).data.selected;
        // галка поставлена - добавляем ее в массив, убрана - удаляем
        if (new_val)
            educFilter.selectedIds.push(item_id)
        else
            educFilter.selectedIds.splice(educFilter.selectedIds.indexOf(item_id), 1)
        Ext.getStore('EducationsStore').getById(item_id).data.selected = new_val;
        Ext.getStore("UsersStore").clearFilter(educFilter);
        Ext.getStore("UsersStore").filter(educFilter)
    }

    function clickCity(item_id)   {
        var new_val = !Ext.getStore('CitiesStore').getById(item_id).data.selected;
        // галка поставлена - добавляем ее в массив, убрана - удаляем
        if (new_val)
            cityFilter.selectedIds.push(item_id)
        else
            cityFilter.selectedIds.splice(cityFilter.selectedIds.indexOf(item_id), 1)
        Ext.getStore('CitiesStore').getById(item_id).data.selected = new_val;
        Ext.getStore("UsersStore").clearFilter(cityFilter);
        Ext.getStore("UsersStore").filter(cityFilter)
    }    

    EducationGrid = new Ext.grid.Panel({
        title: 'Образование',
        columns: [
            {   header: ''
                ,  dataIndex: 'selected'
                , renderer: function(val,x, item) {
                return "<input type='checkbox' onclick='clickEducation("+item.data.id+")' "+(val ? "checked": "");
            }
                , width: 50
            },
            { header: '', dataIndex: 'id', hidden: true },
            { header: 'Образование', dataIndex: 'name' }
        ],
        store: new Ext.data.JsonStore({
            model: 'Education',
            autoDestroy: true,
            storeId: 'EducationsStore',
            autoLoad: true,
            proxy: {
                type: 'ajax',
                url: 'ajax.php?get_educations_data',
                reader: {
                    type: 'json',
                    root: 'root',
                    idProperty: 'id'
                }
            }
        })
    });

    CityGrid = new Ext.grid.Panel ({
        title: 'Город',
        columns: [
            {   header: ''
                ,  dataIndex: 'selected'
                , renderer: function(val,x, item) {
                return "<input type='checkbox' onclick='clickCity("+item.data.id+")' "+(val ? "checked": "");
            }
                , width: 50
            },
            { header: '', dataIndex: 'id', hidden: true },
            { header: 'Город', dataIndex: 'name', width: 150 }
        ],
        store: new Ext.data.JsonStore({
            model: 'City',
            autoDestroy: true,
            storeId: 'CitiesStore',
            autoLoad: true,
            proxy: {
                type: 'ajax',
                url: 'ajax.php?get_cities_data',
                reader: {
                    type: 'json',
                    root: 'root',
                    idProperty: 'id'
                }
            }
        })
    });

    MainGrid = new Ext.grid.Panel({
        title: "Пользователи",
        height: "600px",
        columns: [
            {header: "Имя пользователя", dataIndex: "name", width: 150},
            {header: "Образование", dataIndex: "education"},
            {header: "Города", dataIndex: "cities", width: 250}
        ],
        store: new Ext.data.JsonStore({
            // store configs
            autoDestroy: true,
            storeId: 'UsersStore',
            autoLoad: true,
            proxy: {
                type: 'ajax',
                url: 'ajax.php?get_users_data',
                reader: {
                    type: 'json',
                    root: 'root',
                    idProperty: 'id'
                }
            },
            model: 'User'
        }),
        listeners: {
            selectionChange: function(obj, selected)    {
                if (!selected.length) return;
                EducChangePanel.getComponent('userName').setValue(selected[0].data.name)
                EducChangePanel.getComponent('userEduc').setValue(selected[0].data.education_id)

            }
        }
    });

    EducChangePanel = new Ext.Panel({
        layout: 'column',
        padding: 10,

        items: [
            new Ext.form.field.Text({
                fieldLabel: 'Для пользователя',
                readOnly: true,
                itemId: "userName",
                labelWidth: 120,
                width: 300,
                margin: 5
            }),
            new Ext.form.ComboBox({
                fieldLabel: 'установить образование',
                store: Ext.getStore('EducationsStore'),
                displayField: "name",
                valueField: "id",
                itemId: "userEduc",
                mode: "local",
                labelWidth: 150,
                margin: 5
            }),
            new Ext.Button({
                text: 'Сохранить',
                handler: function() {
                    user_id = MainGrid.getSelectionModel().getSelection()[0].data.id;
                    education_id = EducChangePanel.getComponent('userEduc').getValue();
                    Ext.Ajax.request({
                        url: 'ajax.php?change_user_education',
                        method: 'POST',
                        params: {
                            user_id: user_id,
                            education_id: education_id
                        },
                        success: function(resp) {
                            eval("resp="+resp.responseText)
                            if (resp.success)   {
                                Ext.getStore('UsersStore').reload();
                            }
                        }
                    })
                }
            })
        ]
    })


    Ext.application({
        name: 'Cognitive Testwork',
        launch: function() {
            Ext.create('Ext.container.Viewport', {
                layout: 'column',
                items: [
                    new Ext.Panel({
                        width: "20%",
                        position: "west",
                        items:  [
                           EducationGrid,
                           CityGrid
                        ]
                    }),
                    new Ext.Panel({
                        caption:"Пользователи",
                        position: "center",
                        layout: "form",
                        width: "80%",
                        height: "70%",
                        items: [
                            MainGrid,
                            EducChangePanel
                        ]
                    })
                ]
            });
        }
    });

    </script>
</head>
<body></body>
</html>