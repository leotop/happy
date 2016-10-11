<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<div class="bx_my_order_cancel">
	<?if(strlen($arResult["ERROR_MESSAGE"])<=0):?>
		<h3>Отмена заказа #<?=$arResult["ACCOUNT_NUMBER"]?></h3>
		<div class="cross-line gray"></div>
		
		<form method="post" action="<?=POST_FORM_ACTION_URI?>" class="form-horizontal">
			
			<input type="hidden" name="CANCEL" value="Y">
			<?=bitrix_sessid_post()?>
			<input type="hidden" name="ID" value="<?=$arResult["ID"]?>">
			
			<p>
				<?=GetMessage("SALE_CANCEL_ORDER1") ?>
				<a href="<?=$arResult["URL_TO_DETAIL"]?>"><?=GetMessage("SALE_CANCEL_ORDER2")?> #<?=$arResult["ACCOUNT_NUMBER"]?></a>?
				<b><?= GetMessage("SALE_CANCEL_ORDER3") ?></b>
			</p>
			
			<lavel for="REASON_CANCELED" class="control-label margin-bottom"><?= GetMessage("SALE_CANCEL_ORDER4") ?>:</lavel>
			
			<div class="form-group">
				 <div class="col-xs-12 col-md-6">
					<textarea name="REASON_CANCELED" class="form-control" rows="7"></textarea>
				</div>
			</div>

			<div class="form-group">
				<div class="col-xs-12 col-md-6">
					<div class="row">
						 <div class="col-xs-6">
							<a href="<?=$arResult["URL_TO_LIST"]?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?=GetMessage("SALE_RECORDS_LIST")?></a>
						</div>
						<div class="col-xs-6">
							<input type="submit" name="action" value="<?=GetMessage("SALE_CANCEL_ORDER_BTN") ?>" class="btn btn-danger pull-right"/>
						</div>
					</div>
				</div>
			</div>

		</form>
	<?else:?>
		<?=ShowError($arResult["ERROR_MESSAGE"]);?>
	<?endif;?>

</div>