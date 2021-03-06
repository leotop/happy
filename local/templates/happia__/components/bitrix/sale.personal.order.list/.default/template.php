<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult['ERRORS']['FATAL'])):?>

	<?foreach($arResult['ERRORS']['FATAL'] as $error):?>
		<?=ShowError($error)?>
	<?endforeach?>

	<?$component = $this->__component;?>
	<?if($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED])):?>
		<?$APPLICATION->AuthForm('', false, false, 'N', false);?>
	<?endif?>

<?else:?>

	<?if(!empty($arResult['ERRORS']['NONFATAL'])):?>

		<?foreach($arResult['ERRORS']['NONFATAL'] as $error):?>
			<?=ShowError($error)?>
		<?endforeach?>

	<?endif?>

	<div class="bx_my_order_switch">

		<?$nothing = !isset($_REQUEST["filter_history"]) && !isset($_REQUEST["show_all"]);?>

		<?if($nothing || isset($_REQUEST["filter_history"])):?>
			<a class="btn btn-default" href="<?=$arResult["CURRENT_PAGE"]?>?show_all=Y">
				<i class="fa fa-list"></i> 
				<?=GetMessage('SPOL_ORDERS_ALL')?>
			</a>
		<?endif?>

		<?if($_REQUEST["filter_history"] == 'Y' || $_REQUEST["show_all"] == 'Y'):?>
			<a class="btn btn-default" href="<?=$arResult["CURRENT_PAGE"]?>?filter_history=N">
				<i class="fa fa-list"></i> 
				<?=GetMessage('SPOL_CUR_ORDERS')?>
			</a>
		<?endif?>

		<?if($nothing || $_REQUEST["filter_history"] == 'N' || $_REQUEST["show_all"] == 'Y'):?>
			<a class="btn btn-default" href="<?=$arResult["CURRENT_PAGE"]?>?filter_history=Y">
				<i class="fa fa-history"></i> 
				<?=GetMessage('SPOL_ORDERS_HISTORY')?>
			</a>
		<?endif?>

		<a class="btn btn-default" href="/personal/cart/">
			<i class="fa fa-shopping-cart"></i> 
			Посмотреть содержимое корзины
		</a>

		<a class="btn btn-default" href="/personal/profile/">
			<i class="fa fa-user"></i> 
			Изменить регистрационные данные
		</a>

	</div>

	<?if(!empty($arResult['ORDERS'])):?>

		<?foreach($arResult["ORDER_BY_STATUS"] as $key => $group):?>

			<?foreach($group as $k => $order):?>

				<?if(!$k):?>

					<div class="bx_my_order_status_desc">

						<h2><?=GetMessage("SPOL_STATUS")?> "<?=$arResult["INFO"]["STATUS"][$key]["NAME"] ?>"</h2>
						<div class="bx_mos_desc"><?=$arResult["INFO"]["STATUS"][$key]["DESCRIPTION"] ?></div>
						<span class="cross-line gray"></span>

					</div>

				<?endif?>

				<div class="bx_my_order">
					
					<div class="table-responsive">
						<table class="table bx_my_order_table">
							<thead>
								<tr>
									<th>
										<?=GetMessage('SPOL_ORDER')?> <?=GetMessage('SPOL_NUM_SIGN')?><?=$order["ORDER"]["ACCOUNT_NUMBER"]?>
										<?if(strlen($order["ORDER"]["DATE_INSERT_FORMATED"])):?>
											<?=GetMessage('SPOL_FROM')?> <?=$order["ORDER"]["DATE_INSERT_FORMATED"];?>
										<?endif?>
									</th>
									<th style="text-align: right;">
										<a href="<?=$order["ORDER"]["URL_TO_DETAIL"]?>"><?=GetMessage('SPOL_ORDER_DETAIL')?></a>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<strong><?=GetMessage('SPOL_PAY_SUM')?>:</strong> <?=$order["ORDER"]["FORMATED_PRICE"]?> <br />

										<strong><?=GetMessage('SPOL_PAYED')?>:</strong> <?=GetMessage('SPOL_'.($order["ORDER"]["PAYED"] == "Y" ? 'YES' : 'NO'))?> <br />

										<? // PAY SYSTEM ?>
										<? $paySystemList = array();?>
										<?foreach($order["PAYMENT"] as $payment):?>
											<?$paySystemList[] = $arResult['INFO']['PAY_SYSTEM'][$payment['PAY_SYSTEM_ID']]['NAME'];?>
										<?endforeach;?>

										<?if(!empty($paySystemList)):?>
											<strong><?=GetMessage('SPOL_PAYSYSTEM')?>:</strong> <?=implode(', ', $paySystemList)?> <br />
										<?endif?>

										<? // DELIVERY SYSTEM ?>
										<? $deliveryServiceList = array(); ?>
										<?foreach ($order['SHIPMENT'] as $shipment):?>
											<? $deliveryServiceList[] = $arResult['INFO']['DELIVERY'][$shipment['DELIVERY_ID']]['NAME'];?>
										<?endforeach;?>

										<?if(!empty($deliveryServiceList)):?>
											<strong><?=GetMessage('SPOL_DELIVERY')?>:</strong> <?=implode(', ', $deliveryServiceList)?> <br />
										<?endif?>

										<strong><?=GetMessage('SPOL_BASKET')?>:</strong>
										<ul class="bx_item_list">

											<?foreach ($order["BASKET_ITEMS"] as $item):?>

												<li>
													<?if(strlen($item["DETAIL_PAGE_URL"])):?>
														<a href="<?=$item["DETAIL_PAGE_URL"]?>" target="_blank">
													<?endif?>
														<?=$item['NAME']?>
													<?if(strlen($item["DETAIL_PAGE_URL"])):?>
														</a> 
													<?endif?>
													<nobr>&nbsp;&mdash; <?=$item['QUANTITY']?> <?=(isset($item["MEASURE_NAME"]) ? $item["MEASURE_NAME"] : GetMessage('SPOL_SHT'))?></nobr>
												</li>

											<?endforeach?>

										</ul>

									</td>
									<td>
										<?=$order["ORDER"]["DATE_STATUS_FORMATED"];?>
										<div class="bx_my_order_status <?=$arResult["INFO"]["STATUS"][$key]['COLOR']?><?/*yellow*/ /*red*/ /*green*/ /*gray*/?>"><?=$arResult["INFO"]["STATUS"][$key]["NAME"]?></div>

										<?if($order["ORDER"]["CANCELED"] != "Y"):?>
											<a href="<?=$order["ORDER"]["URL_TO_CANCEL"]?>" style="min-width:140px"class="bx_big bx_bt_button_type_2 bx_cart bx_order_action btn btn-danger"><?=GetMessage('SPOL_CANCEL_ORDER')?></a>
										<?endif?>

										<a href="<?=$order["ORDER"]["URL_TO_COPY"]?>" style="min-width:140px"class="bx_big bx_bt_button_type_2 bx_cart bx_order_action btn btn-success"><?=GetMessage('SPOL_REPEAT_ORDER')?></a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

				</div>

			<?endforeach?>

		<?endforeach?>

		<?if(strlen($arResult['NAV_STRING'])):?>
			<?=$arResult['NAV_STRING']?>
		<?endif?>

	<?else:?>
		<?=GetMessage('SPOL_NO_ORDERS')?>
	<?endif?>

<?endif?>