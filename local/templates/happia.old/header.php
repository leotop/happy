<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/global.php");
CJSCore::Init(array("fx"));
$curPage = $APPLICATION->GetCurPage(true);
function PageType() {
  global $APPLICATION;
  return $APPLICATION->GetPageProperty("PAGE_TYPE");  
}
?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
	<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_DIR?>favicon.ico" />
	<?$APPLICATION->ShowHead();?>
	<?
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/jcarousel.css");
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/style.css");
	?>
	<title><?$APPLICATION->ShowTitle(true)?></title>
</head>

<body class="col1-layout row-offcanvas row-offcanvas-left <?=$APPLICATION->AddBufferContent('PageType')?>">
<div id="panel"><? $APPLICATION->ShowPanel();?></div>



<? $APPLICATION->IncludeComponent("bitrix:eshop.banner", "", array());?>

<nav class="navbar navbar-default navbar-static-top navbar-top">  
    <ul class="nav navbar-nav">
		<?if(!$USER->IsAuthorized()):?>
			<li><a href="/login/?login=yes&backurl=<?=$APPLICATION->GetCurPage() ?>" onclick="jQuery(this).getloginpopupTODO();" title="<?=GetMessage('Login')?>">
				<?=GetMessage('Login')?>
			</a></li>
			<li class="last"><a href="/login/?login=yes&backurl=/personal/order/" onclick="jQuery(this).getloginpopupTODO();" title="<?=GetMessage('Login')?>">
				<?=GetMessage('Where_is_my_order')?>
			</a></li>
		<?php else: ?>
			<li><a href="/personal/order/" title="<?=GetMessage('My_account')?>">
				<?=GetMessage('Where_is_my_order')?>
			</a></li>
			<li class="last">
				<a title="<?=GetMessage('Logout')?>" href="<?echo $APPLICATION->GetCurPageParam("logout=yes", array(
					"login",
					"logout",
					"register",
					"forgot_password",
					"change_password"));?>">
					<?=GetMessage('Logout')?>
				</a>
			</li>
		<?endif;?>

		<?$APPLICATION->IncludeComponent(
			"bitrix:menu","top_links",
			array(
		        "ROOT_MENU_TYPE" => "top", 
		        "MAX_LEVEL" => "1", 
		        "CHILD_MENU_TYPE" => "top", 
		        "USE_EXT" => "Y",
		        "DELAY" => "N",
		        "ALLOW_MULTI_SELECT" => "Y",
		        "MENU_CACHE_TYPE" => "N", 
		        "MENU_CACHE_TIME" => "3600", 
		        "MENU_CACHE_USE_GROUPS" => "Y", 
		        "MENU_CACHE_GET_VARS" => "" 
		    ), false, Array('HIDE_ICONS' => 'Y')
		);?>
    </ul>
</nav>

<div class="header-top"></div>


<header class="mobile-header">     
    <div class="toggle-nav">
        <button type="button" class="navbar-toggle collapsed" data-toggle="offcanvas" data-target="#main-menu" data-canvas="body">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button> 
    </div>

    <a href="<?=SITE_DIR?>" title="TODO" class="logo">
        <img src='<?=SITE_TEMPLATE_PATH."/images/logo_mobile.png"?>' />
    </a>

    <div class="toggle-search">
        <i class="fa fa-times close-search hide"></i>
        <i class="fa fa-search"></i>
    </div>

    <div class="toggle-address">
        <i class="fa fa-map-marker"></i>
    </div>

    
	<div class="mini-cart">
		<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "dropdown_cart", 
			Array(
		        "PATH_TO_BASKET" => "/personal/cart/",
		        "PATH_TO_ORDER" => "/personal/order/",
		        "SHOW_PRODUCTS" => "Y",
		        "SHOW_DELAY" => "Y",
		        "SHOW_NOTAVAIL" => "Y",
		        "SHOW_SUBSCRIBE" => "N",
		        "SHOW_SUMMARY" => "N",
		        "SHOW_IMAGE" => "Y",
		        "SHOW_PRICE" => "Y"
		    ), false
		);?>
	</div>
</header>


<div class="address-open address-both" style="display:none">
    <div class="address-close"><i class="fa fa-times"></i></div>
</div>


