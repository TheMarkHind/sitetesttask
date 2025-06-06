<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
	die();
}

/** @var CBitrixPersonalOrderListComponent $component */
/** @var array $arParams */
/** @var array $arResult */

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/bootstrap_v4/script.js");
Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/bootstrap_v4/style.css");
CJSCore::Init(array('clipboard', 'fx'));

Loc::loadMessages(__FILE__);

if (!empty($arResult['ERRORS']['FATAL']))
{
	foreach($arResult['ERRORS']['FATAL'] as $code => $error)
	{
		if ($code !== $component::E_NOT_AUTHORIZED)
			ShowError($error);
	}
	$component = $this->__component;
	if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED]))
	{
		?>
		<div class="row">
			<div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
				<div class="alert alert-danger"><?=$arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED]?></div>
			</div>
			<? $authListGetParams = array(); ?>
			<div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
				<?$APPLICATION->AuthForm('', false, false, 'N', false);?>
			</div>
		</div>
		<?
	}

}
else
{
	$filterHistory = ($_REQUEST['filter_history'] ?? '');
	$filterShowCanceled = ($_REQUEST["show_canceled"] ?? '');

	if (!empty($arResult['ERRORS']['NONFATAL']))
	{
		foreach($arResult['ERRORS']['NONFATAL'] as $error)
		{
			ShowError($error);
		}
	}
	if (empty($arResult['ORDERS']))
	{
		if ($filterHistory === 'Y')
		{
			if ($filterShowCanceled === 'Y')
			{
				?>
				<h3><?= Loc::getMessage('SPOL_TPL_EMPTY_CANCELED_ORDER')?></h3>
				<?
			}
			else
			{
				?>
				<h3><?= Loc::getMessage('SPOL_TPL_EMPTY_HISTORY_ORDER_LIST')?></h3>
				<?
			}
		}
		else
		{
			?>
			<h3><?= Loc::getMessage('SPOL_TPL_EMPTY_ORDER_LIST')?></h3>
			<?
		}
	}
	?>
	<div class="row mb-3">
		<div class="col">
			<?
			$nothing = !isset($_REQUEST["filter_history"]) && !isset($_REQUEST["show_all"]);
			$clearFromLink = array("filter_history","filter_status","show_all", "show_canceled");

			if ($nothing || $filterHistory === 'N')
			{
				?>
				<a class="mr-4" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false)?>"><?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_HISTORY")?></a>
				<?
			}
			if ($filterHistory === 'Y')
			{
				?>
				<a class="mr-4" href="<?=$APPLICATION->GetCurPageParam("", $clearFromLink, false)?>"><?echo Loc::getMessage("SPOL_TPL_CUR_ORDERS")?></a>
				<?
				if ($filterShowCanceled === 'Y')
				{
					?>
					<a class="mr-4" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false)?>"><?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_HISTORY")?></a>
					<?
				}
				else
				{
					?>
					<a class="mr-4" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y&show_canceled=Y", $clearFromLink, false)?>"><?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_CANCELED")?></a>
					<?
				}
			}
			?>
		</div>
	</div>
	<?
	if (empty($arResult['ORDERS']))
	{
		?>
		<div class="row mb-3">
			<div class="col">
				<a href="<?=htmlspecialcharsbx($arParams['PATH_TO_CATALOG'])?>" class="mr-4"><?=Loc::getMessage('SPOL_TPL_LINK_TO_CATALOG')?></a>
			</div>
		</div>
		<?
	}

	if ($filterHistory !== 'Y')
	{
		$paymentChangeData = array();
		$orderHeaderStatus = null;

		foreach ($arResult['ORDERS'] as $key => $order)
		{
			if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $arResult['SORT_TYPE'] == 'STATUS')
			{
				$orderHeaderStatus = $order['ORDER']['STATUS_ID'];

				?>
				<div class="row mb-3">
					<div class="col">
						<h2><?= Loc::getMessage('SPOL_TPL_ORDER_IN_STATUSES_MSGVER_1', ['#STATUS#' => htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME'])]) ?>
					</div>
				</div>
				<?
			}
			?>
			<div class="row mx-0 sale-order-list-title-container">
				<h3 class="col mb-1 mt-1">
					<?=Loc::getMessage('SPOL_TPL_ORDER')?>
					<?=Loc::getMessage('SPOL_TPL_NUMBER_SIGN').$order['ORDER']['ACCOUNT_NUMBER']?>
					<?=Loc::getMessage('SPOL_TPL_FROM_DATE')?>
					<?=$order['ORDER']['DATE_INSERT_FORMATED']?>,
					<?=count($order['BASKET_ITEMS']);?>
					<?
					$count = count($order['BASKET_ITEMS']) % 10;
					if ($count == '1')
					{
						echo Loc::getMessage('SPOL_TPL_GOOD');
					}
					elseif ($count >= '2' && $count <= '4')
					{
						echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
					}
					else
					{
						echo Loc::getMessage('SPOL_TPL_GOODS');
					}
					?>
					<?=Loc::getMessage('SPOL_TPL_SUMOF')?>
					<?=$order['ORDER']['FORMATED_PRICE']?>
				</h3>
			</div>
			<div class="row mx-0 mb-5">
				<div class="col pt-3 sale-order-list-inner-container">
					<div class="row mb-3 align-items-center">
						<div class="col-auto">
							<span class="sale-order-list-inner-title-line-item"><?=Loc::getMessage('SPOL_TPL_PAYMENT')?></span>
						</div>
						<div class="col">
							<hr class="sale-order-list-inner-title-line" />
						</div>
					</div>

					<?
					$showDelimeter = false;
					foreach ($order['PAYMENT'] as $payment)
					{
						if ($order['ORDER']['LOCK_CHANGE_PAYSYSTEM'] !== 'Y')
						{
							$paymentChangeData[$payment['ACCOUNT_NUMBER']] = array(
								"order" => htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']),
								"payment" => htmlspecialcharsbx($payment['ACCOUNT_NUMBER']),
								"allow_inner" => $arParams['ALLOW_INNER'],
								"refresh_prices" => $arParams['REFRESH_PRICES'],
								"path_to_payment" => $arParams['PATH_TO_PAYMENT'],
								"only_inner_full" => $arParams['ONLY_INNER_FULL'],
								"return_url" => $arResult['RETURN_URL'],
							);
						}
						?>

						<? if ($showDelimeter)
						{
							?>
								<hr class="sale-order-list-inner-title-line mb-3" />
							<?
						}
						else
						{
							$showDelimeter = true;
						}
						?>

						<div class="row mb-3 sale-order-list-inner-row">
							<div class="col sale-order-list-inner-row-body">
								<div class="row">
									<div class="col sale-order-list-payment">
										<div class="mb-1 sale-order-list-payment-title"><?
											$paymentSubTitle = Loc::getMessage('SPOL_TPL_BILL')." ".Loc::getMessage('SPOL_TPL_NUMBER_SIGN').htmlspecialcharsbx($payment['ACCOUNT_NUMBER']);
											if(isset($payment['DATE_BILL']))
											{
												$paymentSubTitle .= " ".Loc::getMessage('SPOL_TPL_FROM_DATE')." ".$payment['DATE_BILL_FORMATED'];
											}
											$paymentSubTitle .=",";
											echo $paymentSubTitle;
											?>
											<span class="sale-order-list-payment-title-element"><?=$payment['PAY_SYSTEM_NAME']?></span><?
											if ($payment['PAID'] === 'Y')
											{
												?>
												<span class="sale-order-list-status-success"><?=Loc::getMessage('SPOL_TPL_PAID')?></span>
												<?
											}
											elseif ($order['ORDER']['IS_ALLOW_PAY'] == 'N')
											{
												?>
												<span class="sale-order-list-status-restricted"><?=Loc::getMessage('SPOL_TPL_RESTRICTED_PAID')?></span>
												<?
											}
											else
											{
												?>
												<span class="sale-order-list-status-alert"><?=Loc::getMessage('SPOL_TPL_NOTPAID')?></span>
												<?
											}
											?>
										</div>
										<div class="mb-1 sale-order-list-payment-price">
											<?= Loc::getMessage(
												'SPOL_TPL_SUM_TO_PAID_MSGVER_1',
												[
													'[text_span]' => '<span class="sale-order-list-payment-element">',
													'[/text_span]' => '</span>',
													'[sum_span]' => '<span class="sale-order-list-payment-number">',
													'#SUM#' => $payment['FORMATED_SUM'],
													'[/sum_span]' => '</span>',
												],
											) ?>
										</div>
										<? if (!empty($payment['CHECK_DATA']))
										{
											$listCheckLinks = "";
											foreach ($payment['CHECK_DATA'] as $checkInfo)
											{
												$title = Loc::getMessage('SPOL_CHECK_NUM', array('#CHECK_NUMBER#' => $checkInfo['ID']))." - ". htmlspecialcharsbx($checkInfo['TYPE_NAME']);
												if($checkInfo['LINK'] <> '')
												{
													$link = $checkInfo['LINK'];
													$listCheckLinks .= "<div><a href='$link' target='_blank'>$title</a></div>";
												}
											}
											if ($listCheckLinks <> '')
											{
												?>
												<div class="sale-order-list-payment-check">
													<div class="sale-order-list-payment-check-left"><?= Loc::getMessage('SPOL_CHECK_TITLE')?>:</div>
													<div class="sale-order-list-payment-check-left"><?=$listCheckLinks?></div>
												</div>
												<?
											}
										}
										if ($payment['PAID'] !== 'Y' && $order['ORDER']['LOCK_CHANGE_PAYSYSTEM'] !== 'Y')
										{
											?>
											<a href="#" class="sale-order-list-change-payment" id="<?= htmlspecialcharsbx($payment['ACCOUNT_NUMBER']) ?>"><?= Loc::getMessage('SPOL_TPL_CHANGE_PAY_TYPE') ?></a>
											<?
										}
										if ($order['ORDER']['IS_ALLOW_PAY'] == 'N' && $payment['PAID'] !== 'Y')
										{
											?>
											<div class="sale-order-list-status-restricted-message-block">
												<span class="sale-order-list-status-restricted-message"><?=Loc::getMessage('SOPL_TPL_RESTRICTED_PAID_MESSAGE')?></span>
											</div>
											<?
										}
										?>
									</div>
									<?
									if ($payment['PAID'] === 'N' && $payment['IS_CASH'] !== 'Y' && $payment['ACTION_FILE'] !== 'cash')
									{
										if ($order['ORDER']['IS_ALLOW_PAY'] == 'N')
										{
											?>
											<div class="col-sm-auto sale-order-list-button-container">
												<a class="btn btn-primary disabled"><?=Loc::getMessage('SPOL_TPL_PAY')?></a>
											</div>
											<?
										}
										elseif ($payment['NEW_WINDOW'] === 'Y')
										{
											?>
											<div class="col-sm-auto  sale-order-list-button-container">
												<a class="btn btn-primary" target="_blank" href="<?=htmlspecialcharsbx($payment['PSA_ACTION_FILE'])?>"><?=Loc::getMessage('SPOL_TPL_PAY')?></a>
											</div>
											<?
										}
										else
										{
											?>
											<div class="col-sm-auto  sale-order-list-button-container">
												<a class="btn btn-primary ajax_reload" href="<?=htmlspecialcharsbx($payment['PSA_ACTION_FILE'])?>"><?=Loc::getMessage('SPOL_TPL_PAY')?></a>
											</div>
											<?
										}
									}
									?>
								</div>
							</div>
							<div class="col sale-order-list-inner-row-template">
								<a class="sale-order-list-cancel-payment" href="">
									<i class="fa fa-long-arrow-left"></i> <?=Loc::getMessage('SPOL_CANCEL_PAYMENT')?>
								</a>
							</div>
						</div>
						<?
					}
						if (!empty($order['SHIPMENT']))
						{
							?>
							<div class="row mb-3 align-items-center">
								<div class="col-auto">
									<span class="sale-order-list-inner-title-line-item"><?=Loc::getMessage('SPOL_TPL_DELIVERY')?></span>
								</div>
								<div class="col">
									<hr class="sale-order-list-inner-title-line" />
								</div>
							</div>
							<?
						}
						$showDelimeter = false;
						foreach ($order['SHIPMENT'] as $shipment)
						{
							if (empty($shipment))
							{
								continue;
							}
							?>
							<?
							if ($showDelimeter)
							{
								?>
								<div class="row mb-3">
									<div class="col">
										<hr class="sale-order-list-inner-title-line" />
									</div>
								</div>
								<?
							}
							else
							{
								$showDelimeter = true;
							}
							?>
							<div class="row mb-3">
								<div class="col">
									<div class="mb-1 sale-order-list-shipment-title">
										<span class="sale-order-list-shipment-element">
											<?=Loc::getMessage('SPOL_TPL_LOAD')?>
											<?
											$shipmentSubTitle = Loc::getMessage('SPOL_TPL_NUMBER_SIGN').htmlspecialcharsbx($shipment['ACCOUNT_NUMBER']);
											if ($shipment['DATE_DEDUCTED'])
											{
												$shipmentSubTitle .= " ".Loc::getMessage('SPOL_TPL_FROM_DATE')." ".$shipment['DATE_DEDUCTED_FORMATED'];
											}

											if ($shipment['FORMATED_DELIVERY_PRICE'])
											{
												$shipmentSubTitle .= ", ".Loc::getMessage('SPOL_TPL_DELIVERY_COST')." ".$shipment['FORMATED_DELIVERY_PRICE'];
											}
											echo $shipmentSubTitle;
											?>
										</span>
										<?
										if ($shipment['DEDUCTED'] == 'Y')
										{
											?>
											<span class="sale-order-list-status-success"><?=Loc::getMessage('SPOL_TPL_LOADED');?></span>
											<?
										}
										else
										{
											?>
											<span class="sale-order-list-status-alert"><?=Loc::getMessage('SPOL_TPL_NOTLOADED');?></span>
											<?
										}
										?>
									</div>

									<div class="mb-1 sale-order-list-shipment-status">
										<span class="sale-order-list-shipment-status-item"><?=Loc::getMessage('SPOL_ORDER_SHIPMENT_STATUS');?>:</span>
										<span class="sale-order-list-shipment-status-block"><?=htmlspecialcharsbx($shipment['DELIVERY_STATUS_NAME'])?></span>
									</div>

									<?
									if (!empty($shipment['DELIVERY_ID']))
									{
										?>
										<div class="mb-1 sale-order-list-shipment-item"><?=Loc::getMessage('SPOL_TPL_DELIVERY_SERVICE')?>: <?=$arResult['INFO']['DELIVERY'][$shipment['DELIVERY_ID']]['NAME']?></div>
										<?
									}

									if (!empty($shipment['TRACKING_NUMBER']))
									{
										?>
										<div class="mb-1 sale-order-list-shipment-item">
											<span class="sale-order-list-shipment-id-name"><?=Loc::getMessage('SPOL_TPL_POSTID')?>:</span>
											<span class="sale-order-list-shipment-id"><?=htmlspecialcharsbx($shipment['TRACKING_NUMBER'])?></span>
											<span class="sale-order-list-shipment-id-icon"></span>
										</div>
										<?
									}
									?>
								</div>
								<?
								if ($shipment['TRACKING_URL'] <> '')
								{
									?>
									<div class="col-md-2 col-md-offset-1 col-sm-12 sale-order-list-shipment-button-container">
										<a class="sale-order-list-shipment-button" target="_blank" href="<?=$shipment['TRACKING_URL']?>">
											<?=Loc::getMessage('SPOL_TPL_CHECK_POSTID')?>
										</a>
									</div>
									<?
								}
								?>
							</div>
							<?
						}
						?>

						<div class="row mb-3">
							<div class="col">
								<hr class="sale-order-list-inner-title-line" />
							</div>
						</div>

						<div class="row pb-3 sale-order-list-inner-row">
							<div class="col-auto sale-order-list-about-container">
								<a class="g-font-size-15 sale-order-list-about-link" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"])?>"><?=Loc::getMessage('SPOL_TPL_MORE_ON_ORDER')?></a>
							</div>
							<div class="col"></div>
							<div class="col-auto sale-order-list-repeat-container">
								<a class="g-font-size-15 sale-order-list-repeat-link" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"])?>"><?=Loc::getMessage('SPOL_TPL_REPEAT_ORDER')?></a>
							</div>
							<?
							if ($order['ORDER']['CAN_CANCEL'] !== 'N')
							{
								?>
								<div class="col-auto sale-order-list-cancel-container">
									<a class="g-font-size-15 sale-order-list-cancel-link" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_CANCEL"])?>"><?=Loc::getMessage('SPOL_TPL_CANCEL_ORDER')?></a>
								</div>
								<?
							}
							?>
						</div>
					</div>
				</div>
			<?
		}
	}
	else
	{
		$orderHeaderStatus = null;

		if ($filterShowCanceled === 'Y' && !empty($arResult['ORDERS']))
		{
			?>
			<div class="row mb-3">
				<div class="col">
					<h2><?= Loc::getMessage('SPOL_TPL_ORDERS_CANCELED_HEADER') ?></h2>
				</div>
			</div>
			<?
		}

		foreach ($arResult['ORDERS'] as $key => $order)
		{
			if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $filterShowCanceled !== 'Y')
			{
				$orderHeaderStatus = $order['ORDER']['STATUS_ID'];
				?>
				<h1 class="sale-order-title">
					<?= Loc::getMessage('SPOL_TPL_ORDER_IN_STATUSES_MSGVER_1', ['#STATUS#' => htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME'])]) ?>
				</h1>
				<?
			}
			?>
			<div class="row sale-order-list-accomplished-title-container">
				<h3 class="g-font-size-20 mb-1 mt-1 col-sm">
					<?= Loc::getMessage('SPOL_TPL_ORDER') ?>
					<?= Loc::getMessage('SPOL_TPL_NUMBER_SIGN') ?>
					<?= htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER'])?>
					<?= Loc::getMessage('SPOL_TPL_FROM_DATE') ?>
					<span class="text-nowrap"><?= $order['ORDER']['DATE_INSERT'] ?>,</span>
					<?= count($order['BASKET_ITEMS']); ?>
					<?
					$count = mb_substr(count($order['BASKET_ITEMS']), -1);
					if ($count == '1')
					{
						echo Loc::getMessage('SPOL_TPL_GOOD');
					}
					elseif ($count >= '2' || $count <= '4')
					{
						echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
					}
					else
					{
						echo Loc::getMessage('SPOL_TPL_GOODS');
					}
					?>
					<?= Loc::getMessage('SPOL_TPL_SUMOF') ?>
					<span class="text-nowrap"><?= $order['ORDER']['FORMATED_PRICE'] ?></span>
				</h3>
				<div class="col-sm-auto">
					<?
					if ($filterShowCanceled !== 'Y')
					{
						?>
						<span class="sale-order-list-accomplished-date">
									<?= Loc::getMessage('SPOL_TPL_ORDER_FINISHED')?>
								</span>
						<?
					}
					else
					{
						?>
						<span class="sale-order-list-accomplished-date canceled-order">
									<?= Loc::getMessage('SPOL_TPL_ORDER_CANCELED')?>
								</span>
						<?
					}
					?>
					<span class="sale-order-list-accomplished-date"><?= $order['ORDER']['DATE_STATUS_FORMATED'] ?></span>
				</div>
			</div>
			<div class="row mb-5">
				<div class="col pt-3 sale-order-list-inner-container">
					<div class="row pb-3 sale-order-list-inner-row">
						<div class="col-auto col-auto sale-order-list-about-container">
							<a class="g-font-size-15 sale-order-list-about-link" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"])?>"><?=Loc::getMessage('SPOL_TPL_MORE_ON_ORDER')?></a>
						</div>
						<div class="col"></div>
						<div class="col-auto sale-order-list-repeat-container">
							<a class="g-font-size-15 sale-order-list-cancel-link" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"])?>"><?=Loc::getMessage('SPOL_TPL_REPEAT_ORDER')?></a>
						</div>
					</div>
				</div>
			</div>
			<?
		}
	}

	echo $arResult["NAV_STRING"];

	if ($filterHistory !== 'Y')
	{
		$javascriptParams = array(
			"url" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
			"templateFolder" => CUtil::JSEscape($templateFolder),
			"templateName" => $this->__component->GetTemplateName(),
			"paymentList" => $paymentChangeData,
			"returnUrl" => CUtil::JSEscape($arResult["RETURN_URL"]),
		);
		$javascriptParams = CUtil::PhpToJSObject($javascriptParams);
		?>
		<script>
			BX.Sale.PersonalOrderComponent.PersonalOrderList.init(<?=$javascriptParams?>);
		</script>
		<?
	}
}
