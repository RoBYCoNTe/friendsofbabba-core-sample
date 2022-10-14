<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TicketTransaction Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $record_id
 * @property string $state
 * @property string|null $notes
 * @property bool $is_current
 * @property bool $is_private
 * @property string|null $data
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Ticket $ticket
 */
class TicketTransaction extends Entity
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
        'record_id' => true,
        'state' => true,
        'notes' => true,
        'is_current' => true,
        'is_private' => true,
        'data' => true,
        'created' => true,
        'deleted' => true,
        'user' => true,
        'ticket' => true,
        'transaction' => true,
    ];

}
