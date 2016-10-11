<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$this->IncludeLangFile('template.php');

$cartId = $arParams['cartId'];

require(realpath(dirname(__FILE__)).'/top_template.php');

if ($arParams["SHOW_PRODUCTS"] == "Y" && $arResult['NUM_PRODUCTS'] > 0) { ?>
	<div class="cart-details" style="display:none">

		<?if ($arParams["POSITION_FIXED"] == "Y"):?>
			<div id="<?=$cartId?>status" class="bx-basket-item-list-action" onclick="<?=$cartId?>.toggleOpenCloseCart()"><?=GetMessage("TSB1_COLLAPSE")?></div>
		<?endif?>

		<?foreach ($arResult["CATEGORIES"] as $category => $items):
			if (empty($items))
				continue;
			?>
			<?foreach ($items as $v):?>
				<div class="cart-top-item">
					
					<? // item picture ?>
					<?if ($arParams["SHOW_IMAGE"] == "Y" && $v["PICTURE_SRC"]):?>
						<?if($v["DETAIL_PAGE_URL"]):?>
							<a href="<?=$v["DETAIL_PAGE_URL"]?>"><img src="<?=$v["PICTURE_SRC"]?>" width="115px" height="120px" alt="<?=$v["NAME"]?>"></a>
						<?else:?>
							<img src="<?=$v["PICTURE_SRC"]?>" width="115px" height="120px" alt="<?=$v["NAME"]?>" />
						<?endif?>
					<?else:?>
						<img src="<?=SITE_TEMPLATE_PATH.'/images/np_product_main.gif';?>" width="115px" height="120px" alt="<?=$v["NAME"]?>">
					<?endif?>

					<? // right part with item info ?>
					<div class="cart-top-info">
						
						<? // title ?>
						<p class="cart-top-name">
							<?if ($v["DETAIL_PAGE_URL"]):?>
								<a href="<?=$v["DETAIL_PAGE_URL"]?>"><?=$v["NAME"]?></a>
							<?else:?>
								<?=$v["NAME"]?>
							<?endif?>
						</p>

						<? // quantity, size, color --- TODO ?>
						<div class="cart-top-options">
							<p class="cart-top-option">
								<label><?= GetMessage("TSBS_QUANTITY") ?></label>
								<span><?echo $v["QUANTITY"] . ' ' . GetMessage("QUANTITY_NAME")?></span>
							</p>
						</div>

						<? // price ?>
						<?if (true): ?>
							<p class="cart-top-price">
								<?if ($arParams["SHOW_PRICE"] == "Y"):?>
									<?=$v["PRICE_FMT"]?>
									<?if ($v["FULL_PRICE"] != $v["PRICE_FMT"]):?>
										<span class="price-old"><?=$v["FULL_PRICE"]?></span>
									<?endif?>
								<?endif?>
							</p>
						<?endif?>

						<? // remove button ?>
						<div class="cart-rm-icon" onclick="<?=$cartId?>.removeItemFromCart(<?=$v['ID']?>)" title="<?=GetMessage("TSB1_DELETE")?>"><i class="fa fa-times"></i></div>

					</div>

				</div>
			<?endforeach?>
		<?endforeach?>

		<? // buttons to basket and order ?>
		<?if($arParams["PATH_TO_ORDER"] && $arResult["CATEGORIES"]["READY"]):?>
			<div class="cart-top-buttons">
				<a href="<?=$arParams["PATH_TO_BASKET"]?>" class="btn btn-primary top-to-cart"><?=GetMessage("TSB1_2BASKET")?></a>
				<a href="<?=$arParams["PATH_TO_ORDER"]?>" class="btn btn-secondary top-to-checkout"><?=GetMessage("TSB1_2ORDER")?></a>
			</div>
		<?endif?>


	</div>

	<script>
		BX.ready(function(){
			<?=$cartId?>.fixCart();
		});
	</script>
<? } ?>
