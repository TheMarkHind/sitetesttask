<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?><div class="usage-page usage-page-m" id="pageContent">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb",
	"custombreadcrumb",
	Array(
		"COMPONENT_TEMPLATE" => "custombreadcrumb",
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "1"
	)
);?>
	<div class="sectionname sectionname-m">
		<div class="sectionnametext sectionnametext-m">
			 <? $APPLICATION->ShowTitle(); ?>
		</div>
	</div>
	<div class="newsframe newsframe-m">
		<div class="detailnewsframe detailnewsframe-m">
			 Телефон: +799999999<br>
			 Почта: энвелоуп@gmail.com<br>
			 Адрес: улица дом корпус<br>
		</div>
	</div>
</div><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>