<?
    CModule::IncludeModule("sale");

    use Bitrix\Main;
    use Bitrix\Sale\Order;
    file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/constants.php') ? require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/constants.php') : "";
    file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/orderStoreHandler.php') ? require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/orderStoreHandler.php') : "";

    // обработчик для привязки склада к заказу  
    Main\EventManager::getInstance()->addEventHandler( 
        'sale',
        'OnSaleOrderSaved',
        'storeHandler'
    );    

    /**
    * Обработчик для привязки склада к заказу
    *
    * @param Main\Event $event
    * @return void
    * 
    * */
    function storeHandler(Main\Event $event) {          
        $order = $event->getParameter("ENTITY");    
        $is_new = $event->getParameter("IS_NEW"); 
        if ($is_new) {      
            setShipment($order);        
        }
    }

    function arshow($array, $adminCheck = false){
        global $USER;
        $USER = new Cuser;
        if ($adminCheck) {
            if (!$USER->IsAdmin()) {
                return false;
            }
        }
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }


    //подключаем скрипт для добавления складов на странице просмотра/редактирования заказа в административной части
    if ($APPLICATION->GetCurPage() == "/bitrix/admin/sale_order_view.php" 
        || $APPLICATION->GetCurPage() == "/bitrix/admin/sale_order_edit.php" 
        || $APPLICATION->GetCurPage() == "/bitrix/admin/sale_order_create.php") {
        $APPLICATION->AddHeadScript('https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js');
        $APPLICATION->AddHeadScript("/admin_panel_custom/js/add_warehouses_to_order.js");   
    }

    /**
    * функция-обертка для установки отгрузок
    * 
    * @param object Bitrix\Sale\Order $order 
    */         
    function setShipment($order) {
        $items = array();
        $store_handler = new orderStoreHandler();           
        $delivery_id = $order->getDeliverySystemId();
        $one_try = in_array(array_shift($delivery_id), array(MOSCOW_PICKUP, SAINT_PETERSBURG_PICKUP)) ? true : false; // если для Спб или Москвы выбран самовывоз, то проверяем только 1 раз для выбранного города
        $location_id = $order->getPropertyCollection()->getDeliveryLocation()->getValue();
        $properties_list = CSaleOrderProps::GetList(
            array(),
            array(
                "CODE"           => STORE_PROPERTY_CODE,
                "PERSON_TYPE_ID" => $order->getPersonTypeId()
            ),
            false,
            false,
            array("ID")
        );
        if ($property = $properties_list->Fetch()) {
            $store_property_id = $property['ID'];
        }

        foreach ($order->getBasket() as $basket_item) {
            $items[$basket_item->getProductId()] = $basket_item->getQuantity();
        }

        $result = $store_handler->checkAvailability($items, $location_id, $one_try);
        if ($result['success']) {
            $order->setField('STATUS_ID', ACCEPTED_STATUS);
            $order->getPropertyCollection()->getItemByOrderPropertyId($store_property_id)->setValue($result['store_name']);
            $shipments = $order->getShipmentCollection();
            // разрешаем доставку
            $shipments->allowDelivery();
            // разрешаем отгрузку
            foreach ($shipments as $shipment) {     
                if ($shipment->getField("ALLOW_DELIVERY") == "Y") {
                    $shipment->setFieldNoDemand('DEDUCTED', "Y");
                    $shipment->save();
                }
            }    
            $order->save();
        }
    }      


     //функция разбиения заказа при недостаточном количестве товара на одном складе
    Main\EventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderBeforeSaved', 'orderExplode');     
    function orderExplode(Main\Event $event) {     
    
        //получаем объект заказа
        $order = $event->getParameter("ENTITY");         

        //если у новозо заказа не статус "принят"
        if ($order->GetField("STATUS_ID") != "o") {      

            $delivery_price = 0;
            $shipments = $order->getShipmentCollection();   
            foreach ($shipments as $shipment) {   
                //получаем стоимость доставки
                if ($shipment->GetField("DELIVERY_ID") == $order->GetField("DELIVERY_ID")) {
                    $delivery_price = $shipment->getField("PRICE_DELIVERY");
                }     
            }   

            $order_props_all = $order->getPropertyCollection()->getArray();
            $order_props = $order_props_all["properties"];

            foreach ($order_props as $arProp) {
                $newOrdersProps[$arProp["ID"]] = array(   
                    "ORDER_PROPS_ID" => $arProp["ID"], 
                    "NAME" => $arProp["NAME"],
                    "VALUE" => $arProp["VALUE"][0],
                    "CODE" => $arProp["CODE"]       
                );  
            }

            $deliveryPriceNew = $delivery_price / 2;    

            $newOrder = array (
                "FIRST_ORDER" => array(),
                "SECOND_ORDER" => array(),
            );

            //создаем заказ
            $order_fields = array(
                "LID"            => $order->GetField("LID"),
                "PERSON_TYPE_ID" => $order->GetField("PERSON_TYPE_ID"),
                "PAYED"          => $order->GetField("PAYED"),
                "CANCELED"       => $order->GetField("CANCELED"),
                "STATUS_ID"      => "o",
                "CURRENCY"       => $order->GetField("CURRENCY"),
                "USER_ID"        => $order->GetField("USER_ID"),
                "PAY_SYSTEM_ID"  => $order->GetField("PAY_SYSTEM_ID"),                       
                "PRICE_DELIVERY" => $deliveryPriceNew,
                "DELIVERY_ID"    => $order->GetField("DELIVERY_ID")
            );
           
            //склад по умолчанию
            $defaultStore = "Москва";
            $secondStore = "Петербург";

            //заполняем данные для будущих заказов
            $newOrder["FIRST_ORDER"]["DELIVERY_PRICE"] = $newOrder["SECOND_ORDER"]["DELIVERY_PRICE"] = $deliveryPriceNew;
            $newOrder["FIRST_ORDER"]["STORE"] = $defaultStore;
            $newOrder["SECOND_ORDER"]["STORE"] = $secondStore;

            $newOrderIDS = array();

            $basket = CSaleBasket::GetList(array(), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"));
            while($arBasketItem = $basket->Fetch()) {
                $amount = array();
                $arItemAmount = CCatalogStoreProduct::GetList(false, array("PRODUCT_ID" => $arBasketItem["PRODUCT_ID"]));
                while ($itemAmount = $arItemAmount->Fetch()) {
                    $amount[$itemAmount["STORE_NAME"]] = $itemAmount["AMOUNT"];
                }

                //количество на складе по умолчанию
                $defaultStoreQuantity = $amount[$defaultStore];

                //переформировываем корзины для новоых заказов                
                //если на складе по умолчанию меньше товара, чем в заказе, разбиваем товар на 2 заказа
                if ($defaultStoreQuantity < $arBasketItem["QUANTITY"]) {      
                    $newOrder["FIRST_ORDER"]["PRODUCTS"][$arBasketItem["PRODUCT_ID"]] = $arBasketItem;
                    $newOrder["FIRST_ORDER"]["PRODUCTS"][$arBasketItem["PRODUCT_ID"]]["QUANTITY"] = $defaultStoreQuantity;
                    $newOrder["FIRST_ORDER"]["PRICE"] += $defaultStoreQuantity * $arBasketItem["PRICE"];

                    //количество товара, которое переносим во второй заказ
                    $secondOrderQuantity = $arBasketItem["QUANTITY"] - $defaultStoreQuantity;
                    $newOrder["SECOND_ORDER"]["PRODUCTS"][$arBasketItem["PRODUCT_ID"]] =  $arBasketItem;
                    $newOrder["SECOND_ORDER"]["PRODUCTS"][$arBasketItem["PRODUCT_ID"]]["QUANTITY"] = $arBasketItem["QUANTITY"] - $defaultStoreQuantity;     
                    $newOrder["SECOND_ORDER"]["PRICE"] += $secondOrderQuantity * $arBasketItem["PRICE"];         
                } else { //иначе оставляем в первом заказе весь товар
                    $newOrder["FIRST_ORDER"]["PRODUCTS"][$arBasketItem["PRODUCT_ID"]] = $arBasketItem;
                    $newOrder["FIRST_ORDER"]["PRODUCTS"][$arBasketItem["PRODUCT_ID"]]["QUANTITY"] = $arBasketItem["QUANTITY"];
                    $newOrder["FIRST_ORDER"]["PRICE"] += $arBasketItem["QUANTITY"] * $arBasketItem["PRICE"];
                }

                //удалям из корзины текущий товар, чтобы добавить его заново в нужном количестве дл двуз новых закаов
                CSaleBasket::Delete($arBasketItem["ID"]);
            }           


            //если заполнился массив с товарами для второго заказа, создаем 2 новых заказа
            if (is_array($newOrder["SECOND_ORDER"]["PRODUCTS"]) && count($newOrder["SECOND_ORDER"]["PRODUCTS"]) > 0) {
                //очищаем массив в сессии. он заполнится ниже
                unset($_SESSION["EXPLODE_ORDERS"]);   
                foreach ($newOrder as $orderID => $arOder) { 
                    //добавляем корзину для заказа
                    foreach ($arOder["PRODUCTS"] as $pID => $product) {
                        $basketFields = array(
                            "PRODUCT_ID"       => $product["PRODUCT_ID"],
                            "PRODUCT_XML_ID"   => $product["PRODUCT_XML_ID"],
                            "PRICE"            => $product["PRICE"],
                            "CURRENCY"         => $product["CURRENCY"],
                            "WEIGHT"           => $product["WEIGHT"],
                            "QUANTITY"         => $product["QUANTITY"],
                            "LID"              => $product["LID"],
                            "DELAY"            => $product["DELAY"],
                            "CAN_BUY"          => $product["CAN_BUY"],
                            "NAME"             => $product["NAME"],
                            "MODULE"           => $product["MODULE"],                                      
                            "DETAIL_PAGE_URL"  => $product["DETAIL_PAGE_URL"],
                            "CATALOG_XML_ID"   => $product["CATALOG_XML_ID"],
                            "FUSER_ID"         => $product["FUSER_ID"],                              
                            "PRODUCT_PROVIDER_CLASS" => $product["PRODUCT_PROVIDER_CLASS"],       
                        );
                        $basketID = CSaleBasket::Add($basketFields); 
                    }

                    $order_fields["PRICE"] = $arOder["PRICE"];

                    //добавляем комментарий для второго заказа   
                    if ($orderID == "SECOND_ORDER") {  
                        $order_fields["COMMENTS"] = $order->GetField("COMMENTS"). " [Связанный заказ: №".$newOrderIDS["FIRST_ORDER"]."]";
                    }    

                    $order_id = CSaleOrder::Add($order_fields);   

                    //если заказ успешно создался
                    if ($order_id) {  

                        //пишем в сессию номера новых заказов, которые получились при разбиении:
                        $_SESSION["EXPLODE_ORDERS"][] = $order_id; 

                        //добавляем в первый заказ ID второго заказа
                        if ($orderID == "SECOND_ORDER") {
                            $firstOrderData = CSaleOrder::GetByID($newOrderIDS["FIRST_ORDER"]);
                            CSaleOrder::Update($newOrderIDS["FIRST_ORDER"], array("COMMENTS" => $firstOrderData["COMMENTS"]. " [Связанный заказ: №".$order_id."]"));
                        }

                        $newOrderIDS[$orderID] = $order_id; //массив с ID новых заказов нужен для последующей связки заказов   

                        //добавляем свойства заказа
                        foreach ($newOrdersProps as $pID => $orderProp) {

                            if ($pID == 22) { //склад
                                $orderProp["VALUE"] = $arOder["STORE"]; 
                            }      

                            $arPropFields = array(
                                "ORDER_ID" => $order_id,
                                "ORDER_PROPS_ID" => $pID,
                                "NAME" => $orderProp["NAME"],
                                "CODE" => $orderProp["CODE"],
                                "VALUE" => $orderProp["VALUE"]
                            );

                            CSaleOrderPropsValue::Add($arPropFields);
                        }
                        //привязываем текущую карзину к заказу и затем очищаем ее
                        CSaleBasket::OrderBasket($order_id, CSaleBasket::GetBasketUserID());  
                        CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());      

                        //создаем объект заказа
                        $order = Order::Load($order_id);
                        //разрешаем доставку через функцию-обертку
                        setShipment($order);

                    }   


                }  
                return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::ERROR, new \Bitrix\Sale\ResultError('Ошибка', 'code'), 'sale'); 
            }   
        }        

    }
    
    
?>