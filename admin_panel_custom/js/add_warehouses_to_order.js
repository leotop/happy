document.addEventListener('DOMContentLoaded', function(){          

    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;


    var table = document.querySelector('#sale_order_basketsale_order_view_product_table'); //просмотр заказа
    if(!table) {
        table = document.querySelector('#sale_order_basketsale_order_edit_product_table'); //редактирование заказа
    }

    var table_popup = document.querySelector('#bx-admin-prefix'); //#bx-admin-prefix #tbl_sale_order


    var observer = new MutationObserver(function(mutations) {  
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {   
                //список товаров в заказе
                var tr = document.querySelectorAll(".adm-s-order-table-ddi-table-img").length;                
                if (tr > 1) {
                    addWarehouse();
                }

                //выбор товаров во всплывающем окне
                var tbl_popup = document.querySelectorAll("#tbl_product_search_order_edit_result_div").length;                
                if (tbl_popup > 1) {
                    addWarehousePopup();
                }
            }


        });
    });      

    observer.observe(table, {  
        childList: true, 
        characterData: true 
    });   


    observer.observe(table_popup, {
        childList: true, 
        characterData: true 
    });    

    addWarehouse();
    addWarehousePopup();

    BX.addCustomEvent('onAjaxSuccess', function() {
        addWarehouse();
        addWarehousePopup();
    });
    BX.addCustomEvent('onAjaxSuccessFinish', function() {
        addWarehouse();
        addWarehousePopup();
    });

    } , false);

//извлечение параметров из урла
function getUrlVars(url) {
    if (!url) {
        var url = window.location.href; 
    }
    var vars = {};      
    var parts = url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {  
        vars[key] = value;       
    });               
    return vars;      
}


//для таблиц в заказе    
function addWarehouse() {

    //выбираем нужную таблицу (в зависимости от страницы - создание/изменение заказа)
    var table = false;
    if ($("#sale_order_basketsale_order_view_product_table").length > 0) {
        table = $("#sale_order_basketsale_order_view_product_table");
    }
    else if ($("#sale_order_basketsale_order_edit_product_table").length > 0) {
        table = $("#sale_order_basketsale_order_edit_product_table"); 
    }

    if (!table) {
        return false;
    }

    var cell_index = 0; //получаем индекс ячейки с остатками
    table.find("thead > tr > td").each(function(){
        if($(this).html().indexOf("Остаток") >= 0) {
            cell_index = $(this).index(); 
            return;
        }
    }) 

    //перебираем товары в заказе
    table.find(".adm-s-order-table-ddi-table-img:visible").each(function(){
        var tr = $(this).parent();

        //получаем ID товара из столбца со ссылкой на товар
        var item_img_href = tr.find(".adm-s-order-table-ddi-table-img a").attr("href");
        var item_params = getUrlVars(item_img_href);
        var item_id = item_params.ID;

        post_data = {item_id: item_id};
        
        var url_vars = getUrlVars();
        post_data.order_id = url_vars.ID; //добавляем в запрос ID заказа

        //запрашиваем остатки                            
        $.post("/admin_panel_custom/ajax/add_warehouses_to_order.php", post_data, function(data){
            if (data != "error") {
                var result_data = JSON.parse(data);   

                var result_html = "";     
                for (key in result_data.warehouses){
                    result_html += "<b>" + key + "</b>:" + result_data.warehouses[key] + "<br>";                                
                }  


                //если в ответе пришло максимальное доступное количество
                if (result_data.available_quantity && parseInt(result_data.available_quantity) > 0) {
                    tr.find(".tac").attr("data-available_quantity", parseInt(result_data.available_quantity));
                    tr.find(".tac").on("keyup", function() {
                        var max_quantity = parseInt($(this).data("available_quantity"));
                        if (parseInt($(this).val()) > max_quantity) {
                           $(this).val(max_quantity); 
                        }
                    })
                }        

                //размещаем остатки в нужный столбец
                tr.find("td:eq(" + cell_index + ")").html(result_html); 
            }
        })
    })
}


//для всплывающих окон
function addWarehousePopup() {
    if ($("#tbl_product_search_order_edit_result_div #tbl_product_search_order_edit").length > 0) {
        var table = $("#tbl_product_search_order_edit_result_div #tbl_product_search_order_edit"); 

        var cell_index = 0;  //индекс столбца с количеством
        var ID_cell_index = 0; //индекс столбца с ID товара
        var action_cell_index = 0; //индекс столбца действий
        table.find("thead > tr > td").each(function(){
            if($(this).html().indexOf("Остаток") >= 0) {
                cell_index = $(this).index(); 
            }
            if($(this).html().indexOf("ID") >= 0) {
                ID_cell_index = $(this).index(); 
            }
            if($(this).html().indexOf("Действие") >= 0) {
                action_cell_index = $(this).index(); 
            }
        })  

        //перебираем товары во всплывающем окне
        table.find(".adm-list-table-row").each(function(){
            var tr = $(this);
            if (parseInt(tr.find("td:eq(" + cell_index + ")").html()) >= 0) {     

                var item_id = tr.find("td:eq(" + ID_cell_index + ")").html(); 
                //для торговых предложений
                if (item_id.indexOf("-") > 0) {
                    var ids = item_id.split("-");
                    item_id = ids[1];
                }    

                post_data = {item_id: item_id};

                //если мы находимся на странице редактирования заказа, то передаем ID заказа в запрос
                if ((document.location.href).indexOf("sale_order_edit.php") >= 0) {
                    var url_vars = getUrlVars();
                    post_data.order_id = url_vars.ID; //добавляем в запрос ID заказа 
                }     

                //запрашиваем остатки
                $.post("/admin_panel_custom/ajax/add_warehouses_to_order.php", post_data, function(data){
                    if (data != "error") {
                        var result_data = JSON.parse(data);   

                        var result_html = "";
                        //формируем иготовый html
                        for (key in result_data.warehouses){
                            result_html += "<b>" + key + "</b>:" + result_data.warehouses[key] + "<br>";                                
                        }

                        //если в ответе пришел параметр can_buy = "N" то убираем кнопку добавления товара и контекстное меню
                        if (result_data.can_buy && result_data.can_buy == "N") {
                            tr.find("td:eq(" + action_cell_index + ")").html("");
                            tr.find("td:eq(0)").html("");
                            tr.removeAttr("ondblclick").removeAttr("oncontextmenu");
                        }

                        //размещаем остатки в нужный столбец
                        tr.find("td:eq(" + cell_index + ")").html(result_html); 
                    }
                })
            }
        })
    }
}