<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
/** @var CBitrixBasketComponent $component */
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME'],
);
$this->addExternalCss($templateData['TEMPLATE_THEME']);

$this->addExternalCss('/bitrix/templates/happia/js/fancybox/jquery.fancybox.css');
$this->addExternalJS('/bitrix/templates/happia/js/fancybox/jquery.fancybox.pack.js');

$curPage = $APPLICATION->GetCurPage().'?'.$arParams["ACTION_VARIABLE"].'=';
$arUrls = array(
	"delete" => $curPage."delete&id=#ID#",
	"delay" => $curPage."delay&id=#ID#",
	"add" => $curPage."add&id=#ID#",
);
unset($curPage);

$arBasketJSParams = array(
	'SALE_DELETE' => GetMessage("SALE_DELETE"),
	'SALE_DELAY' => GetMessage("SALE_DELAY"),
	'SALE_TYPE' => GetMessage("SALE_TYPE"),
	'TEMPLATE_FOLDER' => $templateFolder,
	'DELETE_URL' => $arUrls["delete"],
	'DELAY_URL' => $arUrls["delay"],
	'ADD_URL' => $arUrls["add"]
);
?>
<script type="text/javascript">
	var basketJSParams = <?=CUtil::PhpToJSObject($arBasketJSParams);?>
</script>
<?
$APPLICATION->AddHeadScript($templateFolder."/script.js");


if (strlen($arResult["ERROR_MESSAGE"]) <= 0) { ?>

	<div class="warranty">
		<ul>
			<li class="fancybox-various" href="#safety_payment">
				<i class="fa fa-lock"></i>
				<span>Безопасная оплата</span>
			</li>
			<li class="fancybox-various" href="#commission">
				<i class="fa icon-percent"></i>
				<span>Комиссия за наш счёт</span>
			</li>
			<li class="fancybox-various" href="#insured">
				<i class="fa fa-shield"></i>
				<span>Посылка застрахована</span>
			</li>
			<li class="fancybox-various" href="#payment_delivery">
				<i class="fa icon-cash"></i>
				<span>Оплата при получении</span>
			</li>
		</ul>
	</div>

	<div id="safety_payment" style="display:none;">Безопасная оплата</div>
	<div id="commission" style="display:none;">Комиссия за наш счет</div>
	<div id="insured" style="display:none;">Посылка застрахована</div>
	<div id="payment_delivery" style="display:none;">Оплата при получении</div>
	
	<div id="warning_message">
		<?
		if (!empty($arResult["WARNING_MESSAGE"]) && is_array($arResult["WARNING_MESSAGE"]))
		{
			foreach ($arResult["WARNING_MESSAGE"] as $v)
				ShowError($v);
		}
		?>
	</div>

	<h1 class="page-title">
		Мои покупки
		<small class="product-count">(N товаров)</small>
		<span class="cart-number"></span>
		<span class="cross-line red"></span>
	</h1>


	<?
	$normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
	$normalHidden = ($normalCount == 0) ? 'style="display:none;"' : '';

	$delayCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
	$delayHidden = ($delayCount == 0) ? 'style="display:none;"' : '';

	$subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
	$subscribeHidden = ($subscribeCount == 0) ? 'style="display:none;"' : '';

	$naCount = count($arResult["ITEMS"]["nAnCanBuy"]);
	$naHidden = ($naCount == 0) ? 'style="display:none;"' : '';
	?>

		<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form">
			<div id="basket_form_container">
				<div class="bx_ordercart">
					<div class="tabs">
						<span><?=GetMessage("SALE_ITEMS")?></span>
						<a href="javascript:void(0)" id="basket_toolbar_button" class="btn btn-default current" onclick="showBasketItemsList()"><?=GetMessage("SALE_BASKET_ITEMS")?><div id="normal_count" class="flat" style="display:none">&nbsp;(<?=$normalCount?>)</div></a>
						<a href="javascript:void(0)" id="basket_toolbar_button_delayed" class="btn btn-default" onclick="showBasketItemsList(2)" <?=$delayHidden?>><?=GetMessage("SALE_BASKET_ITEMS_DELAYED")?><div id="delay_count" class="flat">&nbsp;(<?=$delayCount?>)</div></a>
						<a href="javascript:void(0)" id="basket_toolbar_button_subscribed" class="btn btn-default" onclick="showBasketItemsList(3)" <?=$subscribeHidden?>><?=GetMessage("SALE_BASKET_ITEMS_SUBSCRIBED")?><div id="subscribe_count" class="flat">&nbsp;(<?=$subscribeCount?>)</div></a>
						<a href="javascript:void(0)" id="basket_toolbar_button_not_available" class="btn btn-default" onclick="showBasketItemsList(4)" <?=$naHidden?>><?=GetMessage("SALE_BASKET_ITEMS_NOT_AVAILABLE")?><div id="not_available_count" class="flat">&nbsp;(<?=$naCount?>)</div></a>
					</div>
					<?
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delayed.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_subscribed.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_not_available.php");
					?>
				</div>
			</div>
			<input type="hidden" name="BasketOrder" value="BasketOrder" />
			<!-- <input type="hidden" name="ajax_post" id="ajax_post" value="Y"> -->
		</form>
	<?
}
else
{
	ShowError($arResult["ERROR_MESSAGE"]);
}
?>