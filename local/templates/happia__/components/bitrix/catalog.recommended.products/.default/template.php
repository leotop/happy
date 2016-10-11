
<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);

$templateData = array(
    'TEMPLATE_THEME' => $this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css',
    'TEMPLATE_CLASS' => 'bx_' . $arParams['TEMPLATE_THEME']
);
CJSCore::Init(array("popup"));
$arSkuTemplate = array(); ?>

<? if (is_array($arResult['SKU_PROPS']))
{?>
    <?foreach ($arResult['SKU_PROPS'] as $iblockId => $skuProps)
    {
        $arSkuTemplate[$iblockId] = array();
        foreach ($skuProps as &$arProp)
        {
            ob_start();
            if ('TEXT' == $arProp['SHOW_MODE'])
            {
                if (5 < $arProp['VALUES_COUNT'])
                {
                    $strClass = 'bx_item_detail_size full';
                    $strWidth = ($arProp['VALUES_COUNT'] * 20) . '%';
                    $strOneWidth = (100 / $arProp['VALUES_COUNT']) . '%';
                    $strSlideStyle = '';
                }
                else
                {
                    $strClass = 'bx_item_detail_size';
                    $strWidth = '100%';
                    $strOneWidth = '20%';
                    $strSlideStyle = 'display: none;';
                }
                ?>
            <div class="<? echo $strClass; ?>" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_cont">
                <span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>

                <div class="bx_size_scroller_container">
                    <div class="bx_size">
                        <ul id="#ITEM#_prop_<? echo $arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;"><?
                            foreach ($arProp['VALUES'] as $arOneValue)
                            {
                                ?>
                            <li
                                data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID']; ?>"
                                data-onevalue="<? echo $arOneValue['ID']; ?>"
                                style="width: <? echo $strOneWidth; ?>;"
                                ><i></i><span class="cnt"><? echo htmlspecialcharsex($arOneValue['NAME']); ?></span>
                                </li><?
                            }
                            ?></ul>
                    </div>
                    <div class="bx_slide_left" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_left"
                        data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                    <div class="bx_slide_right" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_right"
                        data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                </div>
                </div><?
            }
            elseif ('PICT' == $arProp['SHOW_MODE'])
            {
                if (5 < $arProp['VALUES_COUNT'])
                {
                    $strClass = 'bx_item_detail_scu full';
                    $strWidth = ($arProp['VALUES_COUNT'] * 20) . '%';
                    $strOneWidth = (100 / $arProp['VALUES_COUNT']) . '%';
                    $strSlideStyle = '';
                }
                else
                {
                    $strClass = 'bx_item_detail_scu';
                    $strWidth = '100%';
                    $strOneWidth = '20%';
                    $strSlideStyle = 'display: none;';
                }
                ?>
            <div class="<? echo $strClass; ?>" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_cont">
                <span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>

                <div class="bx_scu_scroller_container">
                    <div class="bx_scu">
                        <ul id="#ITEM#_prop_<? echo $arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;"><?
                            foreach ($arProp['VALUES'] as $arOneValue)
                            {
                                ?>
                            <li
                                data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID'] ?>"
                                data-onevalue="<? echo $arOneValue['ID']; ?>"
                                style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>;"
                                ><i title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"></i>
                        <span class="cnt"><span class="cnt_item"
                                style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');"
                                title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"
                                ></span></span></li><?
                            }
                            ?></ul>
                    </div>
                    <div class="bx_slide_left" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_left"
                        data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                    <div class="bx_slide_right" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_right"
                        data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                </div>
                </div><?
            }
            $arSkuTemplate[$iblockId][$arProp['CODE']] = ob_get_contents();
            ob_end_clean();
            unset($arProp);
        }
    }
}

