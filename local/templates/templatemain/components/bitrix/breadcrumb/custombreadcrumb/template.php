<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

if(empty($arResult))
	return "";

$res .= '<div class="breadcrumbsarea breadcrumbsarea-m">
            <div class="breadcrumpsframe breadcrumpsframe-m">
                <div class="breadcrumbspath breadcrumbspath-m">
                    <a style="color: var(--gray-500)" href="/"> Главная / </a>';

$elCount = count($arResult);
foreach ($arResult as $index => $item) {
    $link = (!empty($item['LINK']) && $index < ($elCount - 1)) ? $item['LINK'] : '#';
    $title = $item['TITLE'] ?? ''; 
    if(!empty($item['LINK']) && $index < ($elCount - 1))
	{
        $res .= '<a href="' . $link . '">' . $title . ' / ' . '</a>';
	}
	else
	{
		$res .= '<a href="' . $link . '">' . $title . '</a>';
	}
}

$res .= '       </div>
            </div>
        </div>';

return $res; 