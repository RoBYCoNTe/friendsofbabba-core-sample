<?php

namespace App\Controller\Api\Extender;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Log\Log;
use FriendsOfBabba\Core\Controller\BaseControllerExtender;

class UsersControllerExtender extends BaseControllerExtender
{
	public function beforePaginate(Event $event, Controller $controller)
	{
		Log::debug('UsersControllerExtender::beforePaginate');
	}
	public function afterPaginate(Event $event, Controller $controller)
	{
		Log::debug('UsersControllerExtender::afterPaginate');
	}

	public function beforeFind(Event $event, Controller $controller)
	{
		Log::debug('UsersControllerExtender::beforeFind');
	}

	public function beforeSave(Event $event, Controller $controller)
	{
		Log::debug('UsersControllerExtender::beforeSave');
	}

	public function afterSave(Event $event, Controller $controller)
	{
		Log::debug('UsersControllerExtender::afterSave');
	}

	public function beforeDelete(Event $event, Controller $controller)
	{
		Log::debug('UsersControllerExtender::beforeDelete');
	}
}
