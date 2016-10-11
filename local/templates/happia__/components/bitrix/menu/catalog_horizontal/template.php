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
/** @var CBitrixComponent $component */

$this->setFrameMode(true);

if (empty($arResult["ALL_ITEMS"]))
	return;

CUtil::InitJSCore();

if (file_exists($_SERVER["DOCUMENT_ROOT"].$this->GetFolder().'/themes/'.$arParams["MENU_THEME"].'/colors.css'))
	$APPLICATION->SetAdditionalCSS($this->GetFolder().'/themes/'.$arParams["MENU_THEME"].'/colors.css');

$menuBlockId = "catalog_menu_".$this->randString();
?>

<div class="bx-top-nav offcanvas main-menu" id="<?=$menuBlockId?>">
	<nav class="bx-top-nav-container" id="cont_<?=$menuBlockId?>">
		<div class="catalog-title">Каталог</div>
		<ul class="bx-nav-list-1-lvl" id="ul_<?=$menuBlockId?>">
			<?foreach($arResult["MENU_STRUCTURE"] as $itemID => $arColumns):?>
				<?$existPictureDescColomn = ($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["picture_src"] || $arResult["ALL_ITEMS"][$itemID]["PARAMS"]["description"]) ? true : false;?>
				<li
					class="bx-nav-1-lvl bx-nav-list-<?=($existPictureDescColomn) ? count($arColumns)+1 : count($arColumns)?>-col <?if($arResult["ALL_ITEMS"][$itemID]["SELECTED"]):?>bx-active<?endif?><?if (is_array($arColumns) && count($arColumns) > 0):?> bx-nav-parent<?endif?>"
					onmouseover="BX.CatalogMenu.itemOver(this);"
					onmouseout="BX.CatalogMenu.itemOut(this)"
					onclick="if (BX.hasClass(document.documentElement, 'bx-touch')) obj_<?=$menuBlockId?>.clickInMobile(this, event);"
					<?if (is_array($arColumns) && count($arColumns) > 0):?>
						data-role="bx-menu-item"
					<?endif?>>
					<a href="<?=$arResult["ALL_ITEMS"][$itemID]["LINK"]?>"
						<?if (is_array($arColumns) && count($arColumns) > 0 && $existPictureDescColomn):?>
							id="<?=$itemID?>"
							data-picture="<?=$arResult["ALL_ITEMS"][$itemID]["PARAMS"]["picture_src"]?>"
							data-desc="<?=htmlspecialchars($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["description"])?>"
							data-video="<?= strpos($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["description"], 'iframe') !== FALSE ? 'true' : 'false' ?>"
							onmouseover="changeMenuPicure(this, '<?=$itemID?>');"
						<?endif?>
					><?=$arResult["ALL_ITEMS"][$itemID]["TEXT"]?></a>
				
				<?if (is_array($arColumns) && count($arColumns) > 0):?>
					<? //for mobile ?>
					<div class="bx-nav-2-lvl-container">
						<?foreach($arColumns as $key=>$arRow):?>
							<ul class="bx-nav-list-2-lvl">
							
							<?
							// divide in columns
							switch (count($arRow)) {
								case '1': { $gridClass = 'col-xs-12'; break; }
								case '2': { $gridClass = 'col-xs-12 col-sm-6'; break; }
								case '3': { $gridClass = 'col-xs-12 col-sm-4'; break; }
								case '4': { $gridClass = 'col-xs-12 col-sm-3'; break; }
								default:  { $gridClass = 'col-xs-12 col-sm-3'; break; }
							}
							?>

							<?foreach($arRow as $itemIdLevel_2=>$arLevel_3):?>
								<li class="bx-nav-2-lvl <?=$gridClass?> <?=(is_array($arLevel_3) && count($arLevel_3) > 0)?'bx-nav-parent':''?>">
									<a href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"]?>"
										<?if ($existPictureDescColomn):?>
											onmouseover="changeMenuPicure(this, '<?=$itemIdLevel_2?>');"
										<?endif?>
										id="<?=$itemIdLevel_2?>"
										data-picture="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["PARAMS"]["picture_src"]?>"
										data-desc="<?=htmlspecialchars($arResult["ALL_ITEMS"][$itemIdLevel_2]["PARAMS"]["description"])?>"
										data-video="<?= strpos($arResult["ALL_ITEMS"][$itemIdLevel_2]["PARAMS"]["description"], 'iframe') !== FALSE ? 'true' : 'false' ?>"
										<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["SELECTED"]):?>class="bx-active"<?endif?>
									><?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"]?></a>
								
								<?if (is_array($arLevel_3) && count($arLevel_3) > 0):?>
									<ul class="bx-nav-list-3-lvl">
									<?foreach($arLevel_3 as $itemIdLevel_3):?>
										<li class="bx-nav-3-lvl">
											<a href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["LINK"]?>"
												<?if ($existPictureDescColomn):?>
													onmouseover="changeMenuPicure(this, '<?=$itemIdLevel_3?>');return false;"
												<?endif?>
												id="<?=$itemIdLevel_3?>"
												data-picture="<?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["PARAMS"]["picture_src"]?>"
												data-desc="<?=htmlspecialchars($arResult["ALL_ITEMS"][$itemIdLevel_3]["PARAMS"]["description"])?>"
												data-video="<?= strpos($arResult["ALL_ITEMS"][$itemIdLevel_3]["PARAMS"]["description"], 'iframe') !== FALSE ? 'true' : 'false' ?>"
												<?if($arResult["ALL_ITEMS"][$itemIdLevel_3]["SELECTED"]):?>class="bx-active"<?endif?>
											><?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["TEXT"]?></a>
										</li>
									<?endforeach;?>
									</ul>
								<?endif?>
								</li>
							<?endforeach;?>
							</ul>
						<?endforeach;?>
						<?if ($existPictureDescColomn):?>
							<div class="bx-nav-list-2-lvl bx-nav-catinfo dbg" data-role="desc-img-block">
								<a href="<?=$arResult["ALL_ITEMS"][$itemID]["LINK"]?>">
									<img src="<?=$arResult["ALL_ITEMS"][$itemID]["PARAMS"]["picture_src"]?>" alt="">
								</a>
								<p><?=$arResult["ALL_ITEMS"][$itemID]["PARAMS"]["description"]?></p>
							</div>
						<?endif?>
					</div>
				<?endif?>

				</li>
			<?endforeach;?>

			<li class="search-button">
			    <button type="submit" title="<?=GetMessage('SEARCH')?>" class="btn btn-default">
			        <i class="fa fa-search"></i>
			        <span><?=GetMessage('SEARCH')?></span>
			        <i class="close-search hide" title="<?=GetMessage('SEARCH_CLOSE')?>"></i>
			    </button>					
			</li>

		</ul>

		<?$APPLICATION->IncludeComponent("bitrix:search.title", "visual", array(
				"NUM_CATEGORIES" => "1",
				"TOP_COUNT" => "5",
				"CHECK_DATES" => "N",
				"SHOW_OTHERS" => "N",
				"PAGE" => SITE_DIR."catalog/",
				"CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS") ,
				"CATEGORY_0" => array(
					0 => "iblock_catalog",
				),
				"CATEGORY_0_iblock_catalog" => array(
					0 => "all",
				),
				"CATEGORY_OTHERS_TITLE" => GetMessage("SEARCH_OTHER"),
				"SHOW_INPUT" => "Y",
				"INPUT_ID" => "title-search-input",
				"CONTAINER_ID" => "search",
				"PRICE_CODE" => array(
					0 => "BASE",
				),
				"SHOW_PREVIEW" => "Y",
				"PREVIEW_WIDTH" => "75",
				"PREVIEW_HEIGHT" => "75",
				"CONVERT_CURRENCY" => "Y"
			),
			false
		);?>

		<div style="clear: both;"></div>
	</nav>
</div>