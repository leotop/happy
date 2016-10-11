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
if(!empty($arResult["STORES"]) && $arParams["MAIN_TITLE"] != ''):?>
	<h4><?=$arParams["MAIN_TITLE"]?></h4>
<?endif;?>

<!-- <pre><? //print_r($arResult); ?></pre> -->


<div id="catalog_store_amount_div">
	<?if(!empty($arResult["STORES"])):?>
		<table class="table table-bordered" id="c_store_amount">
			<?if(!empty($arResult['JS']['SKU'])):?>
				<thead>
					<tr>
						<th></th>
						<? // Выводим названия торговых предложений ?>
						<?foreach($arResult['JS']['SKU'] as $id => $itemTitle):?>
							<th><?=$itemTitle['properties'];?></th>
						<?endforeach;?>
					</tr>
				</thead>
			<?endif;?>

			<?foreach($arResult['STORES'] as $id => $store):?>
				<tr>
					<? // Выводим названия складов ?>
					<td><?=$store['TITLE'];?></td>

					
					<?if(empty($arResult['JS']['SKU'])):?>
						<td>0 шт.</td>
					<?else:?>
						<? // Получаем ID текущего склада 
						// и используем его как ключ для поиска количества в массиве с торговыми предложениями ?>
						<?foreach($arResult['JS']['SKU'] as $id => $item):?>
							<td>
								<?if(!$item[$store['ID']]):?>
									-
								<?else:?>
									<?=$item[$store['ID']]; ?>
								<?endif;?>
							</td>
						<?endforeach;?>
					<?endif;?>
				
				</tr>
			<?endforeach;?>
			
		</table>
	<?endif;?>
</div>
