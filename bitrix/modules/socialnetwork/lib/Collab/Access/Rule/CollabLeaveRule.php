<?php

declare(strict_types=1);

namespace Bitrix\SocialNetwork\Collab\Access\Rule;

use Bitrix\Main\Access\AccessibleItem;
use Bitrix\Main\Access\Rule\AbstractRule;
use Bitrix\Socialnetwork\Permission\GroupAccessController;
use Bitrix\Socialnetwork\Permission\GroupDictionary;
use Bitrix\Socialnetwork\Permission\User\UserModel;
use Bitrix\SocialNetwork\Collab\Access\CollabAccessController;
use Bitrix\SocialNetwork\Collab\Access\Model\CollabModel;

class CollabLeaveRule extends AbstractRule
{
	/** @var CollabAccessController */
	protected $controller;

	/** @var UserModel */
	protected $user;

	public function execute(AccessibleItem $item = null, $params = null): bool
	{
		if (!$item instanceof CollabModel)
		{
			$this->controller->addError(static::class, 'Wrong instance');

			return false;
		}

		if ($this->user->isCollaber())
		{
			$this->controller->addError(static::class, 'Access denied by collaber role');

			return false;
		}

		if (!$this->controller->forward(GroupAccessController::class, GroupDictionary::LEAVE, $item, $params))
		{
			$this->controller->addError(static::class, 'Access denied by group controller');

			return false;
		}

		return true;
	}
}