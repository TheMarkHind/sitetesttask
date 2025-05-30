<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
  die(); ?>

<div class="carcass-page">
  <footer class="footer">
    <div class="frame-2">
      <div class="img-wrapper"><a href="/"><img class="logo-2"
            src="<?= SITE_TEMPLATE_PATH ?>/assets/img/logowithtextwhite.png" /></a>
      </div>
      <div class="text-wrapper-3">Центральный офис:</div>
      <div class="text-wrapper-4">График работы центрального офиса</div>
      <div class="text-wrapper-5">График работы производства</div>
      <img class="element" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/phonenumber.png" />
      <div class="link">
        <p class="text-wrapper-6">Обратный звонок</p>
        <img class="phone-red" id="openModalBtn" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/phone.png" />
      </div>
      <div class="text-wrapper-7">Для вопросов и предложений</div>
      <div class="text-wrapper-8">mrvd@bzmr.ru</div>
      <p class="p">
        г. Москва, 121596 г. Москва, вн. тер. г. муниципальный округ Можайский, ул. Толбухина, д. 10, корп. 2, пом.
        1, комн. 5
      </p>
      <p class="text-wrapper-9">пн.-пт.: с 9:00 до 18:00</p>
      <p class="text-wrapper-10">пн.-пт.: с 8:00 до 16:30</p>
      <div class="bottom-frame">
        <div class="frame-3">
          <? $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "lowest_menu_desktop",
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
              "ROOT_MENU_TYPE" => "lowest_menu_desktop",
              "USE_EXT" => "N",
              "COMPONENT_TEMPLATE" => "lowest_menu_desktop"
            ),
            false
          ); ?>
        </div>
      </div>
      <div class="text-wrapper-13">Покупателям</div>
      <div class="bottom-menu">
        <? $APPLICATION->IncludeComponent(
          "bitrix:menu",
          "bottom_menu_desktop",
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
            "COMPONENT_TEMPLATE" => "bottom_menu_desktop"
          ),
          false
        ); ?>
      </div>
      <div class="line-2"></div>
      <div class="line-3"></div>
    </div>
  </footer>
</div>

<div class="carcass-page-mobile" id="mobileFooter">
  <footer class="footer">
    <a href="/"><img class="logo-area" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/logophonewhite.png" /></a>
    <div class="line"></div>
    <div class="footer-interactions">
      <div class="main-info">
        <p class="p">пн.-пт.: с 8:00 до 16:30</p>
        <div class="text-wrapper-2">График работы производства</div>
        <p class="text-wrapper-3">пн.-пт.: с 9:00 до 18:00</p>
        <div class="text-wrapper-4">График работы центрального офиса</div>
        <p class="text-wrapper-5">
          г. Москва, 121596 г. Москва, вн. тер. г. муниципальный округ Можайский, ул. Толбухина, д. 10, корп. 2,
          пом. 1, комн. 5
        </p>
        <div class="text-wrapper-6">Центральный офис:</div>
        <div class="text-wrapper-7">Для вопросов и предложений</div>
        <div class="text-wrapper-8">mrvd@bzmr.ru</div>
        <div id="openModalBtn" class="link">
          <div class="text-wrapper-9">Обратный звонок</div>
          <img class="phone-red" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/phone.png" />
        </div>
        <p class="text-wrapper-10">+7 (495) 790 44 11</p>
      </div>
      <div class="footer-menu">
        <div class="text-wrapper-13">Покупателям</div>
        <div class="menu-frame">
          <? $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "bottom_menu_desktop",
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
              "COMPONENT_TEMPLATE" => "bottom_menu_desktop"
            ),
            false
          ); ?>
        </div>
      </div>
    </div>
    <div class="line-2"></div>
    <div class="suggestions">
      <? $APPLICATION->IncludeComponent(
        "bitrix:menu",
        "lowest_menu_mobile",
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
          "ROOT_MENU_TYPE" => "lowest_menu_desktop",
          "USE_EXT" => "N",
          "COMPONENT_TEMPLATE" => "lowest_menu_mobil"
        ),
        false
      ); ?>
    </div>
  </footer>
</div>

</body>

</html>