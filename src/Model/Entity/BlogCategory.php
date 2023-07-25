<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BlogCategory Entity
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int|null $order_index
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 */
class BlogCategory extends Entity
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
        'code' => true,
        'name' => true,
        'order_index' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'notes' => true,
        'state' => true,
        'is_private' => true,
        'transaction' => true,
    ];
}
