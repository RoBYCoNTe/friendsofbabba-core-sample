<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BlogPostComment Entity
 *
 * @property int $id
 * @property int $blog_post_id
 * @property string $comment_text
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\BlogPost $blog_post
 */
class BlogPostComment extends Entity
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
        'blog_post_id' => true,
        'comment_text' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'blog_post' => true,
        'notes' => true,
        'state' => true,
        'is_private' => true,
        'transaction' => true,
    ];

}
