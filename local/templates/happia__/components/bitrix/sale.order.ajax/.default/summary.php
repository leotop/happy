<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
$bPropsColumn = false;
$bUseDiscount = false;
$bPriceType = false;
$bShowNameWithPicture = ($bDefaultColumns) ? true : false; // flat to show name and picture column in one column
?>
<div class="bx_ordercart onepage-checkout-cart">
	<div class="block-title">
	    <h2><?=GetMessage("SALE_PRODUCTS_SUMMARY");?></h2>
	</div>
	<ul>
		<?foreach ($arResult["GRID"]["ROWS"] as $k => $arData):?>
		<li>
			<div class="checkout-product-image">
				<? if ($bShowNameWithPicture): ?>
				
					<?
					if (strlen($arData["data"]["PREVIEW_PICTURE_SRC"]) > 0):
						$url = $arData["data"]["PREVIEW_PICTURE_SRC"];
					elseif (strlen($arData["data"]["DETAIL_PICTURE_SRC"]) > 0):
						$url = $arData["data"]["DETAIL_PICTURE_SRC"];
					else:
						$url = $templateFolder."/images/no_photo.png";
					endif;

					if (strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arData["data"]["DETAIL_PAGE_URL"] ?>"><?endif;?>
						<img src='<?=$url?>' alt="" />
					<?if (strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
					<?
					if (!empty($arData["data"]["BRAND"])):
					?>
						<div class="bx_ordercart_brand">
							<img alt="" src="<?=$arData["data"]["BRAND"]?>" />
						</div>
					<?
					endif;
					?>
				<? endif; ?>
			</div>

			<div class="checkout-product-item">
				<? foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn):

					$class = ($arColumn["id"] == "PRICE_FORMATED") ? "price" : "";

					if (in_array($arColumn["id"], array("PROPS", "TYPE", "NOTES"))) // some values are not shown in columns in this template
						continue;

					if ($arColumn["id"] == "PREVIEW_PICTURE" && $bShowNameWithPicture)
						continue;

					$arItem = (isset($arData["columns"][$arColumn["id"]])) ? $arData["columns"] : $arData["data"]; ?>

					<? if ($arColumn["id"] == "NAME"): ?>

						<h2 class="product-name">
							<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"] ?>"><?endif;?>
								<?=$arItem["NAME"]?>
							<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
						</h2>

						
						<? if ($bPropsColumn): ?>
							<div class="item-options">
								<? foreach ($arItem["PROPS"] as $val):
									echo '<div class="item-option">'.$val["NAME"].":&nbsp;<span>".$val["VALUE"]."<span></div>";
								endforeach; ?>
							</div>>
						<? endif; ?>
							
						<? if (is_array($arItem["SKU_DATA"])):
							foreach ($arItem["SKU_DATA"] as $propId => $arProp):

								// is image property
								$isImgProperty = false;
								foreach ($arProp["VALUES"] as $id => $arVal)
								{
									if (isset($arVal["PICT"]) && !empty($arVal["PICT"]))
									{
										$isImgProperty = true;
										break;
									}
								}

								$full = (count($arProp["VALUES"]) > 5) ? "full" : "";

								if ($isImgProperty): // iblock element relation property
								?>
									<div class="bx_item_detail_scu_small_noadaptive <?=$full?>">

										<span class="bx_item_section_name_gray">
											<?=$arProp["NAME"]?>:
										</span>

										<div class="bx_scu_scroller_container">

											<div class="bx_scu">
												<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>" style="width: 200%;margin-left:0%;">
												<?
												foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

													$selected = "";
													foreach ($arItem["PROPS"] as $arItemProp):
														if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
														{
															if ($arItemProp["VALUE"] == $arSkuValue["NAME"])
																$selected = "class=\"bx_active\"";
														}
													endforeach;
												?>
													<li style="width:10%;" <?=$selected?>>
														<a href="javascript:void(0);">
															<span style="background-image:url(<?=$arSkuValue["PICT"]["SRC"]?>)"></span>
														</a>
													</li>
												<?
												endforeach;
												?>
												</ul>
											</div>

											<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
											<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
										</div>

									</div>
								<?
								else:
								?>
									<div class="bx_item_detail_size_small_noadaptive <?=$full?>">

										<span class="bx_item_section_name_gray">
											<?=$arProp["NAME"]?>:
										</span>

										<div class="bx_size_scroller_container">
											<div class="bx_size">
												<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>" style="width: 200%; margin-left:0%;">
													<?
													foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

														$selected = "";
														foreach ($arItem["PROPS"] as $arItemProp):
															if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
															{
																if ($arItemProp["VALUE"] == $arSkuValue["NAME"])
																	$selected = "class=\"bx_active\"";
															}
														endforeach;
													?>
														<li style="width:10%;" <?=$selected?>>
															<a href="javascript:void(0);"><?=$arSkuValue["NAME"]?></a>
														</li>
													<?
													endforeach;
													?>
												</ul>
											</div>
											<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
											<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
										</div>

									</div>
								<?
								endif;
							endforeach;
						endif; ?>

					<? elseif ($arColumn["id"] == "PRICE_FORMATED"): ?>
						
						<div class="sub-price">
							<div class="current_price">
								<span><?=getColumnName($arColumn)?>:</span>
								<?=$arItem["PRICE_FORMATED"]?>
							</div>
							<div class="old_price right">
								<?
								if (doubleval($arItem["DISCOUNT_PRICE"]) > 0):
									echo SaleFormatCurrency($arItem["PRICE"] + $arItem["DISCOUNT_PRICE"], $arItem["CURRENCY"]);
									$bUseDiscount = true;
								endif;
								?>
							</div>

							<?if ($bPriceType && strlen($arItem["NOTES"]) > 0):?>
								<div style="text-align: left">
									<div class="type_price"><?=GetMessage("SALE_TYPE")?></div>
									<div class="type_price_value"><?=$arItem["NOTES"]?></div>
								</div>
							<?endif;?>
						</div>

					<? elseif ($arColumn["id"] == "DISCOUNT"): ?>


					<? elseif ($arColumn["id"] == "DETAIL_PICTURE" && $bPreviewPicture): ?>
						
						<div class="itemphoto">
							<div class="bx_ordercart_photo_container">
								<?
								$url = "";
								if ($arColumn["id"] == "DETAIL_PICTURE" && strlen($arData["data"]["DETAIL_PICTURE_SRC"]) > 0)
									$url = $arData["data"]["DETAIL_PICTURE_SRC"];

								if ($url == "")
									$url = $templateFolder."/images/no_photo.png";

								if (strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arData["data"]["DETAIL_PAGE_URL"] ?>"><?endif;?>
									<div class="bx_ordercart_photo" style="background-image:url('<?=$url?>')"></div>
								<?if (strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
							</div>
						</div>

					<? elseif ($arColumn["id"] == "SUM"): ?>
						
						<div class="price">
							<?=$arItem[$arColumn["id"]]?>
						</div>

					<? elseif (in_array($arColumn["id"], array("QUANTITY", "WEIGHT_FORMATED", "DISCOUNT_PRICE_PERCENT_FORMATED"))): ?>
						
						<div class="custom">
							<span><?=getColumnName($arColumn)?>:</span>
							<?=$arItem[$arColumn["id"]]?>
						</div>
				
					<? else: // some property value

						if (is_array($arItem[$arColumn["id"]])): ?>
							<div class="custom other">
								<span><?=getColumnName($arColumn)?>:</span>
								<?
								foreach ($arItem[$arColumn["id"]] as $arValues):
									if ($arValues["type"] == "image"):
									?>
										<div class="bx_ordercart_photo_container">
											<div class="bx_ordercart_photo" style="background-image:url('<?=$arValues["value"]?>')"></div>
										</div>
									<?
									else: // not image
										echo $arValues["value"]."<br/>";
									endif;
								endforeach;
								?>
							</div>
						<? else: // not array, but simple value ?>
							<div class="custom" style="<?=$columnStyle?>">
								<span><?=getColumnName($arColumn)?>:</span>
								<?
									echo $arItem[$arColumn["id"]];
								?>
							</div>
						<? endif;
					endif;

				endforeach; ?>
			</div>
		</li>
		<?endforeach;?>
	</ul>

	<div class="onepage-checkout-step-totals">
		<table class="totals">
			<tbody>
				<?
				if (floatval($arResult['ORDER_WEIGHT']) > 0):
				?>
				<tr>
					<td class="title" colspan="<?=$colspan?>"><?=GetMessage("SOA_TEMPL_SUM_WEIGHT_SUM")?></td>
					<td class="price"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></td>
				</tr>
				<?
				endif;
				?>
				<tr>
					<td class="title itog <?=($bUseDiscount?'with_discount' :'')?>" colspan="<?=$colspan?>"><?=GetMessage("SOA_TEMPL_SUM_SUMMARY")?></td>
					<?
					if ($bUseDiscount)
					{
						?>
							<td class="price">
								<?=$arResult["ORDER_PRICE_FORMATED"]?><br/><span style="text-decoration:line-through; color:#828282;"><?=$arResult["PRICE_WITHOUT_DISCOUNT"]?></span></td>
						<?
					}
					else
					{
						?>
						<td class="price"><?=$arResult["ORDER_PRICE_FORMATED"]?></td>
						<?
					}
					?>
				</tr>
				<?
				if (doubleval($arResult["DISCOUNT_PRICE"]) > 0)
				{
					?>
					<tr>
						<td class="title" colspan="<?=$colspan?>"><?=GetMessage("SOA_TEMPL_SUM_DISCOUNT")?><?if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"])>0):?> (<?echo $arResult["DISCOUNT_PERCENT_FORMATED"];?>)<?endif;?>:</td>
						<td class="price"><?echo $arResult["DISCOUNT_PRICE_FORMATED"]?></td>
					</tr>
					<?
				}
				if(!empty($arResult["TAX_LIST"]))
				{
					foreach($arResult["TAX_LIST"] as $val)
					{
						?>
						<tr>
							<td class="title" colspan="<?=$colspan?>" class="itog"><?=$val["NAME"]?> <?=$val["VALUE_FORMATED"]?>:</td>
							<td class="price"><?=$val["VALUE_MONEY_FORMATED"]?></td>
						</tr>
						<?
					}
				}
				if (doubleval($arResult["DELIVERY_PRICE"]) > 0)
				{
					?>
					<tr>
						<td class="title" colspan="<?=$colspan?>"><?=GetMessage("SOA_TEMPL_SUM_DELIVERY")?></td>
						<td class="price"><?=$arResult["DELIVERY_PRICE_FORMATED"]?></td>
					</tr>
				<?
				}

				if (strlen($arResult["PAYED_FROM_ACCOUNT_FORMATED"]) > 0)
				{
					?>
					<tr>
						<td class="title" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_IT")?></td>
						<td class="price" class="price"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></td>
					</tr>
					<tr>
						<td class="title" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_PAYED")?></td>
						<td class="price" class="price"><?=$arResult["PAYED_FROM_ACCOUNT_FORMATED"]?></td>
					</tr>
					<tr>
						<td class="title fwb" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_LEFT_TO_PAY")?></td>
						<td class="price fwb" class="price"><?=$arResult["ORDER_TOTAL_LEFT_TO_PAY_FORMATED"]?></td>
					</tr>
				<?
				}
				else
				{
					?>
					<tr>
						<td class="title fwb" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_IT")?></td>
						<td class="price fwb" class="price"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></td>
					</tr>
				<?
				}
				?>
			</tbody>
		</table>
	</div>
</div>
