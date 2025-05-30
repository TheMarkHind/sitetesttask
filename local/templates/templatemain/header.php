<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
  die(); ?>
<? use \Bitrix\Main\Page\Asset; ?>

<html>

<head>
  <? $APPLICATION->ShowHead(); ?>
  <title><? $APPLICATION->ShowTitle() ?></title>

  <link rel="icon" href="<?= SITE_TEMPLATE_PATH ?>/assets/img/logoonly.png" type="image/x-icon">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta charset="utf-8" />

  <? Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/globals.css"); ?>
  <? Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/styleguide.css"); ?>
  <? Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/style.css"); ?>
  <? Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/pagesstyle.css"); ?>
  <? Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/newsliststyle.css"); ?>
  <? Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/newsdetailstyle.css"); ?>
  <? Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/modalwindow.css"); ?>

  <? Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/main.js"); ?>
  <? Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/modalwindow.js"); ?>
</head>

<body>
  <div class="carcass-page">
    <? $APPLICATION->ShowPanel() ?>
    <header class="header">
      <div class="top-bar">
        <div class="frame">
          <div class="div">
            <div class="img-wrapper"><img class="mail" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/mail.png" /></div>
            <div class="text-wrapper">mrvd@bzmr.ru</div>
          </div>
          <div class="div">
            <div class="img-wrapper"><img class="phone" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/phone.png" /></div>
            <p class="text-wrapper">+7 (495) 790 44 11</p>
          </div>
        </div>
      </div>
      <div class="line"></div>
      <div class="header-2">
        <div class="navbar">
          <? $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "top_menu_desktop",
            array(
              "ALLOW_MULTI_SELECT" => "N",
              "CHILD_MENU_TYPE" => "left",
              "DELAY" => "N",
              "MAX_LEVEL" => "2",
              "MENU_CACHE_GET_VARS" => array(
              ),
              "MENU_CACHE_TIME" => "3600",
              "MENU_CACHE_TYPE" => "Y",
              "MENU_CACHE_USE_GROUPS" => "N",
              "ROOT_MENU_TYPE" => "top",
              "USE_EXT" => "N",
              "COMPONENT_TEMPLATE" => "top_menu_desktop"
            ),
            false
          ); ?>
        </div>
        <div class="img-wrapper">
          <a href="/"><img class="logo" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/logowithtext.png" /></a>
        </div>
      </div>
    </header>
  </div>
  <div class="carcass-page-mobile" id="mobileHeader">
    <header class="header-mobile">
      <img class="bars" id="menuToggle" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/bars.png" />
      <a class="logoshort" href="/"><img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/logophone.png" /></a>
      <img class="phone" id="openModalBtn" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/phone.png" />
    </header>
  </div>
  <div class="hidden" id="mobileMenu">
    <div class="menu-frame">
      <div class="down-frame">
        <div class="text-wrapper">Для вопросов и предложений</div>
        <div class="p">+7 (495) 150 14 50</div>
        <div class="div">info@bzm32.ru</div>
      </div>
      <div class="menu-list">
        <? $APPLICATION->IncludeComponent(
          "bitrix:menu",
          "top_menu_mobile",
          array(
            "ALLOW_MULTI_SELECT" => "N",
            "CHILD_MENU_TYPE" => "left",
            "DELAY" => "N",
            "MAX_LEVEL" => "2",
            "MENU_CACHE_GET_VARS" => array(
            ),
            "MENU_CACHE_TIME" => "3600",
            "MENU_CACHE_TYPE" => "Y",
            "MENU_CACHE_USE_GROUPS" => "N",
            "ROOT_MENU_TYPE" => "top",
            "USE_EXT" => "N",
            "COMPONENT_TEMPLATE" => "top_menu_mobile"
          ),
          false
        ); ?>
      </div>
      <div class="line"></div>
      <div class="overlap-group">
        <div class="menu-text">Меню</div>
        <img class="back-button" id="closeMenuToggle" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/backbutton.png" />
      </div>
    </div>
  </div>
  <div id="phoneModal" class="hidden">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3>Введите номер телефона</h3>
      <input type="tel" placeholder="+7 (XXX) XXX-XX-XX" id="phoneInput">
      <button id="submitPhone">Отправить</button>
    </div>
  </div>