<?
class orderStoreHandler {
    // Т.к. варинатов только два, то и попытки будет только две
    private $first_attempt = true;
    // если для Спб или Москвы выбран самовывоз, то проверяем только 1 раз для выбранного города
    private $one_try = false;
    // Текущий выбранный город
    private $city;
    // Отношение ID местоположения к ID склада
    private $location_id_to_store = array(
        MOSCOW_LOCATION_ID           => MOSCOW_STORE_ID,
        SAINT_PETERSBURG_LOCATION_ID => SAINT_PETERSBURG_STORE_ID
    );
    // Отношение ID местоположения к названию склада
    private $location_id_to_store_name = array(
        MOSCOW_LOCATION_ID           => "Москва",
        SAINT_PETERSBURG_LOCATION_ID => "Петербург"
    );
    
    /**
     * 
     * Получить соотношение товаров к складам + количество
     * 
     * @param array $items
     * @return array $result
     * 
     * */
    
    private function getItemsToWarehouseAssignment($items) {
        $result = array();
        $store_items = CCatalogStoreProduct::GetList(
            Array(),
            Array(
                "PRODUCT_ID" => $items
            ),
            false,
            false,
            Array(
                "ID",
                "PRODUCT_ID",
                "STORE_ID",
                "AMOUNT"
            )
        );
        
        while ($store_item = $store_items->Fetch()) {
            if (!$result[$store_item['PRODUCT_ID']]) {
                $result[$store_item['PRODUCT_ID']] = array();
            }
            $result[$store_item['PRODUCT_ID']][$store_item['STORE_ID']] = $store_item['AMOUNT'];
        }
        
        return $result;
    }
    
    /**
     * 
     * Переключить город на второй итерации
     * 
     * @return void
     * 
     * */
    
    private function switchCity() {
        $this->city = array_pop(array_diff(array_keys($this->location_id_to_store), [$this->city]));
    }
    
    /**
     * 
     * Основная функция проверки количества
     * 
     * @param array $items
     * @param array $items_to_warehouse
     * @return boolean
     * 
     * */
    
    private function check($items, $items_to_warehouse) {
        if (!is_array($items)|| count($items) <= 0) {
            return false;
        }
        $current_warehouse_id = $this->location_id_to_store[$this->city];
        if ($this->first_attempt) {
            foreach ($items as $item_id => $needed_quantity) {
                if ($items_to_warehouse[$item_id][$current_warehouse_id] < $needed_quantity) {
                    $this->first_attempt = false;
                    $this->switchCity();
                    return $this->one_try ? false : $this->check($items, $items_to_warehouse);
                }
            }
        } else {
            foreach ($items as $item_id => $needed_quantity) {
                if ($items_to_warehouse[$item_id][$current_warehouse_id] < $needed_quantity) {
                    return false;
                }
            }
        }
        return true;
    }
    
    /**
     * 
     * Точка входа
     * 
     * @param array $items
     * @param string $city
     * @param bool $one_try 
     * @return array $result
     * 
     * */
    
    public function checkAvailability($items, $city, $one_try) {
        $result = array(
            "success"    => false,
            "store_name" => ""
        );
        // Если местоположение не найдено, то по дефолту приоритетнее Москва
        $this->city = array_key_exists($city, $this->location_id_to_store) ? $city : MOSCOW_LOCATION_ID;
        $this->one_try = $one_try;
        $items_to_warehouse = $this->getItemsToWarehouseAssignment(array_keys($items));
        if ($this->check($items, $items_to_warehouse)) {
            $result["success"] = true;
            $result["store_name"] = $this->location_id_to_store_name[$this->city];
        }
        return $result;
    }
}
?>