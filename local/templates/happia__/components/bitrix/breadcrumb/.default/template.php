<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '';

$strReturn .= '<ul class="breadcrumb">'; ?>

<?$itemSize = count($arResult);

// get site name
$rsSites = CSite::GetByID(SITE_ID);
$arSite = $rsSites->Fetch();
$site_name = $arSite['SITE_NAME'];
// end - get site name

for($index = 0; $index < $itemSize; $index++)
{
	if($index === 0) {
		$title = $site_name;
	}
	else {
		$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	}

	$nextRef = ($index < $itemSize-2 && $arResult[$index+1]["LINK"] <> ""? ' itemref="bx_breadcrumb_'.($index+1).'"' : '');
	$child = ($index > 0? ' itemprop="child"' : '');

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
		$strReturn .= '
			<li class="bx-breadcrumb-item" id="bx_breadcrumb_'.$index.'">
				<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'">'.$title.'</a>
			</li>';
	}
	else
	{
		$strReturn .= '
			<li class="bx-breadcrumb-item">
				<strong>'.$title.'</strong>
			</li>';
	}
}

$strReturn .= '</ul>';

return $strReturn;
