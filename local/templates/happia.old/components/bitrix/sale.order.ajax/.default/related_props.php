<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");

$style = (is_array($arResult["ORDER_PROP"]["RELATED"]) && count($arResult["ORDER_PROP"]["RELATED"])) ? "" : "display:none";
?>
<div class="bx_section onepage-checkout-step-related-props" style="<?=$style?>">
	<h2><span class="step-number"></span><?=GetMessage("SOA_TEMPL_RELATED_PROPS")?></h2>
	<br />
	<?=PrintPropsForm($arResult["ORDER_PROP"]["RELATED"], $arParams["TEMPLATE_LOCATION"])?>
</div>

<div class="bx_section onepage-checkout-step-comments">
	<h2><span class="step-number"></span><?=GetMessage("SOA_TEMPL_SUM_COMMENTS")?></h2>
	<div class="bx_block w100"><textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" style="max-width:100%;min-height:120px"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea></div>
	<input type="hidden" name="" value="">
	<div style="clear: both;"></div><br />
</div>