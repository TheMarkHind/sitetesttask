<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>


<? if (!empty($arResult)): ?>
		<? foreach ($arResult as $item): ?>
			<a href="<?= $item["LINK"] ?>"><div class="text-wrapper-2"><?= $item["TEXT"] ?></div></a>
		<? endforeach; ?>
<? endif; ?>