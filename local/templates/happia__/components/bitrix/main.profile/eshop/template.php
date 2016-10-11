<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
?>
<?=ShowError($arResult["strProfileError"]);?>
<?
if ($arResult['DATA_SAVED'] == 'Y')
	echo ShowNote(GetMessage('PROFILE_DATA_SAVED'));
?>
<div class="bx_profile bx_<?=$arResult["THEME"]?>">

	<div class="bx_my_order_switch">

		<?$nothing = !isset($_REQUEST["filter_history"]) && !isset($_REQUEST["show_all"]);?>

		<?if($nothing || isset($_REQUEST["filter_history"])):?>
			<a class="bx_mo_link btn btn-default" href="/personal/order/?show_all=Y">
				<i class="fa fa-list"></i> 
				Посмотреть все заказы
			</a>
		<?endif?>

		<?if($_REQUEST["filter_history"] == 'Y' || $_REQUEST["show_all"] == 'Y'):?>
			<a class="bx_mo_link btn btn-default" href="/personal/order/?filter_history=N">
				<i class="fa fa-list"></i> 
				Посмотреть текущие заказы
			</a>
		<?endif?>

		<?if($nothing || $_REQUEST["filter_history"] == 'N' || $_REQUEST["show_all"] == 'Y'):?>
			<a class="bx_mo_link btn btn-default" href="/personal/order/?filter_history=Y">
				<i class="fa fa-history"></i> 
				Посмотреть историю заказов
			</a>
		<?endif?>

		<a class="bx_mo_link btn btn-default" href="/personal/cart/">
			<i class="fa fa-shopping-cart"></i> 
			Посмотреть содержимое корзины
		</a>

	</div>


	<form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>?" enctype="multipart/form-data">
		<?=$arResult["BX_SESSION_CHECK"]?>
		<input type="hidden" name="lang" value="<?=LANG?>" />
		<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
		<input type="hidden" name="LOGIN" value=<?=$arResult["arUser"]["LOGIN"]?> />
		<input type="hidden" name="EMAIL" value=<?=$arResult["arUser"]["EMAIL"]?> />

		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<h3><?=GetMessage("LEGEND_PROFILE")?></h3>
				<div class="cross-line gray"></div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<h3><?=GetMessage("MAIN_PSWD")?></h3>
				<div class="cross-line gray"></div>
			</div>
		</div>


		<div class="row">

			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="NAME" class="control-label"><?=GetMessage('NAME')?></label>
					<input class="form-control" type="text" name="NAME" maxlength="50" value="<?=$arResult["arUser"]["NAME"]?>" />
				</div>

				<div class="form-group">
					<label for="LAST_NAME" class="control-label"><?=GetMessage('LAST_NAME')?></label>
					<input class="form-control" type="text" name="LAST_NAME" maxlength="50" value="<?=$arResult["arUser"]["LAST_NAME"]?>" />
				</div>

				<div class="form-group">
					<label for="SECOND_NAME" class="control-label"><?=GetMessage('SECOND_NAME')?></label>
					<input class="form-control" type="text" name="SECOND_NAME" maxlength="50"  value="<?=$arResult["arUser"]["SECOND_NAME"]?>" />
				</div>
			</div>

			<div class="col-xs-12 col-sm-6">
				
				<div class="form-group">
					<label for="NEW_PASSWORD" class="control-label"><?=GetMessage('NEW_PASSWORD_REQ')?></label>
					<input class="form-control" type="password" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="off" />
				</div>

				<div class="form-group">
					<label for="NEW_PASSWORD_CONFIRM" class="control-label"><?=GetMessage('NEW_PASSWORD_CONFIRM')?></label>
					<input class="form-control" type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off" />
				</div>

				<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
					<h2><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></h2>
					<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
						<div class="form-group">
							<label for="" class="control-label"><?=$arUserField["EDIT_FORM_LABEL"]?><?if ($arUserField["MANDATORY"]=="Y"):?><span class="starrequired">*</span><?endif;?></label>
							<?$APPLICATION->IncludeComponent(
								"bitrix:system.field.edit",
								$arUserField["USER_TYPE"]["USER_TYPE_ID"],
								array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField), null, array("HIDE_ICONS"=>"Y")
							);?>
						</div>
					<?endforeach;?>
				<?endif;?>
			</div>

		</div>

		<input name="save" value="<?=GetMessage("MAIN_SAVE")?>" class="btn btn-secondary bx_bt_button bx_big shadow" type="submit">
	</form>
</div>
<br>
<?
if($arResult["SOCSERV_ENABLED"])
{
	$APPLICATION->IncludeComponent("bitrix:socserv.auth.split", ".default", array(
			"SHOW_PROFILES" => "Y",
			"ALLOW_DELETE" => "Y"
		),
		false
	);
}
?>