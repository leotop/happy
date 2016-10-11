<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
	"NAME" => "Recommended custom",
	"DESCRIPTION" => "Custom component",
	"ICON" => "/images/cat_list.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 20,
	"PATH" => array(
		"ID" => "e-store",
		"NAME" => GetMessage('CP_CATALOG_SERVICES_MAIN_SECTION'),
		"CHILD" => array(
			"ID" => "catalog-services",
			"NAME" => GetMessage("CP_CATALOG_SERVICES_PARENT_SECTION"),
			"SORT" => 500,
		)
	)
);
?>