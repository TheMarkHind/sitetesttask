<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
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
$this->setFrameMode(true); ?>

<div class="newsframe newsframe-m">
	<div class="detailnewsframe detailnewsframe-m">
		<div class="detailname detailname-m">
			<div class="detailnametext detailnametext-m"><?= $arResult['NAME'] ?></div>
		</div>
		<div class="detailframe detailframe-m">
			<img class="detailpicture detailpicture-m" src="<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>" />
			<p class="detailtext detailtext-m"><?= $arResult['DETAIL_TEXT'] ?></p>
		</div>
	</div>
</div>