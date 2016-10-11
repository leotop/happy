<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$templateLibrary = array('popup');
$currencyList = '';
if (!empty($arResult['CURRENCIES']))
{
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = array(
    'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
    'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
    'ID' => $strMainID,
    'PICT' => $strMainID.'_pict',
    'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
    'STICKER_ID' => $strMainID.'_sticker',
    'BIG_SLIDER_ID' => $strMainID.'_big_slider',
    'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
    'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
    'SLIDER_LIST' => $strMainID.'_slider_list',
    'SLIDER_LEFT' => $strMainID.'_slider_left',
    'SLIDER_RIGHT' => $strMainID.'_slider_right',
    'OLD_PRICE' => $strMainID.'_old_price',
    'PRICE' => $strMainID.'_price',
    'DISCOUNT_PRICE' => $strMainID.'_price_discount',
    'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
    'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
    'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
    'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
    'QUANTITY' => $strMainID.'_quantity',
    'QUANTITY_DOWN' => $strMainID.'_quant_down',
    'QUANTITY_UP' => $strMainID.'_quant_up',
    'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
    'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
    'BASIS_PRICE' => $strMainID.'_basis_price',
    'BUY_LINK' => $strMainID.'_buy_link',
    'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
    'BASKET_ACTIONS' => $strMainID.'_basket_actions',
    'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
    'COMPARE_LINK' => $strMainID.'_compare_link',
    'PROP' => $strMainID.'_prop_',
    'PROP_DIV' => $strMainID.'_skudiv',
    'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
    'OFFER_GROUP' => $strMainID.'_set_group_',
    'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;

$strTitle = (
    isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"] != ''
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
    : $arResult['NAME']
);

$strAlt = (
    isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
    : $arResult['NAME']
);
$quantity = $arResult['CATALOG_QUANTITY'] 
?>

<h1 class="product-name">
	<?echo (
        isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
        ? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
        : $arResult["NAME"]
    );?>
	<small class="sku">Артикул: <?=$arResult['PROPERTIES']['ARTNUMBER']['VALUE'] ?></small>
	<span class="cross-line red"></span>
</h1>


<div class="bx_item_detail product-essential">
	<div id="<? echo $arItemIDs['ID']; ?>">

		<? reset($arResult['MORE_PHOTO']); ?>
		<? $arFirstPhoto = current($arResult['MORE_PHOTO']); ?>

	     

		<!-- IMAGES -->
	    <div class="product-img-box" id="<? echo $arItemIDs['BIG_SLIDER_ID']; ?>">
			
			<div class="product-image">
					
					<div class="image" id="<? echo $arItemIDs['BIG_IMG_CONT_ID']; ?>">
						
						<img id="<? echo $arItemIDs['PICT']; ?>" src="<? echo $arFirstPhoto['SRC']; ?>" alt="<? echo $strAlt; ?>" title="<? echo $strTitle; ?>">

						<? if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']) {
						    if (!isset($arResult['OFFERS']) || empty($arResult['OFFERS'])) {
						        if (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF']) { ?>
						    		<div class="bx_stick_disc right bottom" id="<? echo $arItemIDs['DISCOUNT_PICT_ID'] ?>"><? echo -$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>%</div>
								<? }
						    }
						    else { ?>
						    	<div class="bx_stick_disc right bottom" id="<? echo $arItemIDs['DISCOUNT_PICT_ID'] ?>" style="display: none;"></div>
							<? }
						}

						if ($arResult['LABEL']) { ?>
						    <div class="bx_stick average left top" id="<? echo $arItemIDs['STICKER_ID'] ?>" title="<? echo $arResult['LABEL_VALUE']; ?>"><? echo $arResult['LABEL_VALUE']; ?></div>
						<? } ?>

				</div>
			</div>  <!-- END .product-image -->

			<? if ($arResult['SHOW_SLIDER']):
			    if (!isset($arResult['OFFERS']) || empty($arResult['OFFERS'])):
			        
			        // add video to images count
					if($arResult['PROPERTIES']['VIDEO']['VALUE']) {
						$arResult['MORE_PHOTO_COUNT'] += 1;
					}

			        if (5 < $arResult['MORE_PHOTO_COUNT']) {
			            $strClass = 'bx_slider_conteiner full';
			            $strOneWidth = (100/$arResult['MORE_PHOTO_COUNT']).'%';
			            $strWidth = (20*$arResult['MORE_PHOTO_COUNT']).'%';
			            $strSlideStyle = '';
			        }
			        else {
			            $strClass = 'bx_slider_conteiner';
			            $strOneWidth = '20%';
			            $strWidth = '100%';
			            $strSlideStyle = 'display: none;';
			        } ?>

			    	<div class="<? echo $strClass; ?> jcarousel-wrapper more-views" id="<? echo $arItemIDs['SLIDER_CONT_ID']; ?>">
			    		<div class="jcarousel jcarousel-more-views" id="jcarousel-more-views-<? echo $arItemIDs['SLIDER_LIST']; ?>">
		    				<ul id="<? echo $arItemIDs['SLIDER_LIST']; ?>">
								<? if($arResult['PROPERTIES']['VIDEO']['VALUE']) { ?>
									<li>
										<a class="cnt fancybox-various fancybox.iframe" rel="images" 
										   href="http://www.youtube.com/embed/<? echo $arResult['PROPERTIES']['VIDEO']['VALUE']?>?autoplay=1">
											<img src="<? echo 'http://img.youtube.com/vi/' . $arResult['PROPERTIES']['VIDEO']['VALUE'] . '/default.jpg' ?>" width="180"/>
										</a>
									</li>			
								<? }?>
								<? foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto) { ?>
									<? $thumb = CFile::ResizeImageGet($arOnePhoto["ID"], array('width'=>180, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true);?>
		    						<li data-value="<? echo $arOnePhoto['ID']; ?>">
		    							<img src="<? echo $thumb['src']; ?>" width="180" height="180"/>
		    						</li>
								<? }
		        				unset($arOnePhoto); ?>
		    				</ul>
			    		</div>
			    		<a href="#" class="jcarousel-control-prev" id="<? echo $arItemIDs['SLIDER_LEFT']; ?>"><i class="fa fa-angle-up"></i></a>
		    			<a href="#" class="jcarousel-control-next" id="<? echo $arItemIDs['SLIDER_RIGHT']; ?>"><i class="fa fa-angle-down"></i></a>
			    	</div>

				<? else:
			        foreach ($arResult['OFFERS'] as $key => $arOneOffer):
			            if (!isset($arOneOffer['MORE_PHOTO_COUNT']) || 0 >= $arOneOffer['MORE_PHOTO_COUNT'])
			                continue;
			           
			            $strVisible = ($key == $arResult['OFFERS_SELECTED'] ? '' : 'none');

			            // add video to images count
						if($arResult['PROPERTIES']['VIDEO']['VALUE']) {
							$arResult['MORE_PHOTO_COUNT'] += 1;
						}

			            if (5 < $arOneOffer['MORE_PHOTO_COUNT']) {
			                $strClass = 'bx_slider_conteiner full';
			                $strOneWidth = (100/$arOneOffer['MORE_PHOTO_COUNT']).'%';
			                $strWidth = (20*$arOneOffer['MORE_PHOTO_COUNT']).'%';
			                $strSlideStyle = '';
			            }
			            else {
			                $strClass = 'bx_slider_conteiner';
			                $strOneWidth = '20%';
			                $strWidth = '100%';
			                $strSlideStyle = 'display: none;';
			            } ?>

			   			<div class="<? echo $strClass; ?> jcarousel-wrapper more-views" id="<? echo $arItemIDs['SLIDER_CONT_OF_ID'].$arOneOffer['ID']; ?>" style="display: <? echo $strVisible; ?>;">
			    			<div class="jcarousel jcarousel-more-views" id="jcarousel-more-views-<? echo $arItemIDs['SLIDER_LIST_OF_ID'].$arOneOffer['ID']; ?>">
		    					<ul id="<? echo $arItemIDs['SLIDER_LIST_OF_ID'].$arOneOffer['ID']; ?>">
									<? if($arResult['PROPERTIES']['VIDEO']['VALUE']) { ?>
										<li>
											<a class="cnt fancybox-various fancybox.iframe" rel="images" 
											   href="http://www.youtube.com/embed/<? echo $arResult['PROPERTIES']['VIDEO']['VALUE']?>?autoplay=1">
												<img src="<? echo 'http://img.youtube.com/vi/' . $arResult['PROPERTIES']['VIDEO']['VALUE'] . '/default.jpg' ?>" width="180"/>
											</a>
										</li>			
									<? } ?>
									<? foreach ($arOneOffer['MORE_PHOTO'] as &$arOnePhoto) { ?>
										<? $thumb = CFile::ResizeImageGet($arOneOffer["ID"], array('width'=>180, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true);?>
		    							<li data-value="<? echo $arOneOffer['ID'].'_'.$arOnePhoto['ID']; ?>">
		    								<img src="<? echo $thumb['src']; ?>" width="180" height="180"/>
		    							</li>
									<? }
		            				unset($arOnePhoto); ?>
		    					</ul>
			    			</div>
			    			<a href="#" class="jcarousel-control-prev" id="<? echo $arItemIDs['SLIDER_LEFT_OF_ID'].$arOneOffer['ID'] ?>"><i class="fa fa-angle-up"></i></a>
		    				<a href="#" class="jcarousel-control-next" id="<? echo $arItemIDs['SLIDER_RIGHT_OF_ID'].$arOneOffer['ID'] ?>"><i class="fa fa-angle-down"></i></a>
			    		</div>

					<? endforeach;
			    endif;
			endif; ?>
	    </div> 
	    <!-- END IMAGES -->



	   
	    <div class="product-shop">

			<!-- PRICE -->
			<div class="price-box">
				<? $minPrice = (isset($arResult['RATIO_PRICE']) ? $arResult['RATIO_PRICE'] : $arResult['MIN_PRICE']);
				$boolDiscountShow = (0 < $minPrice['DISCOUNT_DIFF']);
				
				if ($arParams['SHOW_OLD_PRICE'] == 'Y') { ?>
			    	<div class="item_old_price" id="<? echo $arItemIDs['OLD_PRICE']; ?>" style="display: <? echo($boolDiscountShow ? '' : 'none'); ?>"><? echo($boolDiscountShow ? $minPrice['PRINT_VALUE'] : ''); ?></div>
				<? } ?>

			    <div id="<? echo $arItemIDs['PRICE']; ?>"><? echo $minPrice['PRINT_DISCOUNT_VALUE']; ?></div>

				<? if ($arParams['SHOW_OLD_PRICE'] == 'Y'): ?>
				    <div class="item_economy_price" id="<? echo $arItemIDs['DISCOUNT_PRICE']; ?>" style="display: <? echo($boolDiscountShow ? '' : 'none'); ?>">
				    	<? echo($boolDiscountShow ? GetMessage('CT_BCE_CATALOG_ECONOMY_INFO', array('#ECONOMY#' => $minPrice['PRINT_DISCOUNT_DIFF'])) : ''); ?>
				    </div>
				<? endif; ?>
				<? unset($minPrice); ?>
			</div> 
			<!-- END PRICE -->


	    	 <!-- VOTES -->
			<? $useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']); ?>
			<? if ($useVoteRating): ?>
			    <div class="ratings">
			        <?$APPLICATION->IncludeComponent(
			            "bitrix:iblock.vote",
			            "stars",
			            array(
			                "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
			                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
			                "ELEMENT_ID" => $arResult['ID'],
			                "ELEMENT_CODE" => "",
			                "MAX_VOTE" => "5",
			                "VOTE_NAMES" => array("1", "2", "3", "4", "5"),
			                "SET_STATUS_404" => "N",
			                "DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
			                "CACHE_TYPE" => $arParams['CACHE_TYPE'],
			                "CACHE_TIME" => $arParams['CACHE_TIME']
			            ),
			            $component,
			            array("HIDE_ICONS" => "Y")
			        );?>
			    </div>
			<? endif; ?>
			<? unset($useVoteRating); ?>
			<!-- END VOTES -->



			<!-- OFFERS -->
			<? if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP'])):
			    $arSkuProps = array(); ?>

				<div class="item_info_section product-options" style="padding-right:150px;" id="<? echo $arItemIDs['PROP_DIV']; ?>">
					<? foreach ($arResult['SKU_PROPS'] as &$arProp):
				       
				        if (!isset($arResult['OFFERS_PROP'][$arProp['CODE']]))
				            continue;

				        $arSkuProps[] = array(
				            'ID' => $arProp['ID'],
				            'SHOW_MODE' => $arProp['SHOW_MODE'],
				            'VALUES_COUNT' => $arProp['VALUES_COUNT']
				        );

				        if ('TEXT' == $arProp['SHOW_MODE']): ?>
				            
				            <? if (5 < $arProp['VALUES_COUNT']) {
				                $strClass = 'bx_item_detail_size full';
				                $strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				                $strWidth = (20*$arProp['VALUES_COUNT']).'%';
				                $strSlideStyle = '';
				            }
				            else {
				                $strClass = 'bx_item_detail_size';
				                $strOneWidth = '20%';
				                $strWidth = '100%';
				                $strSlideStyle = 'display: none;';
				            } ?>

						    <div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
						        <span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
						        
						        <div class="bx_size_scroller_container">
						        	<div class="bx_size">
						            	<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
											<? foreach ($arProp['VALUES'] as $arOneValue):
						                		$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']); ?>
												<li data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID']; ?>" data-onevalue="<? echo $arOneValue['ID']; ?>" style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>; display: none;">
													<i title="<? echo $arOneValue['NAME']; ?>"></i>
													<span class="cnt" title="<? echo $arOneValue['NAME']; ?>"><? echo $arOneValue['NAME']; ?></span>
												</li>
											<? endforeach; ?>
			            				</ul>
			            			</div>
						            <div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
						            <div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
			        			</div>
			        			<!-- END .bx_size_scroller_container -->
			    			</div>

						<? elseif ('PICT' == $arProp['SHOW_MODE']): ?>

				            <? if (5 < $arProp['VALUES_COUNT']) {
				                $strClass = 'bx_item_detail_scu full';
				                $strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				                $strWidth = (20*$arProp['VALUES_COUNT']).'%';
				                $strSlideStyle = '';
				            }
				            else {
				                $strClass = 'bx_item_detail_scu';
				                $strOneWidth = '20%';
				                $strWidth = '100%';
				                $strSlideStyle = 'display: none;';
				            } ?>

						    <div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
						        <span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
						        <div class="bx_scu_scroller_container">
						        	<div class="bx_scu">
						            	<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
											<? foreach ($arProp['VALUES'] as $arOneValue)  {
						               			$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']); ?>
												<li data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID'] ?>" data-onevalue="<? echo $arOneValue['ID']; ?>" style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>; display: none;">
													<i title="<? echo $arOneValue['NAME']; ?>"></i>
													<span class="cnt sku-item"><span class="cnt_item" style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');" title="<? echo $arOneValue['NAME']; ?>"></span></span>
												</li>
											<? } ?>
						            	</ul>
						            </div>
						            <div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
						            <div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
						        </div>
						        <!-- END .bx_scu_scroller_container -->
						    </div>
						<? endif; ?>
			    	<? endforeach; ?>
			    	<? unset($arProp); ?>
				</div>
				<!-- END .item_info_section -->
			<? endif; ?>
			<!-- END OFFERS -->


			<!-- BUTTONS -->
			<div class="add-to-box">
				<div class="add-to-cart">
				
					<? if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
					    $canBuy = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CAN_BUY'];
					}
					else {
					    $canBuy = $arResult['CAN_BUY'];
					}

					$buyBtnMessage = ($arParams['MESS_BTN_BUY'] != '' ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));
					$addToBasketBtnMessage = ($arParams['MESS_BTN_ADD_TO_BASKET'] != '' ? $arParams['MESS_BTN_ADD_TO_BASKET'] : GetMessage('CT_BCE_CATALOG_ADD'));
					$notAvailableMessage = ($arParams['MESS_NOT_AVAILABLE'] != '' ? $arParams['MESS_NOT_AVAILABLE'] : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE'));
					$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
					$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
					$showSubscribeBtn = false;
					$compareBtnMessage = ($arParams['MESS_BTN_COMPARE'] != '' ? $arParams['MESS_BTN_COMPARE'] : GetMessage('CT_BCE_CATALOG_COMPARE')); ?>

					<? if ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'): ?>
				        <? if($canBuy): ?>
					        <div class="input-group product-qty">
					            <input id="<? echo $arItemIDs['QUANTITY']; ?>" type="text" class="form-control qty" value="<? echo (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
					                    ? 1
					                    : $arResult['CATALOG_MEASURE_RATIO']
					                ); ?>">
					            <a href="javascript:void(0)" class="input-group-addon product-plus" id="<? echo $arItemIDs['QUANTITY_UP']; ?>">
					            	<i class="fa fa-plus"></i>
					            </a>
					             <a href="javascript:void(0)" class="input-group-addon product-minus" id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>">
					             	<i class="fa fa-minus"></i>
					             </a>
					        </div>
				    	<? endif; ?>

				        <span id="<? echo $arItemIDs['BASKET_ACTIONS']; ?>" style="display: <? echo ($canBuy ? '' : 'none'); ?>;">
							<? if ($showBuyBtn) { ?>
					            <a href="javascript:void(0);" class="btn btn-primary btn-cart btn-lg" id="<? echo $arItemIDs['BUY_LINK']; ?>"><? echo $buyBtnMessage; ?></a>
					        <? }

					    	if ($showAddBtn) { ?>
					            <a href="javascript:void(0);" class="btn btn-primary btn-cart btn-lg" id="<? echo $arItemIDs['ADD_BASKET_LINK']; ?>"><? echo $addToBasketBtnMessage; ?></a>
							<? } ?>
				        </span>
				        <span id="<? echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="notavailable" style="display: <? echo (!$canBuy ? '' : 'none'); ?>;"><? echo $notAvailableMessage; ?></span>

						<? if ($arParams['DISPLAY_COMPARE'] || $showSubscribeBtn) { ?>
				        	<? if ($arParams['DISPLAY_COMPARE']) { ?>
				            	<a href="javascript:void(0);" class="btn btn-default" id="<? echo $arItemIDs['COMPARE_LINK']; ?>"><? echo $compareBtnMessage; ?></a>
							<? }

			       			if ($showSubscribeBtn) { } ?>
						<? } ?>

						<? if ('Y' == $arParams['SHOW_MAX_QUANTITY']) {
					        if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) { ?>
					    		<p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>" style="display: none;"><? echo GetMessage('OSTATOK'); ?>: <span></span></p>
							<? }
					        else {
					            if ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO']) { ?>
					    			<p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>"><? echo GetMessage('OSTATOK'); ?>: <span><? echo $arResult['CATALOG_QUANTITY']; ?></span></p>
								<? }
					        }
					    } ?>
					<? else: ?>
				        <span id="<? echo $arItemIDs['BASKET_ACTIONS']; ?>" style="display: <? echo ($canBuy ? '' : 'none'); ?>;">
				        	<? if ($showBuyBtn) { ?>
				            	<a href="javascript:void(0);" class="btn btn-primary btn-cart btn-lg" id="<? echo $arItemIDs['BUY_LINK']; ?>"><? echo $buyBtnMessage; ?></a>
				            <? }
				    		if ($showAddBtn) { ?>
				        		<a href="javascript:void(0);" class="btn btn-primary btn-cart btn-lg" id="<? echo $arItemIDs['ADD_BASKET_LINK']; ?>"><? echo $addToBasketBtnMessage; ?></a>
				        	<? } ?>
				        </span>

				        <span id="<? echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="notavailable" style="display: <? echo (!$canBuy ? '' : 'none'); ?>;"><? echo $notAvailableMessage; ?></span>

						<? if ($arParams['DISPLAY_COMPARE'] || $showSubscribeBtn) { ?>
			    			<? if ($arParams['DISPLAY_COMPARE']) { ?>
			        			<a href="javascript:void(0);" class="btn btn-default" id="<? echo $arItemIDs['COMPARE_LINK']; ?>"><? echo $compareBtnMessage; ?></a>
			    			<? }
			    			if ($showSubscribeBtn) { } ?>
						<? } ?>
					<? endif; ?>

					<? unset($showAddBtn, $showBuyBtn); ?>

				</div>
			</div>
			<!-- END BUTTONS -->

			
			<!-- ONE CLICK -->
			<? if($quantity > 0): ?>
				<div class="oneclick">
	                <span title="Наш оператор вам перезвонит">Купить в 1 клик</span> 
	            </div>
	        <? endif; ?>
	        <!-- END ONE CLICK -->



	        <!-- VK SHARE -->
	        <div class="want-present">
	            <?
	                // $url = $APPLICATION->GetCurPageParam();
	           		$path = 'http://'.$_SERVER['SERVER_NAME'];
	            ?>
	            <span class="social-share" data-type="vk" data-image="<?=$path.$img['src'];?>" data-title="Хочу в подарок: TODO NAME" data-text="TODO COMPLECTATION"><?php echo GetMessage('WANT_PRESENT'); ?></span>
	        </div>
	        <!-- END VK SHARE -->



			<!-- WHAREHOUSE -->
			<div class="product-warehouse">
				<div class="quantity title">Наличие в магазинах:</div>
				<? $APPLICATION->IncludeComponent(
						"bitrix:catalog.store.amount", ".default", 
						array(
							"ELEMENT_ID" => $arResult['ID'],
							"STORE_PATH" => $arParams['STORE_PATH'],
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "36000",
							"MAIN_TITLE" => $arParams['MAIN_TITLE'],
							"USE_MIN_AMOUNT" => "N",
							"MIN_AMOUNT" => $arParams['MIN_AMOUNT'],
							"STORES" => $arParams['STORES'],
							"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
							"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
							"USER_FIELDS" => $arParams['USER_FIELDS'],
							"FIELDS" => $arParams['FIELDS']
						),
						$component,
						array("HIDE_ICONS" => "Y")
					);
				?>
			</div>
			<!-- END WHAREHOUSE -->


			<div class="product-add">
				
				<div class="description">
					<!-- PREVIEW_TEXT -->
					<? if ('' != $arResult['PREVIEW_TEXT']): ?>
					    <? if ('S' == $arParams['DISPLAY_PREVIEW_TEXT_MODE'] || ('E' == $arParams['DISPLAY_PREVIEW_TEXT_MODE'])): ?>
								<? echo ('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>'); ?>
						<? endif; ?>
					<? else: ?>
						<!-- DETAIL_TEXT -->
						<? if ('' != $arResult['DETAIL_TEXT']) { ?>
					        <? if ('html' == $arResult['DETAIL_TEXT_TYPE']) {
					       		echo $arResult['DETAIL_TEXT'];
						    }
						    else { ?>
					        	<p><? echo $arResult['DETAIL_TEXT']; ?></p>
					        <? } ?>
						<? } ?>
				        <!-- END DETAIL_TEXT -->				
					<? endif; ?>
					<!-- END PREVIEW_TEXT -->



		        </div>


				<div class="complectation-composition">
					<!-- Tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active">
							<a href="#complectation" aria-controls="complectation" role="tab" data-toggle="tab">
								<?=GetMessage('COMPLECTATION')?>
							</a>
						</li>
						<li role="presentation">
							<a href="#composition" aria-controls="composition" role="tab" data-toggle="tab">
								<?=GetMessage('COMPOSITION')?>
							</a>
						</li>
						<li role="presentation">
							<a href="#delivery" aria-controls="delivery" role="tab" data-toggle="tab">
								<?=GetMessage('DELIVERY')?>
							</a>
						</li>
					</ul>

					<!-- Tabs content -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="complectation">
							TODO Вывести комплектацию
						</div>

						<div role="tabpanel" class="tab-pane" id="composition">
							<?if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS']) { ?>
								TODO Вывести состав
								<? //echo $arResult['PROPERTIES']['MANUFACTURER']['VALUE'];?>
								<? if (!empty($arResult['DISPLAY_PROPERTIES'])) { ?>
									<? foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp) { ?>
										<div>
											<strong><? echo $arOneProp['NAME'].': '; ?></strong>
											<? echo (is_array($arOneProp['DISPLAY_VALUE'])
												? implode(' / ', $arOneProp['DISPLAY_VALUE'])
												: $arOneProp['DISPLAY_VALUE']
											); ?>
										</div>
									<? }
									unset($arOneProp); ?>
								<? }

								if ($arResult['SHOW_OFFERS_PROPS']) { ?>
									<dl id="<? echo $arItemIDs['DISPLAY_PROP_DIV'] ?>" style="display: none;"></dl>
								<? } ?>
							<? } ?>
						</div>

						<div role="tabpanel" class="tab-pane" id="delivery">
							TODO Вывести доставку
						</div>
					</div>
				 </div>

			</div>
			<!-- END .product-add -->
		</div>
		<!-- END .product-shop -->
		




		<!-- OFFER_GROUP_VALUES -->
	    <div class="bx_md">
	    	<div class="item_info_section">
				<? if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])): ?>

				    <? if ($arResult['OFFER_GROUP']): ?>
				        <? foreach ($arResult['OFFER_GROUP_VALUES'] as $offerID): ?>
				    		<span id="<? echo $arItemIDs['OFFER_GROUP'].$offerID; ?>" style="display: none;">
								<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
								    ".default",
								    array(
								        "IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
								        "ELEMENT_ID" => $offerID,
								        "PRICE_CODE" => $arParams["PRICE_CODE"],
								        "BASKET_URL" => $arParams["BASKET_URL"],
								        "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
								        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
								        "CACHE_TIME" => $arParams["CACHE_TIME"],
								        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
								        "TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
								        "CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
								        "CURRENCY_ID" => $arParams["CURRENCY_ID"]
								    ),
								    $component,
								    array("HIDE_ICONS" => "Y")
								);?>
				    		</span>
						<? endforeach; ?>
				    <? endif; ?>

				<? else: ?>

				    <? if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP']): ?>
						<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
						    ".default",
						    array(
						        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
						        "ELEMENT_ID" => $arResult["ID"],
						        "PRICE_CODE" => $arParams["PRICE_CODE"],
						        "BASKET_URL" => $arParams["BASKET_URL"],
						        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
						        "CACHE_TIME" => $arParams["CACHE_TIME"],
						        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						        "TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
						        "CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
						        "CURRENCY_ID" => $arParams["CURRENCY_ID"]
						    ),
						    $component,
						    array("HIDE_ICONS" => "Y")
						);?>
					<? endif; ?>

				<? endif; ?>
			</div>
	    </div>
	    <!-- END OFFER_GROUP_VALUES -->

	</div>
