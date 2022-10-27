<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BlogPostsUser Entity
 *
 * @property int $id
 * @property int $blog_post_id
 * @property int $user_id
 *
 * @property \App\Model\Entity\BlogPost $blog_post
 * @property \App\Model\Entity\User $user
 */
class BlogPostsUser extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'id' => true,
        'blog_post_id' => true,
        'user_id' => true,
        'blog_post' => true,
        'user' => true,
    ];
}
