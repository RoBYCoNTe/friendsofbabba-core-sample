<?php

namespace App\Workflow\BlogPosts;

use Cake\Event\Event;
use FriendsOfBabba\Core\Model\Entity\User;
use FriendsOfBabba\Core\Workflow\WorkflowBase;

use App\Workflow\BlogPosts\States\Draft;
use App\Workflow\BlogPosts\States\Published;

class Workflow extends WorkflowBase
{
    public function init()
    {
        $this->addState(new Draft());
        $this->addState(new Published());

        $this->getState(Draft::CODE)->addTransitionTo($this->getState(Published::CODE));
        $this->getState(Published::CODE)->addTransitionTo($this->getState(Draft::CODE));
    }

    public function beforePaginate(string $entityName, User $user, Event $event): void
    {
        parent::beforePaginate($entityName, $user, $event);
    }
}