</div>
<!-- END .product-essential -->


<? if(!empty($arResult['PROPERTIES']['LINKS_RELATED']['VALUE'])):?>
	<div class="box-collateral box-related">
		<h2><?=$arResult['PROPERTIES']['LINKS_RELATED']['NAME']?></h2>

		<? $IBLOCK_ID = CIBlockElement::GetIBlockByID($arResult['ID']); ?>

		<div class="jcarousel-wrapper">
		    <div class="jcarousel jcarousel-related">
				<?$APPLICATION->IncludeComponent(
					"bitrix:catalog.recommended.products",
					".default",
					Array(
						"ACTION_VARIABLE" => "action",
						"ADDITIONAL_PICT_PROP_2" => "MORE_PHOTO2",
						"ADDITIONAL_PICT_PROP_3" => "",
						"ADD_PROPERTIES_TO_BASKET" => "Y",
						"BASKET_URL" => "/personal/basket.php",
						"CACHE_TIME" => "86400",
						"CACHE_TYPE" => "A",
						"CART_PROPERTIES_2" => array(0=>"",1=>"",),
						"CART_PROPERTIES_3" => array(),
						"CODE" => $_REQUEST["PRODUCT_CODE"],
						"COMPONENT_TEMPLATE" => ".default",
						"CONVERT_CURRENCY" => "N",
						"DETAIL_URL" => "",
						"HIDE_NOT_AVAILABLE" => "N",
						"IBLOCK_ID" => $IBLOCK_ID,
						"IBLOCK_TYPE" => "catalog",
						"ID" => $arResult['ID'],
						"LABEL_PROP_2" => "-",
						"LABEL_PROP_3" => "",
						"LINE_ELEMENT_COUNT" => "3",
						"MESS_BTN_BUY" => "Купить",
						"MESS_BTN_DETAIL" => "Подробнее",
						"MESS_BTN_SUBSCRIBE" => "Подписаться",
						"MESS_NOT_AVAILABLE" => "Нет в наличии",
						"OFFERS_PROPERTY_LINK" => "LINKS_RELATED",
						"PAGE_ELEMENT_COUNT" => "30",
						"PARTIAL_PRODUCT_PROPERTIES" => "N",
						"PRICE_CODE" => array(0=>"BASE",),
						"PRICE_VAT_INCLUDE" => "Y",
						"PRODUCT_ID_VARIABLE" => "id",
						"PRODUCT_PROPS_VARIABLE" => "prop",
						"PRODUCT_QUANTITY_VARIABLE" => "quantity",
						"PRODUCT_SUBSCRIPTION" => "N",
						"PROPERTY_CODE_".$IBLOCK_ID => array(),
						"PROPERTY_LINK" => "LINKS_RELATED",
						"SHOW_DISCOUNT_PERCENT" => "N",
						"SHOW_IMAGE" => "Y",
						"SHOW_NAME" => "Y",
						"SHOW_OLD_PRICE" => "N",
						"SHOW_PRICE_COUNT" => "1",
						"SHOW_PRODUCTS_".$IBLOCK_ID => "Y",
						"TEMPLATE_THEME" => "blue",
						"USE_PRODUCT_QUANTITY" => "N"
					)
				);?>
			</div>
		    <a href="#" class="jcarousel-control-prev">‹</a>
		    <a href="#" class="jcarousel-control-next">›</a>
	    </div>

	    <span class="cross-line red"></span>
	</div>