<header class="desktop-header">

    <a href="<?=SITE_DIR?>" title="TODO" class="logo">
        <div class="logo-inner">
            <img src='<?=SITE_TEMPLATE_PATH."/images/logo.png"?>' />
        </div>
    </a>

    <?//$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_TEMPLATE_PATH."/include/telephone.php"), false);?>

    <?//$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_TEMPLATE_PATH."/include/schedule.php"), false);?>

    <div class="header-blocks">

        <address class="address msk">
        	<div class="address-inner">
        		<p class="title">Магазин в Москве</p>
        		<p class="metro">м. «Семёновская», <a href="/magazin-v-moskve" class="map-link">(карта)</a></p>
        		<p class="city-address">ул. Мироновская</p>
        		<p class="phone"><i class="fa fa-phone"></i> +7 (495) 540-47-17</p>
        		<p class="work-time">Ежедневно с 11:00 до 20:00</p>
        	</div>
        </address>

        <address class="address spb">
        	<div class="address-inner">
        		<p class="title">Магазин в Петербурге</p>
        		<p class="metro">м. «Пл. Восстания», <a href="/magazin-v-sankt-peterburge" class="map-link">(карта)</a></p>
        		<p class="city-address">Лиговский пр., д.3</p>
        		<p class="phone"><i class="fa fa-phone"></i> +7 (812) 648-24-55</p>
        		<p class="work-time">Ежедневно с 11:00 до 20:00</p>
        	</div>
        </address>

        <div class="address-open msk" style="display:none">
            <p class="title">Магазин «Хаппиа» в Москве</p>
            <p>Если вы проживаете в Москве и хотите купить карнавальные костюмы, маски, грим, подарки или свадебные аксессуары из нашего ассортимента, то вы можете сделать это лично, посетив наш розничный магазин, или заказав товар с доставкой на дом через интернет-магазин.</p>
            <div class="row">
	            <div class="col-sm-7">
		            <div class="map">
		            	<img src="https://api-maps.yandex.ru/services/constructor/1.0/static/?sid=291P8wh24rUzt5Rz7EKd0nB0mU-zha8H&width=500&height=320&lang=ru_RU&sourceType=constructor" alt=""/>
		            </div>
		            <div class="video">
		            	<div class="iframe youtube" id="lmN_rlix_4Q"></div>
		            </div>
	            </div>
	            <div class="manual col-sm-5">
	            	<p class="title">Пешком от ст.м.«Семёновская»:</p>
	            	<p>(12-15 минут)<br/>1. Выйдите из вестибюля метро и обогните его с левой стороны</p>
	            	<p>2. Пройдите между ТРК «Семёновский » и соседним зданием до ул. Вельяминовская и поверните налево.</p>
	            	<p>3. Дойдите до ул. Ткацкая и поверните направо, а затем на втором светофоре поверните налево на ул. Мироновская.</p>
	            	<p>4. С левой стороны дома №25 будет шлагбаум. Зайдите за него и поверните в арку направо во внутренний двор.</p>
	            </div>
            </div>
            <div class="address-close"><i class="fa fa-times"></i></div>
        </div>

        <div class="address-open spb" style="display:none">
            <p><strong>Магазин «Хаппиа» в Санкт-Петербурге</strong></p>
            <p>Приобретая карнавальные костюмы, маски или грим в магазине «Хаппиа», вы получаете не только качественный товар, но и быструю доставку вашего заказа в любую точку Санкт-Петербурга. У вас есть возможность приобрести костюмы, маски, грим, цветные линзы и всевозможные аксессуары как по предоплате, так и наложенным платежом.</p>
            <div class="row">
	            <div class="col-sm-7">
		            <div class="map">
		            	<img src="https://api-maps.yandex.ru/services/constructor/1.0/static/?sid=tC6SmSFHODtBo1yyYXObxd3qtT6Wn18A&width=600&height=421&lang=ru_RU&sourceType=constructor" alt=""/>
		            </div>
	            </div>
	            <div class="manual col-sm-5">
	            	<p class="title">Для Петербурга действуют следующие условия доставки:</p>
	            	<ul>
	            		<li>эконом доставка 2-3 дня - 350 рублей;</li>
	            		<li>экспресс доставка 1-2 дня - 450 рублей.</li>
	            	</ul>
	            	<p>&nbsp;</p>
	            	<p class="title">Также вы можете самостоятельно забрать заказ по адресу:</p>
	            	<p>Санкт-Петербург, Лиговский проспект, д.3</p>
	            </div>
            </div>
            <div class="address-close"><i class="fa fa-times"></i></div>
        </div>


    	<a href="#" class="call-to-me getcallmeback false-link">
            <div class="call-to-me-inner">
                <div class="fa fa-phone call-to-me-icon"></div>
                <div class="call-to-me-title"><?=GetMessage('Call_to_me')?></div>
            </div>
        </a>

		<div class="mini-cart">
			<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "dropdown_cart", 
				Array(
			        "PATH_TO_BASKET" => "/personal/cart/",
			        "PATH_TO_ORDER" => "/personal/order/",
			        "SHOW_PRODUCTS" => "Y",
			        "SHOW_DELAY" => "Y",
			        "SHOW_NOTAVAIL" => "Y",
			        "SHOW_SUBSCRIBE" => "N",
			        "SHOW_SUMMARY" => "N",
			        "SHOW_IMAGE" => "Y",
			        "SHOW_PRICE" => "Y"
			    ), false
			);?>
		</div>

	</div>
</header>


<?$APPLICATION->IncludeComponent("bitrix:menu", "catalog_horizontal", Array(
	"ROOT_MENU_TYPE" => "catalog",	// Тип меню для первого уровня
		"MENU_CACHE_TYPE" => "A",	// Тип кеширования
		"MENU_CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"MENU_CACHE_USE_GROUPS" => "N",	// Учитывать права доступа
		"MENU_THEME" => "site",	// Тема меню
		"CACHE_SELECTED_ITEMS" => "N",
		"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
		"MAX_LEVEL" => "3",	// Уровень вложенности меню
		"CHILD_MENU_TYPE" => "catalog",	// Тип меню для остальных уровней
		"USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
		"DELAY" => "N",	// Откладывать выполнение шаблона меню
		"ALLOW_MULTI_SELECT" => "Y",	// Разрешить несколько активных пунктов одновременно
	),
	false
);?>

<?if ($curPage != SITE_DIR."index.php"):?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:breadcrumb", "", array(
			"START_FROM" => "0",
			"PATH" => "",
			"SITE_ID" => "-"
		),
		false,
		Array('HIDE_ICONS' => 'Y')
	);?>
<?endif?>

<div class="col-main content">