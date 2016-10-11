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

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap',
		'TITLE' => 'bx_sitemap_title',
		'LIST' => 'bx_sitemap_ul',
	),
	'LINE' => array(
		'CONT' => 'bx_catalog_line',
		'TITLE' => 'bx_catalog_line_category_title',
		'LIST' => 'bx_catalog_line_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/line-empty.png'
	),
	'TEXT' => array(
		'CONT' => 'bx_catalog_text',
		'TITLE' => 'bx_catalog_text_category_title',
		'LIST' => 'bx_catalog_text_ul'
	),
	'TILE' => array(
		'CONT' => 'bx_catalog_tile',
		'TITLE' => 'bx_catalog_tile_category_title',
		'LIST' => 'bx_catalog_tile_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	)
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

?>


<?
if (0 < $arResult["SECTIONS_COUNT"] && 2==3) { ?>

	<ul id="sub-categories" class="hide products-grid <? echo $arCurView['LIST']; ?> <? echo $arCurView['CONT']; ?>">
		<? switch ($arParams['VIEW_MODE']) {
			
			case 'LINE':
				foreach ($arResult['SECTIONS'] as &$arSection) {
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

					if (false === $arSection['PICTURE'])
						$arSection['PICTURE'] = array(
							'SRC' => $arCurView['EMPTY_IMG'],
							'ALT' => (
								'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
								? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
								: $arSection["NAME"]
							),
							'TITLE' => (
								'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
								? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
								: $arSection["NAME"]
							)
						);
					?>

					<li class="item" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">

						<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>" title="<? echo $arSection['PICTURE']['TITLE']; ?>">
							<img src="<? echo $arSection['PICTURE']['SRC']; ?>" alt="<? echo $arSection['PICTURE']['TITLE']; ?>" />
						</a>

						<h2 class="product-name">
							<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>">
								<? echo $arSection['NAME']; ?>
							</a>
							<? if ($arParams["COUNT_ELEMENTS"]) {
								?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span>
							<? } ?>
						</h2>

						<? if ('' != $arSection['DESCRIPTION']) { ?>
							<p class="bx_catalog_line_description"><? echo $arSection['DESCRIPTION']; ?></p><?
						} ?>
					</li>
					<?
				}
				unset($arSection);
				break;

			case 'TEXT':
				foreach ($arResult['SECTIONS'] as &$arSection) {
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams); ?>
					
					<li class="item" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
						<h2 class="product-name">
							<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a>
							<? if ($arParams["COUNT_ELEMENTS"]) { ?> 
								<span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span>
							<? } ?>
						</h2>
					</li>
				<? }
				unset($arSection);
				break;

			case 'TILE':
				foreach ($arResult['SECTIONS'] as &$arSection) {
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

					if (false === $arSection['PICTURE'])
						$arSection['PICTURE'] = array(
							'SRC' => $arCurView['EMPTY_IMG'],
							'ALT' => (
								'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
								? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
								: $arSection["NAME"]
							),
							'TITLE' => (
								'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
								? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
								: $arSection["NAME"]
							)
						);
					?>

					<li class="item" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
						<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>" title="<? echo $arSection['PICTURE']['TITLE']; ?>">
							<img src="<? echo $arSection['PICTURE']['SRC']; ?>" alt="<? echo $arSection['PICTURE']['ALT']; ?>" />
						</a>
						<? if ('Y' != $arParams['HIDE_SECTION_NAME']) { ?>
							<h2 class="product-name">
								<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a>
								<? if ($arParams["COUNT_ELEMENTS"]) { ?> 
									<span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span>
								<? } ?>
							</h2>
						<? } ?>
					</li>
				<? }
				unset($arSection);
				break;

			case 'LIST':
				$intCurrentDepth = 1;
				$boolFirst = true;
				foreach ($arResult['SECTIONS'] as &$arSection) {
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

					if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL']) {
						if (0 < $intCurrentDepth)
							echo "\n",str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']),'<ul>';
					}
					elseif ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL']) {
						if (!$boolFirst)
							echo '</li>';
					}
					else {
						while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL']) {
							echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
							$intCurrentDepth--;
						}
						echo str_repeat("\t", $intCurrentDepth-1),'</li>';
					}

					echo (!$boolFirst ? "\n" : ''),str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']); ?>

					<li class="item" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
					<h2 class="product-name">
						<a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>">
							<? echo $arSection["NAME"];?>
							<? if ($arParams["COUNT_ELEMENTS"]) { ?> 
								<span>(<? echo $arSection["ELEMENT_CNT"]; ?>)</span><?
							} ?>
						</a>
					</h2>
					<? $intCurrentDepth = $arSection['RELATIVE_DEPTH_LEVEL'];
					$boolFirst = false;
				}
				unset($arSection);

				while ($intCurrentDepth > 1) {
					echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
					$intCurrentDepth--;
				}
				if ($intCurrentDepth > 0) {
					echo '</li>',"\n";
				}
				break;
		} ?>
	</ul>

	<? echo ('LINE' != $arParams['VIEW_MODE'] ? '<div style="clear: both;"></div>' : '');
} ?>
