<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");

    if (!CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog")) {
        echo "error"; 
    }

    if (intval($_POST["order_id"]) > 0) {
        $orderID = intval($_POST["order_id"]);
    }

    if (intval($_POST["item_id"]) > 0) {  //зарпос со страницы создания заказа      
        $result =  array("warehouses" => getAmountByProductID(intval($_POST["item_id"])));  

        //если находимся на странице изменения заказа, то нужно сделать проверку на возможность добавления товара с определенного склада
        if (!empty($orderID)) {
            //получаем значение склада из заказа
            $orderWarehouse = CSaleOrderPropsValue::GetList(array(), array("CODE" => "STORE", "ORDER_ID" => $orderID),false, false, array())->Fetch();
            $storeName = $orderWarehouse["VALUE"];
            //получаем остатки на данном складе у текущего товара
            $arItemAmount = CCatalogStoreProduct::GetList(false, array("PRODUCT_ID"=>intval($_POST["item_id"]), "STORE_NAME" => $storeName))->Fetch();
            if ($arItemAmount["AMOUNT"] <= 0) {
               $result["can_buy"] = "N"; 
            } else {
               $result["available_quantity"] = $arItemAmount["AMOUNT"]; 
            }      
            
        }    

        echo json_encode($result);
    } else {
        echo "error";
    }


    //получаем остатки по складам для товара
    function getAmountByProductID($ID) {    

        $ID = intval($ID); 
        if (empty($ID)) {
            return "error"; 
        }                      

        $data = array();
        //получаем остатки по складам у данного товара
        $rsItemAmount = CCatalogStoreProduct::GetList(false, array("PRODUCT_ID"=>$ID));
        while ($arItemAmount = $rsItemAmount->Fetch()) {
            $data[$arItemAmount["STORE_NAME"]] = $arItemAmount["AMOUNT"];
        }
        if (count($data) <= 0) {
            //если информация по складам не найдена, проставляем по умолчанию нули для всех складов
            $arStore = array(); 
            $storeList = CCatalogStore::GetList(array(), array(), false, false, array());
            while($store = $storeList->Fetch()) {
                $data[$store["TITLE"]] = 0;    
            }              
        }

        return $data;
    }                                                

?>