<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

</div><!--//col-main-->


<div class="footer-container">
	<div class="footer">

		<div class="footer-block info">
			<h4 data-toggle="collapse" href="#collapseInfo" aria-expanded="false" aria-controls="collapseInfo">Информация</h4>
			<ul id="collapseAbout">
				<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_menu", array(
						"ROOT_MENU_TYPE" => "bottom_info",
						"MAX_LEVEL" => "1",
						"MENU_CACHE_TYPE" => "A",
						"CACHE_SELECTED_ITEMS" => "N",
						"MENU_CACHE_TIME" => "36000000",
						"MENU_CACHE_USE_GROUPS" => "Y",
						"MENU_CACHE_GET_VARS" => array(
						),
					),
					false
				);?>
			</ul>
		</div>

		<div class="footer-block about">
			<h4 class="title" data-toggle="collapse" href="#collapseAbout" aria-expanded="false" aria-controls="collapseAbout">О компании</h4>
			<ul id="collapseAbout">
				<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_menu", array(
						"ROOT_MENU_TYPE" => "bottom",
						"MAX_LEVEL" => "1",
						"MENU_CACHE_TYPE" => "A",
						"CACHE_SELECTED_ITEMS" => "N",
						"MENU_CACHE_TIME" => "36000000",
						"MENU_CACHE_USE_GROUPS" => "Y",
						"MENU_CACHE_GET_VARS" => array(
						),
					),
					false
				);?>
			</ul>
		</div>

		<div class="footer-block address address-big msk">
			<h4 data-toggle="collapse" href="#collapseAddressBigMsk" aria-expanded="false" aria-controls="collapseAddressBigMsk">Магазин в Москве</h4>
			<ul id="collapseAddressBigMsk">
				<li>ул. Мироновская, д.25</li>
				<li>ст.м. «Семёновская»</li>
				<li>Телефон: +7 (495) 540-47-15</li>
				<li>Ежедневно с 11:00 до 20:00</li>
			</ul>
		</div>

		<div class="footer-block address address-big spb">
			<h4 data-toggle="collapse" href="#collapseAddressBigSpb" aria-expanded="false" aria-controls="collapseAddressBigSpb">Магазин в Петербурге</h4>
			<ul id="collapseAddressBigSpb">
				<li>Лиговский пр., д.3</li>
				<li>ст.м. «Пл. Восстания»</li>
				<li>Телефон: +7 (812) 648-24-55</li>
				<li>Ежедневно с 11:00 до 20:00</li>
			</ul>
		</div>

		<div class="footer-block address address-small">
			<h4 data-toggle="collapse" href="#collapseAddressSmall" aria-expanded="false" aria-controls="collapseAddressSmall">Магазины</h4>
			<ul id="collapseAddressSmall">
				<li class="msk">Москва, ул. Мироновская, д.25</li>
				<li>Телефон: +7 (495) 540-47-15</li>
				<li class="spb">СПб, Лиговский пр., д.3</li>
				<li>Телефон: +7 (812) 648-24-55</li>
			</ul>
		</div>

		<div class="links">
			<ul>
				<li><a rel="nofollow" title="Магазин в Москве" href="/magazin-v-moskve">Москва</a></li>
				<li><a rel="nofollow" title="Магазин в Санкт-Петербурге" href="/magazin-v-sankt-peterburge">С-Петербург</a></li>
				<li><a rel="nofollow" title="Доставка в Новосибирск" href="/dostavka-novosibirsk">Новосибирск</a></li>
				<li><a rel="nofollow" title="Доставка в Екатеринбург" href="/dostavka-ekaterinburg">Екатеринбург</a></li>
				<li><a rel="nofollow" title="Доставка в Нижний Новгород" href="/dostavka-nizhnii-novgorod">Н.Новгород</a></li>
				<li><a rel="nofollow" title="Доставка в Самару" href="/dostavka-samara">Самара</a></li>
				<li><a rel="nofollow" title="Доставка в Казань" href="/dostavka-kazan">Казань</a></li>
				<li><a rel="nofollow" title="Доставка в Омск" href="/dostavka-omsk">Омск</a></li>
				<li><a rel="nofollow" title="Доставка в Челябинск" href="/dostavka-chelyabinsk">Челябинск</a></li>
				<li><a rel="nofollow" title="Доставка в Ростов-на-Дону" href="/dostavka-rostov-na-donu">Ростов-на-Дону</a></li>
				<li><a rel="nofollow" title="Доставка в Уфу" href="/dostavka-ufa">Уфа</a></li>
				<li><a rel="nofollow" title="Доставка в Волгоград" href="/dostavka-volgograd">Волгоград</a></li>
				<li><a rel="nofollow" title="Доставка в Красноярск" href="/dostavka-krasnoyarsk">Красноярск</a></li>
			</ul>
		</div>

		<div class="copyright-row">
			<div class="copyright">
				<address>
					<p>© 2009-2016 Интернет-магазин «Хаппиа»</p>
					<p>Правовое сопровождение - Юридическое Бюро "Содействие"</p>
				</address>

				<div class="payment-methods">
					<span class="fa-stack fa-lg">
						<i class="fa fa-square fa-stack-2x"></i>
						<i class="fa icon-sberbank fa-stack-1x"></i>
					</span>
					<span class="fa-stack fa-lg">
						<i class="fa fa-square fa-stack-2x"></i>
						<i class="fa icon-yandex-money fa-stack-1x"></i>
					</span>
					<span class="fa-stack fa-lg">
						<i class="fa fa-cc-mastercard fa-inverse fa-stack-2x"></i>
					</span>
					<span class="fa-stack fa-lg">
						<i class="fa fa-cc-visa fa-inverse fa-stack-2x"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="back-to-top" style="display:none" data-role="eshopUpButton">наверх &uarr;</div>


<? 
	CJSCore::Init(array("jquery")); 
	// $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/lib/jquery-2.2.1.min.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/lib/modernizr-2.8.3.min.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/lib/respond.min.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/bootstrap/transition.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/bootstrap/dropdown.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/bootstrap/collapse.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/bootstrap/offcanvas.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/bootstrap/tab.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/bootstrap/tooltip.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/bootstrap/popover.js");
	$APPLICATION->SetAdditionalCSS('/bitrix/templates/happia/js/fancybox/jquery.fancybox.css');
	$APPLICATION->AddHeadScript('/bitrix/templates/happia/js/fancybox/jquery.fancybox.pack.js'); 
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/ress.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/scripts.js");
?>
</body>
</html>