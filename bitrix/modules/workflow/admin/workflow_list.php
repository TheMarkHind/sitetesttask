<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/workflow/prolog.php';
/** @var CMain $APPLICATION */
/** @var CUser $USER */
$WORKFLOW_RIGHT = $APPLICATION->GetGroupRight('workflow');
if ($WORKFLOW_RIGHT == 'D')
{
	$APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/workflow/include.php';
IncludeModuleLangFile(__FILE__);
$err_mess = 'File: ' . __FILE__ . '<br>Line: ';


$rs = CSite::GetList();
while ($ar = $rs->Fetch())
{
	$arrSites[$ar['ID']] = $ar;
}

$sTableID = 't_workflow_list';
$oSort = new CAdminSorting($sTableID, 's_date_modify', 'desc');// sort init
$lAdmin = new CAdminList($sTableID, $oSort);// list init

$arFilterFields = [
	'find',
	'find_type',
	'find_id',
	'find_id_exact_match',
	'find_lock_status',
	'find_modify_1',
	'find_modify_2',
	'find_modified_user_id',
	'find_modified_user_id_exact_match',
	'find_site_id',
	'find_filename',
	'find_filename_exact_match',
	'find_title',
	'find_title_exact_match',
	'find_body',
	'find_body_exact_match',
	'find_status',
	'find_status_exact_match',
	'find_status_id',
	'FILTER_logic',
	//"find_modified_by",
];

$lAdmin->InitFilter($arFilterFields);//filter init

$filter = new CAdminFilter(
	$sTableID . '_filter_id',
	[
		GetMessage('FLOW_F_ID'),
		GetMessage('FLOW_F_LOCK_STATUS'),
		GetMessage('FLOW_F_DATE_MODIFY'),
		GetMessage('FLOW_F_MODIFIED_BY'),
		GetMessage('FLOW_F_SITE'),
		GetMessage('FLOW_F_FILENAME'),
		GetMessage('FLOW_F_TITLE'),
		GetMessage('FLOW_F_BODY'),
		GetMessage('FLOW_F_STATUS'),
		GetMessage('FLOW_F_LOGIC'),
	],
);

InitBVar($find_id_exact_match);
InitBVar($find_modified_user_id_exact_match);
InitBVar($find_filename_exact_match);
InitBVar($find_title_exact_match);
InitBVar($find_body_exact_match);
InitBVar($find_status_exact_match);


$arFilter = [
	'ID' => $find_id,
	'DATE_MODIFY_1' => $find_modify_1,
	'DATE_MODIFY_2' => $find_modify_2,
	//"MODIFIED_BY" => ($find_type == "modified_by" && strlen($find)>0 ? $find:$find_modified_by),
	'MODIFIED_USER_ID' => ($find_type == 'modified_by' && $find <> '' ? $find : $find_modified_user_id),
	'LOCK_STATUS' => $find_lock_status,
	'STATUS' => $find_status,
	'STATUS_ID' => $find_status_id,
	'FILENAME' => $find_filename,
	'SITE_ID' => $find_site_id,
	'TITLE' => ($find_type == 'title' && $find <> '' ? $find : $find_title),
	'BODY' => ($find_type == 'body' && $find <> '' ? $find : $find_body),
	'ID_EXACT_MATCH' => $find_id_exact_match,
	'MODIFIED_USER_ID_EXACT_MATCH' => $find_modified_user_id_exact_match,
	'FILENAME_EXACT_MATCH' => $find_filename_exact_match,
	'TITLE_EXACT_MATCH' => $find_title_exact_match,
	'BODY_EXACT_MATCH' => $find_body_exact_match,
	'STATUS_EXACT_MATCH' => $find_status_exact_match,
];

if ($WORKFLOW_RIGHT > 'R' && $lAdmin->EditAction()) // list action handlers
{
	foreach ($FIELDS as $ID => $arFields)
	{
		$ID = intval($ID);

		if (!$lAdmin->IsUpdated($ID))
		{
			continue;
		}

			if (CWorkflow::IsAllowEdit($ID, $locked_by, $date_lock))
			{
				CWorkflow::SetStatus($ID, $arFields['STATUS_ID'], $FIELDS_OLD[$ID]['STATUS_ID']);
				CWorkflow::UnLock($ID);
			}
			else
			{
				if (intval($locked_by) > 0)
				{
					$str = GetMessage('FLOW_DOCUMENT_LOCKED', ['#DID#' => $ID, '#ID#' => $locked_by, '#DATE#' => $date_lock]);
					$lAdmin->AddUpdateError($str, $ID);
				}
				else
				{
					$str = GetMessage('FLOW_DOCUMENT_IS_NOT_AVAILABLE', ['#ID#' => $ID]);
					$lAdmin->AddUpdateError($str, $ID);
				}
			}
	}
}



if ($WORKFLOW_RIGHT > 'R' && $arID = $lAdmin->GroupAction())
{
	if ($_REQUEST['action_target'] == 'selected')
	{
		$rsData = CWorkflow::GetList('', '', $arFilter);
		while ($arRes = $rsData->Fetch())
		{
			$arID[] = $arRes['ID'];
		}
	}

	foreach ($arID as $ID)
	{
		$ID = intval($ID);
		if ($ID <= 0)
		{
			continue;
		}

		switch ($lAdmin->GetAction())
		{
			case 'delete':

			if (CWorkflow::IsAllowEdit($ID, $locked_by, $date_lock))
			{
				CWorkflow::Delete($ID);
			}
			else
			{
				if (intval($locked_by) > 0)
				{
					$str = GetMessage('FLOW_DOCUMENT_LOCKED', ['#DID#' => $ID, '#ID#' => $locked_by, '#DATE#' => $date_lock]);
					$lAdmin->AddGroupError($str, $ID);
				}
				else
				{
					$str = GetMessage('FLOW_DOCUMENT_IS_NOT_AVAILABLE', ['#ID#' => $ID]);
					$lAdmin->AddGroupError($str, $ID);
				}
			}

			break;
			case 'unlock':
				CWorkflow::UnLock($ID);

			break;
		}
	}
}

global $by, $order;

$rsData = CWorkflow::GetList($by, $order, $arFilter);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart(50);

// navigation setup
$lAdmin->NavText($rsData->GetNavPrint(GetMessage('FLOW_PAGES')));

$arHeaders = [
	[
		'id' => 'ID',
		'content' => 'ID',
		'default' => false,
		'sort' => 's_id',
	],
	[
		'id' => 'LOCK_STATUS',
		'content' => GetMessage('FLOW_LOCK_STATUS'),
		'default' => true,
		'sort' => 's_lock_status',
	],
	[
		'id' => 'DATE_MODIFY',
		'content' => GetMessage('FLOW_DATE_MODIFY'),
		'default' => true,
		'sort' => 's_date_modify',
	],
	[
		'id' => 'MODIFIED_BY',
		'content' => GetMessage('FLOW_MODIFIED_BY'),
		'default' => true,
		'sort' => 's_modified_by',
	],
	[
		'id' => 'TITLE',
		'content' => GetMessage('FLOW_TITLE'),
		'default' => true,
		'sort' => 's_title',
	],
	[
		'id' => 'FILENAME',
		'content' => GetMessage('FLOW_FILENAME'),
		'default' => true,
		'sort' => 's_filename',
	],
	[
		'id' => 'STATUS_ID',
		'content' => GetMessage('FLOW_STATUS'),
		'default' => true,
		'sort' => 's_status',
	],
	[
		'id' => 'SITE_ID',
		'content' => GetMessage('FLOW_SITE'),
		'default' => true,
		'sort' => 's_site_id',
	],
];

$lAdmin->AddHeaders($arHeaders);


$arStatus = [];
$res = CWorkflowStatus::GetDropDownList();
while ($ar = $res->Fetch())
{
	$arStatus[$ar['REFERENCE_ID']] = $ar['REFERENCE'];
}

// list fill
while ($arRes = $rsData->NavNext(true, 'f_'))
{
	$row = &$lAdmin->AddRow($f_ID, $arRes);

	if ($f_LOCK_STATUS == 'green')
	{
		$lamp_alt = GetMessage('FLOW_GREEN_ALT');
	}
	elseif ($f_LOCK_STATUS == 'yellow')
	{
		$lamp_alt = GetMessage('FLOW_YELLOW_ALT');
	}
	else
	{
		$lamp_alt = GetMessage('FLOW_RED_ALT');
	}

	$str = '<div class="lamp-' . $f_LOCK_STATUS . '" title="' . $lamp_alt . '"></div>';

	$row->AddViewField('LOCK_STATUS', $str);

	$row->AddViewField('FILENAME', '<a href="' . $f_FILENAME . '">' . $f_FILENAME . '</a>');

	$row->AddSelectField('STATUS_ID', $arStatus);

	$str = '[<a href="user_edit.php?ID=' . $f_MODIFIED_BY . '&lang=' . LANG . '">' . $f_MODIFIED_BY . '</a>]&nbsp;' . $f_MUSER_NAME;
	$row->AddViewField('MODIFIED_BY', $str);

	$arActions = [];

	if ($f_LOCK_STATUS != 'green')
	{
		if (CWorkflow::IsAdmin() || $f_LOCKED_BY == $USER->GetID())
		{
			$arActions[] = [
				'ICON' => 'unlock',
				'TEXT' => GetMessage('FLOW_UNLOCK'),
				'ACTION' => "if(confirm('" . GetMessage('FLOW_UNLOCK_CONFIRM') . "')) " . $lAdmin->ActionDoGroup($f_ID, 'unlock'),
			];

			$arActions[] = ['SEPARATOR' => true];
		}
	}

	if ($f_STATUS_ID != 1)
	{
		$arActions[] = [
			'DEFAULT' => 'Y',
			'ICON' => 'edit',
			'TEXT' => GetMessage('FLOW_EDIT'),
			'ACTION' => $lAdmin->ActionRedirect('workflow_edit.php?lang=' . LANG . '&ID=' . $f_ID),
		];
	}
	else
	{
		$arActions[] = [
			'ICON' => 'view',
			'TEXT' => GetMessage('FLOW_VIEW'),
			'ACTION' => $lAdmin->ActionRedirect('workflow_edit.php?lang=' . LANG . '&ID=' . $f_ID),
		];
	}

	if ($f_STATUS_ID != 1)
	{
		$arActions[] = [
			'ICON' => 'view',
			'TEXT' => GetMessage('FLOW_PREVIEW'),
			'ACTION' => $lAdmin->ActionRedirect('workflow_preview.php?lang=' . LANG . '&ID=' . $f_ID . '&' . bitrix_sessid_get()),
		];
	}

	$arActions[] = [
		'ICON' => 'view',
		'TEXT' => GetMessage('FLOW_HISTORY'),
		'ACTION' => $lAdmin->ActionRedirect('workflow_history_list.php?lang=' . LANG . '&find_document_id=' . $f_ID . '&find_document_id_exact_match=Y&set_filter=Y'),
	];

	$arActions[] = [
		'ICON' => 'view',
		'TEXT' => GetMessage('FLOW_HISTORY_FILE'),
		'ACTION' => $lAdmin->ActionRedirect('workflow_history_list.php?lang=' . LANG . '&find_filename=' . $f_FILENAME . '&find_filename_exact_match=Y&set_filter=Y'),
	];

	if ($f_LOCK_STATUS != 'red' && $WORKFLOW_RIGHT > 'R')
	{
		$arActions[] = ['SEPARATOR' => true];

		$arActions[] = [
			'ICON' => 'delete',
			'TEXT' => GetMessage('FLOW_DELETE'),
			'ACTION' => "if(confirm('" . GetMessage('FLOW_DELETE_CONFIRM') . "')) " . $lAdmin->ActionDoGroup($f_ID, 'delete'),
		];
	}


	$row->AddActions($arActions);
}

if ($WORKFLOW_RIGHT > 'R')
{
	// list footer
	$lAdmin->AddFooter([
		[
			'title' => GetMessage('MAIN_ADMIN_LIST_SELECTED'),
			'value' => $rsData->SelectedRowsCount(),
		],
		[
			'counter' => true,
			'title' => GetMessage('MAIN_ADMIN_LIST_CHECKED'),
			'value' => '0',
		],
	]);
}

// action buttons
$lAdmin->AddGroupActionTable([
	'unlock' => GetMessage('FLOW_UNLOCK_S'),
	'delete' => GetMessage('MAIN_ADMIN_LIST_DELETE'),
],
);



$aContext = [
	[
		'ICON' => 'btn_new',
		'TEXT' => GetMessage('FLOW_ADD'),
		'TITLE' => GetMessage('FLOW_ADD'),
		'LINK' => 'workflow_edit.php?lang=' . LANG,
	],
];

$lAdmin->AddAdminContextMenu($aContext);

$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage('FLOW_PAGE_TITLE'));
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';?>