<? endif; ?>


<? if(!empty($arResult['PROPERTIES']['LINKS_UPSELL']['VALUE'])):?>
	<div class="box-collateral box-up-sell">
		<h2><?=$arResult['PROPERTIES']['LINKS_UPSELL']['NAME']?></h2>

		<div class="jcarousel-wrapper">
		    <div class="jcarousel jcarousel-upsell">
				<?$APPLICATION->IncludeComponent(
					"bitrix:catalog.recommended.products",
					".default",
					Array(
						"ACTION_VARIABLE" => "action",
						"ADDITIONAL_PICT_PROP_2" => "MORE_PHOTO2",
						"ADDITIONAL_PICT_PROP_3" => "",
						"ADD_PROPERTIES_TO_BASKET" => "Y",
						"BASKET_URL" => "/personal/basket.php",
						"CACHE_TIME" => "86400",
						"CACHE_TYPE" => "A",
						"CART_PROPERTIES_2" => array(0=>"",1=>"",),
						"CART_PROPERTIES_3" => array(),
						"CODE" => $_REQUEST["PRODUCT_CODE"],
						"COMPONENT_TEMPLATE" => ".default",
						"CONVERT_CURRENCY" => "N",
						"DETAIL_URL" => "",
						"HIDE_NOT_AVAILABLE" => "N",
						"IBLOCK_ID" => $IBLOCK_ID,
						"IBLOCK_TYPE" => "catalog",
						"ID" => $arResult['ID'],
						"LABEL_PROP_2" => "-",
						"LABEL_PROP_3" => "",
						"LINE_ELEMENT_COUNT" => "3",
						"MESS_BTN_BUY" => "Купить",
						"MESS_BTN_DETAIL" => "Подробнее",
						"MESS_BTN_SUBSCRIBE" => "Подписаться",
						"MESS_NOT_AVAILABLE" => "Нет в наличии",
						"OFFERS_PROPERTY_LINK" => "LINKS_UPSELL",
						"PAGE_ELEMENT_COUNT" => "30",
						"PARTIAL_PRODUCT_PROPERTIES" => "N",
						"PRICE_CODE" => array(0=>"BASE",),
						"PRICE_VAT_INCLUDE" => "Y",
						"PRODUCT_ID_VARIABLE" => "id",
						"PRODUCT_PROPS_VARIABLE" => "prop",
						"PRODUCT_QUANTITY_VARIABLE" => "quantity",
						"PRODUCT_SUBSCRIPTION" => "N",
						"PROPERTY_CODE_".$IBLOCK_ID => array(0=>"",1=>"",),
						"PROPERTY_LINK" => "LINKS_UPSELL",
						"SHOW_DISCOUNT_PERCENT" => "N",
						"SHOW_IMAGE" => "Y",
						"SHOW_NAME" => "Y",
						"SHOW_OLD_PRICE" => "N",
						"SHOW_PRICE_COUNT" => "1",
						"SHOW_PRODUCTS_".$IBLOCK_ID => "Y",
						"TEMPLATE_THEME" => "blue",
						"USE_PRODUCT_QUANTITY" => "N"
					)
				);?>
			</div>
		    <a href="#" class="jcarousel-control-prev">‹</a>
		    <a href="#" class="jcarousel-control-next">›</a>
	    </div>
	</div>
