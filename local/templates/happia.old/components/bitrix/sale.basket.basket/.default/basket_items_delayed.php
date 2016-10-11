<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bDelayColumn  = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn  = false;
?>
<div id="basket_items_delayed" class="" style="display:none">
	<table id="delayed_items" class="cart-table">
		<thead>
			<tr>
				<? foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
					$arHeader["name"] = (isset($arHeader["name"]) ? (string)$arHeader["name"] : '');
					if ($arHeader["name"] == '')
						$arHeader["name"] = GetMessage("SALE_".$arHeader["id"]);
					$arHeaders[] = $arHeader["id"];

					// remember which values should be shown not in the separate columns, but inside other columns
					if (in_array($arHeader["id"], array("TYPE")))
					{
						$bPriceType = true;
						continue;
					}
					elseif ($arHeader["id"] == "PROPS")
					{
						$bPropsColumn = true;
						continue;
					}
					elseif ($arHeader["id"] == "DELAY")
					{
						$bDelayColumn = true;
						continue;
					}
					elseif ($arHeader["id"] == "DELETE")
					{
						$bDeleteColumn = true;
						continue;
					}
					elseif ($arHeader["id"] == "WEIGHT")
					{
						$bWeightColumn = true;
					}

					if ($arHeader["id"] == "NAME"): ?>
						<th class="item" colspan="2" id="col_<?=$arHeader["id"];?>"><?=$arHeader["name"]; ?></th>
					<? elseif ($arHeader["id"] == "PRICE"): ?>
						<th class="cart-price" id="col_<?=$arHeader["id"];?>"><?=$arHeader["name"]; ?></th>
					<? elseif ($arHeader["id"] == "QUANTITY"): ?>
						<th class="cart-qty" id="col_<?=$arHeader["id"];?>"><?=$arHeader["name"]; ?></th>
					<? endif; ?>
				<? endforeach;?>
			</tr>
		</thead>

		<tbody>
			<?
			foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):

				if ($arItem["DELAY"] == "Y" && $arItem["CAN_BUY"] == "Y"):
			?>
				<tr id="<?=$arItem["ID"]?>">
					<?
					foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):

						if (in_array($arHeader["id"], array("PROPS", "DELAY", "DELETE", "TYPE"))) // some values are not shown in columns in this template
							continue;

						if ($arHeader["id"] == "NAME"):
						?>
							<td class="cart-image">
								<?
								if (strlen($arItem["PREVIEW_PICTURE_SRC"]) > 0):
									$url = $arItem["PREVIEW_PICTURE_SRC"];
								elseif (strlen($arItem["DETAIL_PICTURE_SRC"]) > 0):
									$url = $arItem["DETAIL_PICTURE_SRC"];
								else:
									$url = $templateFolder."/images/no_photo.png";
								endif;
								?>
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"] ?>"><?endif;?>
									<img src="<?=$url?>"/>
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
								<?
								if (!empty($arItem["BRAND"])):
								?>
								<div class="bx_ordercart_brand">
									<img alt="" src="<?=$arItem["BRAND"]?>" />
								</div>
								<?
								endif;
								?>
							</td>

							<td class="cart-product-title">
								<p class="cart-product-name">
									<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"] ?>"><?endif;?>
										<?=$arItem["NAME"]?>
									<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
								</p>
								<div class="bx_ordercart_itemart">
									<?
									if ($bPropsColumn):
										foreach ($arItem["PROPS"] as $val):

											if (is_array($arItem["SKU_DATA"]))
											{
												$bSkip = false;
												foreach ($arItem["SKU_DATA"] as $propId => $arProp)
												{
													if ($arProp["CODE"] == $val["CODE"])
													{
														$bSkip = true;
														break;
													}
												}
												if ($bSkip)
													continue;
											}

											echo $val["NAME"].":&nbsp;<span>".$val["VALUE"]."<span><br/>";
										endforeach;
									endif;
									?>
								</div>
								<?
								if (is_array($arItem["SKU_DATA"])):
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
											$countValues = count($arProp["VALUES"]);
											$full = ($countValues > 5) ? "full" : "";

											if ($isImgProperty):
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
																		if ($arItemProp["VALUE"] == $arSkuValue["NAME"] || $arItemProp["VALUE"] == $arSkuValue["XML_ID"])
																			$selected = ' class="bx_active"';
																	}
																endforeach;
															?>
																<li style="width:10%;"<?=$selected?>>
																	<a href="javascript:void(0)" class="cnt"><span class="cnt_item" style="background-image:url(<?=$arSkuValue["PICT"]["SRC"]?>"></span></a>
																</li>
															<?
															endforeach;
															?>
															</ul>
														</div>

														<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>, <?=$countValues?>);"></div>
														<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>, <?=$countValues?>);"></div>
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
																				$selected = ' class="bx_active"';
																		}
																	endforeach;
																?>
																	<li style="width:10%;"<?=$selected?>>
																		<a href="javascript:void(0);" class="cnt"><?=$arSkuValue["NAME"]?></a>
																	</li>
																<?
																endforeach;
																?>
															</ul>
														</div>
														<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>, <?=$countValues?>);"></div>
														<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>, <?=$countValues?>);"></div>
													</div>

												</div>
											<?
											endif;
										endforeach;
								endif;
								?>
								<input type="hidden" name="DELAY_<?=$arItem["ID"]?>" value="Y"/>
							</td>
						<?
						elseif ($arHeader["id"] == "QUANTITY"):
						?>
							<td class="cart-qty">
								<?echo $arItem["QUANTITY"];
									if (isset($arItem["MEASURE_TEXT"]))
										echo "&nbsp;".$arItem["MEASURE_TEXT"];
								?>

								<div class="btn-add-to-basket"><i class="fa fa-shopping-cart"></i> <a href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["add"])?>" title="<?=GetMessage("SALE_ADD_TO_BASKET")?>"><?=GetMessage("SALE_ADD_TO_BASKET")?></a></div>

								<? if ($bDeleteColumn): ?>
						        	<div class="btn-remove"><i class="fa fa-trash-o"></i> <a href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delete"])?>" title="<?=GetMessage("SALE_DELETE")?>"><?=GetMessage("SALE_DELETE")?></a></div>
								<? endif; ?>
							</td>
						<?
						elseif ($arHeader["id"] == "PRICE"):
						?>
							<td class="cart-price">
								<?if (doubleval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0):?>
									<div class="current_price"><?=$arItem["PRICE_FORMATED"]?></div>
									<div class="old_price"><?=$arItem["FULL_PRICE_FORMATED"]?></div>
								<?else:?>
									<div class="current_price"><?=$arItem["PRICE_FORMATED"];?></div>
								<?endif?>
							</td>
						<?
						elseif ($arHeader["id"] == "SUM"):
							?>
								<td class="cart-subtotal">
									<?
									if ($arHeader["id"] == "SUM"):
									?>
										<div id="sum_<?=$arItem["ID"]?>">
									<?
									endif;

									echo $arItem[$arHeader["id"]];

									if ($arHeader["id"] == "SUM"):
									?>
										</div>
									<?
									endif;
									?>
								</td>
						<?
						
						endif;
					endforeach;
					?>
				</tr>
				<?
				endif;
			endforeach;
			?>
		</tbody>

	</table>
</div>
<?