<form name="form1" method="GET" action="<?php echo $APPLICATION->GetCurPage()?>?">

<?php $filter->Begin();?>
<tr>
	<td><b><?=GetMessage('MAIN_FIND')?>:</b></td>
	<td>
		<input type="text" size="25" name="find" value="<?php echo htmlspecialcharsbx($find)?>" title="<?=GetMessage('MAIN_FIND_TITLE')?>">
		<select name="find_type">
			<option value="title"<?php echo ($find_type == 'title') ? ' selected' : '';?>><?=GetMessage('FLOW_F_TITLE')?></option>
			<option value="body"<?php echo ($find_type == 'body') ? ' selected' : '';?>><?=GetMessage('FLOW_F_BODY')?></option>
			<option value="modified_by"<?php echo ($find_type == 'modified_by') ? ' selected' : '';?>><?=GetMessage('FLOW_F_MODIFIED_BY')?></option>
		</select>
	</td>
</tr>

<tr>
	<td><?=GetMessage('FLOW_F_ID')?>:</td>
	<td><input type="text" name="find_id" size="47" value="<?php echo htmlspecialcharsbx($find_id)?>"><?=ShowExactMatchCheckbox('find_id')?>&nbsp;<?=ShowFilterLogicHelp()?></td>
</tr>
<tr valign="top">
	<td><?=GetMessage('FLOW_F_LOCK_STATUS')?>:</td>
	<td><?php
		$arr = [
			'reference' => [
				GetMessage('FLOW_RED'),
				GetMessage('FLOW_YELLOW'),
				GetMessage('FLOW_GREEN')
			],
			'reference_id' => [
				'red',
				'yellow',
				'green'
			]
		];
		echo SelectBoxFromArray('find_lock_status', $arr, htmlspecialcharsbx($find_lock_status), GetMessage('MAIN_ALL'));
	?></td>