<? endif; ?>


<? if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
    foreach ($arResult['JS_OFFERS'] as &$arOneJS) {
        if ($arOneJS['PRICE']['DISCOUNT_VALUE'] != $arOneJS['PRICE']['VALUE']) {
            $arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'];
            $arOneJS['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'];
        }
        $strProps = '';
        
        if ($arResult['SHOW_OFFERS_PROPS']) {
            if (!empty($arOneJS['DISPLAY_PROPERTIES']))  {
                foreach ($arOneJS['DISPLAY_PROPERTIES'] as $arOneProp) {
                    $strProps .= '<dt>'.$arOneProp['NAME'].'</dt><dd>'.(
                        is_array($arOneProp['VALUE'])
                        ? implode(' / ', $arOneProp['VALUE'])
                        : $arOneProp['VALUE']
                    ).'</dd>';
                }
            }
        }

        $arOneJS['DISPLAY_PROPERTIES'] = $strProps;
    }

    if (isset($arOneJS))
        unset($arOneJS);

    $arJSParams = array(
        'CONFIG' => array(
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => true,
            'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y'),
            'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] == 'Y'),
            'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
            'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
            'OFFER_GROUP' => $arResult['OFFER_GROUP'],
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
            'SHOW_BASIS_PRICE' => ($arParams['SHOW_BASIS_PRICE'] == 'Y'),
            'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y')
        ),
        'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
        'VISUAL' => array(
            'ID' => $arItemIDs['ID'],
        ),
        'DEFAULT_PICTURE' => array(
            'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
            'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
        ),
        'PRODUCT' => array(
            'ID' => $arResult['ID'],
            'NAME' => $arResult['~NAME']
        ),
        'BASKET' => array(
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'BASKET_URL' => $arParams['BASKET_URL'],
            'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
        ),
        'OFFERS' => $arResult['JS_OFFERS'],
        'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
        'TREE_PROPS' => $arSkuProps
    );

    if ($arParams['DISPLAY_COMPARE']) {
        $arJSParams['COMPARE'] = array(
            'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
            'COMPARE_PATH' => $arParams['COMPARE_PATH']
        );
    }
}
else {
    $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);

    if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties) { ?>

		<div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
			<? if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])) {
            	foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) { ?>
    				<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
					<? if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
                    	unset($arResult['PRODUCT_PROPERTIES'][$propID]);
            	}
       		}

        	$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);

        	if (!$emptyProductProperties) { ?>
    			<table>
					<? foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo) { ?>
    					<tr>
    						<td><? echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
    						<td>
    							<? if('L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE'] && 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']) {
                    				foreach($propInfo['VALUES'] as $valueID => $value) { ?>
                       					<label><input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?></label><br>
                       				<? }
                				}
				                else { ?>
				                    <select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]">
				                    	<? foreach($propInfo['VALUES'] as $valueID => $value) { ?>
				                        	<option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option>
				                        <? }  ?>
				                    </select>
				                <? } ?>
    						</td>
    					</tr>
					<? } ?>
    			</table>
			<? } ?>
		</div>
	<? }

    if ($arResult['MIN_PRICE']['DISCOUNT_VALUE'] != $arResult['MIN_PRICE']['VALUE']) {
        $arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'];
        $arResult['MIN_BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arResult['MIN_BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'];
    }

    $arJSParams = array(
        'CONFIG' => array(
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => (isset($arResult['MIN_PRICE']) && !empty($arResult['MIN_PRICE']) && is_array($arResult['MIN_PRICE'])),
            'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y'),
            'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] == 'Y'),
            'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
            'SHOW_BASIS_PRICE' => ($arParams['SHOW_BASIS_PRICE'] == 'Y'),
            'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y')
        ),
        'VISUAL' => array(
            'ID' => $arItemIDs['ID'],
        ),
        'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
        'PRODUCT' => array(
            'ID' => $arResult['ID'],
            'PICT' => $arFirstPhoto,
            'NAME' => $arResult['~NAME'],
            'SUBSCRIPTION' => true,
            'PRICE' => $arResult['MIN_PRICE'],
            'BASIS_PRICE' => $arResult['MIN_BASIS_PRICE'],
            'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
            'SLIDER' => $arResult['MORE_PHOTO'],
            'CAN_BUY' => $arResult['CAN_BUY'],
            'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
            'QUANTITY_FLOAT' => is_double($arResult['CATALOG_MEASURE_RATIO']),
            'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
            'STEP_QUANTITY' => $arResult['CATALOG_MEASURE_RATIO'],
        ),
        'BASKET' => array(
            'ADD_PROPS' => ($arParams['ADD_PROPERTIES_TO_BASKET'] == 'Y'),
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
            'EMPTY_PROPS' => $emptyProductProperties,
            'BASKET_URL' => $arParams['BASKET_URL'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
        )
    );

    if ($arParams['DISPLAY_COMPARE']) {
        $arJSParams['COMPARE'] = array(
            'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
            'COMPARE_PATH' => $arParams['COMPARE_PATH']
        );
    }
    unset($emptyProductProperties);
} ?>


<script type="text/javascript">
	var <? echo $strObName; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
	BX.message({
	    ECONOMY_INFO_MESSAGE: '<? echo GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO'); ?>',
	    BASIS_PRICE_MESSAGE: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_BASIS_PRICE') ?>',
	    TITLE_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR') ?>',
	    TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS') ?>',
	    BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
	    BTN_SEND_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS'); ?>',
	    BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT') ?>',
	    BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE'); ?>',
	    BTN_MESSAGE_CLOSE_POPUP: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP'); ?>',
	    TITLE_SUCCESSFUL: '<? echo GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK'); ?>',
	    COMPARE_MESSAGE_OK: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK') ?>',
	    COMPARE_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR') ?>',
	    COMPARE_TITLE: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE') ?>',
	    BTN_MESSAGE_COMPARE_REDIRECT: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT') ?>',
	    SITE_ID: '<? echo SITE_ID; ?>'
	});
</script>