?>
<? if (!empty($arResult['ITEMS'])): ?>
	<ul>
	    <?
	    $elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
	    $elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
	    $elementDeleteParams = array('CONFIRM' => GetMessage('CATALOG_RECOMMENDED_PRODUCTS_TPL_ELEMENT_DELETE_CONFIRM'));
	    foreach ($arResult['ITEMS'] as $key => $arItem)
	    {
	        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $elementEdit);
	        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $elementDelete, $elementDeleteParams);
	        $strMainID = $this->GetEditAreaId($arItem['ID']);

	        $arItemIDs = array(
	            'ID' => $strMainID,
	            'PICT' => $strMainID . '_pict',
	            'SECOND_PICT' => $strMainID . '_secondpict',
	            'MAIN_PROPS' => $strMainID . '_main_props',

	            'QUANTITY' => $strMainID . '_quantity',
	            'QUANTITY_DOWN' => $strMainID . '_quant_down',
	            'QUANTITY_UP' => $strMainID . '_quant_up',
	            'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
	            'BUY_LINK' => $strMainID . '_buy_link',
	            'SUBSCRIBE_LINK' => $strMainID . '_subscribe',

	            'PRICE' => $strMainID . '_price',
	            'DSC_PERC' => $strMainID . '_dsc_perc',
	            'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',

	            'PROP_DIV' => $strMainID . '_sku_tree',
	            'PROP' => $strMainID . '_prop_',
	            'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
	            'BASKET_PROP_DIV' => $strMainID . '_basket_prop'
	        );

	        $strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

	        $strTitle = (
	        isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
	            ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
	            : $arItem['NAME']
	        );
	        $showImgClass = $arParams['SHOW_IMAGE'] != "Y" ? "no-imgs" : "";

	        ?>

	    <li>
		    <? if ($arParams['SHOW_IMAGE'] == "Y") { ?>
			    <a id="<? echo $arItemIDs['PICT']; ?>" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" class="product-image" title="<? echo $strTitle; ?>">
			    	<span></span>
		    		<? $thumb = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']["ID"], array('width'=>165, 'height'=>165), BX_RESIZE_IMAGE_EXACT, true); ?>
		        	<img src="<?=$thumb['src']?>" />
			        
			        <? if ($arItem['LABEL']) {
			            ?>
			            <div class="bx_stick average left top" title="<? echo $arItem['LABEL_VALUE']; ?>">
			            	<? echo $arItem['LABEL_VALUE']; ?>
			            </div>
			        <? }  ?>
			    </a>
		    <? } ?>

		    <? if ($arParams['SHOW_NAME'] == "Y") { ?>
		        <h4 class="product-name">
		        	<a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" title="<? echo $arItem['NAME']; ?>"><? echo $arItem['NAME']; ?></a>
		        </h4>
		    <? } ?>

		    <div class="price-box" id="<? echo $arItemIDs['PRICE']; ?>">
		        <? if (!empty($arItem['MIN_PRICE']))
		            {
		                if (isset($arItem['OFFERS']) && !empty($arItem['OFFERS']))
		                {
		                    echo GetMessage(
		                        'CATALOG_RECOMMENDED_PRODUCTS_TPL_MESS_PRICE_SIMPLE_MODE',
		                        array(
		                            '#PRICE#' => $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'],
		                            '#MEASURE#' => GetMessage(
		                                'CATALOG_RECOMMENDED_PRODUCTS_TPL_MESS_MEASURE_SIMPLE_MODE',
		                                array(
		                                    '#VALUE#' => $arItem['MIN_PRICE']['CATALOG_MEASURE_RATIO'],
		                                    '#UNIT#' => $arItem['MIN_PRICE']['CATALOG_MEASURE_NAME']
		                                )
		                            )
		                        )
		                    );
		                }
		                else
		                {
		                    echo $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'];
		                }
		                if ('Y' == $arParams['SHOW_OLD_PRICE'] && $arItem['MIN_PRICE']['DISCOUNT_VALUE'] < $arItem['MIN_PRICE']['VALUE'])
		                {
		                    ?> <span
		                    style="color: #a5a5a5;font-size: 12px;font-weight: normal;white-space: nowrap;text-decoration: line-through;"><? echo $arItem['MIN_PRICE']['PRINT_VALUE']; ?></span><?
		                }
		            }
		            ?>
		    </div>
		</li>

		<? }
	    unset($elementDeleteParams, $elementDelete, $elementEdit); ?>

    </ul>

    <script type="text/javascript">
        BX.message({
            MESS_BTN_BUY: '<? echo ('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('CATALOG_RECOMMENDED_PRODUCTS_TPL_MESS_BTN_BUY')); ?>',
            MESS_BTN_ADD_TO_BASKET: '<? echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('CATALOG_RECOMMENDED_PRODUCTS_TPL_MESS_BTN_ADD_TO_BASKET')); ?>',

            MESS_BTN_DETAIL: '<? echo ('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CATALOG_RECOMMENDED_PRODUCTS_TPL_MESS_BTN_DETAIL')); ?>',

            MESS_NOT_AVAILABLE: '<? echo ('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CATALOG_RECOMMENDED_PRODUCTS_TPL_MESS_BTN_DETAIL')); ?>',
            BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('CATALOG_RECOMMENDED_PRODUCTS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT'); ?>',
            BASKET_URL: '<? echo $arParams["BASKET_URL"]; ?>',
            ADD_TO_BASKET_OK: '<? echo GetMessageJS('CATALOG_RECOMMENDED_PRODUCTS_ADD_TO_BASKET_OK'); ?>',
            TITLE_ERROR: '<? echo GetMessageJS('CATALOG_RECOMMENDED_PRODUCTS_CATALOG_TITLE_ERROR') ?>',
            TITLE_BASKET_PROPS: '<? echo GetMessageJS('CATALOG_RECOMMENDED_PRODUCTS_CATALOG_TITLE_BASKET_PROPS') ?>',
            TITLE_SUCCESSFUL: '<? echo GetMessageJS('CATALOG_RECOMMENDED_PRODUCTS_ADD_TO_BASKET_OK'); ?>',
            BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CATALOG_RECOMMENDED_PRODUCTS_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
            BTN_MESSAGE_SEND_PROPS: '<? echo GetMessageJS('CATALOG_RECOMMENDED_PRODUCTS_CATALOG_BTN_MESSAGE_SEND_PROPS'); ?>',
            BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CATALOG_RECOMMENDED_PRODUCTS_CATALOG_BTN_MESSAGE_CLOSE') ?>'
        });
    </script>

<? endif ?>