</tr>
<tr>
	<td><?php echo GetMessage('FLOW_F_DATE_MODIFY') . ':'?></td>
	<td><?php echo CalendarPeriod('find_modify_1', $find_modify_1, 'find_modify_2', $find_modify_2, 'form1', 'Y')?></td>
</tr>
<tr>
	<td><?=GetMessage('FLOW_F_MODIFIED_BY')?>:</td>
	<td><input type="text" name="find_modified_user_id" value="<?php echo htmlspecialcharsbx($find_modified_user_id)?>" size="47"><?=ShowExactMatchCheckbox('find_modified_user_id')?>&nbsp;<?=ShowFilterLogicHelp()?></td>
</tr>
<tr>
	<td><?php echo GetMessage('FLOW_LIST_SITE')?>:</td>
	<td><?=CSite::SelectBox('find_site_id', $find_site_id, GetMessage('FLOW_LIST_SITE_ALL'));?></td>
</tr>
<tr>
	<td><?=GetMessage('FLOW_F_FILENAME')?>:</td>
	<td><input type="text" name="find_filename" value="<?php echo htmlspecialcharsbx($find_filename)?>" size="47"><?=ShowExactMatchCheckbox('find_filename')?>&nbsp;<?=ShowFilterLogicHelp()?></td>
