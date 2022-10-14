<?php

namespace App\Workflow\Tickets;

use Cake\Event\Event;
use FriendsOfBabba\Core\Model\Entity\User;
use FriendsOfBabba\Core\Workflow\WorkflowBase;

use App\Workflow\Tickets\States\Draft;
use App\Workflow\Tickets\States\Working;
use App\Workflow\Tickets\States\Closed;

class Workflow extends WorkflowBase
{
    public function init()
    {
        $this->addState(new Draft());
        $this->addState(new Working());
        $this->addState(new Closed());

        $this->getState(Draft::CODE)->addTransitionTo($this->getState(Working::CODE));
        $this->getState(Working::CODE)->addTransitionTo($this->getState(Closed::CODE));
    }

    public function beforePaginate(string $entityName, User $user, Event $event): void
    {
        parent::beforePaginate($entityName, $user, $event);
    }
}
