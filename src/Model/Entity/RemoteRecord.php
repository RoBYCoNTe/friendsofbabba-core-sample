<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RemoteRecord Entity
 *
 * @property int $id
 * @property string $field_1
 * @property int $field_2
 * @property string|null $field_3
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class RemoteRecord extends Entity
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
        'field_1' => true,
        'field_2' => true,
        'field_3' => true,
        'created' => true,
        'modified' => true,
        'notes' => true,
        'state' => true,
        'is_private' => true,
        'transaction' => true,
    ];

}
