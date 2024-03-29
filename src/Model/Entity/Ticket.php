<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Ticket Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $subject
 * @property string $content
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\User $user
 */
class Ticket extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'ticket_type_id' => true,
        'subject' => true,
        'content' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'user' => true,
        'media' => true,
        'ticket_type' => true,

        'notes' => true,
        'state' => true,
        'is_private' => true,
        'transaction' => true,
    ];
}
