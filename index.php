<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//$APPLICATION->SetTitle("Интернет-магазин \"Одежда\"");
?>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_TEMPLATE_PATH."/include/homepage.php"), false);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
