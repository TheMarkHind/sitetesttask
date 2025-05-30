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

<? if (!empty($arResult['ITEMS'])): ?>
	<div class="newsframe newsframe-m">
		<? foreach ($arResult["ITEMS"] as $item): ?>
			<a href="<?= $item["DETAIL_PAGE_URL"] ?>">
				<div class="articleblock articleblock-m">
					<img class="articlepicture articlepicture-m" src="<?= $item['PREVIEW_PICTURE']['SRC']; ?>" />
					<div class="articleinformation articleinformation-m">
						<div class="articlename articlename-m"><?= isset($item['NAME']) ? $item['NAME'] : "" ?></div>
						<? if ($arParams["DISPLAY_DATE"] != "N"): ?>
							<div class="articledate articledate-m"><? echo $item["DISPLAY_ACTIVE_FROM"] ?></div>
						<? endif; ?>
					</div>
				</div>
			</a>
		<? endforeach; ?>
	</div>
<? endif; ?>