</tr>
<tr>
	<td><?=GetMessage('FLOW_F_TITLE')?>:</td>
	<td><input type="text" name="find_title" value="<?php echo htmlspecialcharsbx($find_title)?>" size="47"><?=ShowExactMatchCheckbox('find_title')?>&nbsp;<?=ShowFilterLogicHelp()?></td>
</tr>
<tr>
	<td><?=GetMessage('FLOW_F_BODY')?>:</td>
	<td><input type="text" name="find_body" value="<?php echo htmlspecialcharsbx($find_body)?>" size="47"><?=ShowExactMatchCheckbox('find_body')?>&nbsp;<?=ShowFilterLogicHelp()?></td>
</tr>
<tr>
	<td><?=GetMessage('FLOW_F_STATUS')?>:</td>
	<td><input type="text" name="find_status" value="<?php echo htmlspecialcharsbx($find_status)?>" size="47"><?=ShowExactMatchCheckbox('find_status')?>&nbsp;<?=ShowFilterLogicHelp()?><br><?php
	echo SelectBox('find_status_id', CWorkflowStatus::GetDropDownList(), GetMessage('MAIN_ALL'), htmlspecialcharsbx($find_status_id));
	?></td>
</tr>
<?=ShowLogicRadioBtn()?>

<?php
$filter->Buttons([
	'table_id' => $sTableID,
	'url' => $APPLICATION->GetCurPage(),
	'form' => 'form1',
]);
$filter->End();?>
</form>

<?php $lAdmin->DisplayList();?>

<?php echo BeginNote();?>
<table border="0" width="100%" cellspacing="5" cellpadding="3">
	<tr>
		<td><div class="lamp-green"></div></td>
		<td nowrap><?php echo GetMessage('FLOW_GREEN_ALT')?></td>
	</tr>
	<tr>
		<td><div class="lamp-yellow"></div></td>
		<td nowrap><?php echo GetMessage('FLOW_YELLOW_ALT')?></td>
	</tr>
	<tr>
		<td><div class="lamp-red"></div></td>
		<td nowrap><?php echo GetMessage('FLOW_RED_ALT')?></td>
	</tr>
</table>
<?php echo EndNote();?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
