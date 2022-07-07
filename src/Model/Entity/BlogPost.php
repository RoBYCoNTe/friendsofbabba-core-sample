<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BlogPost Entity
 *
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property string|null $content
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\User $user
 */
class BlogPost extends Entity
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
        'author_id' => true,
        'title' => true,
        'content' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'media' => true,
        'thumbnail' => true,
        'notes' => true,
        'state' => true,
        'is_private' => true,
        'transaction' => true,
        'blog_categories' => true
    ];
}
