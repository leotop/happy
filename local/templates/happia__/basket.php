<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "dropdown_cart", 
	Array(
        "PATH_TO_BASKET" => "/personal/cart/",
        "PATH_TO_ORDER" => "/personal/order/",
        "SHOW_PRODUCTS" => "Y",
        "SHOW_DELAY" => "Y",
        "SHOW_NOTAVAIL" => "Y",
        "SHOW_SUBSCRIBE" => "N",
        "SHOW_SUMMARY" => "N",
        "SHOW_IMAGE" => "Y",
        "SHOW_PRICE" => "Y"
    ), false
);?>