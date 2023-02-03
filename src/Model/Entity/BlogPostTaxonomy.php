<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BlogPostTaxonomy Entity
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string $slug
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\ParentBlogPostTaxonomy $parent_blog_post_taxonomy
 * @property \App\Model\Entity\ChildBlogPostTaxonomy[] $child_blog_post_taxonomies
 */
class BlogPostTaxonomy extends Entity
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
        'parent_id' => true,
        'name' => true,
        'slug' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'parent_blog_post_taxonomy' => true,
        'child_blog_post_taxonomies' => true,
        'notes' => true,
        'state' => true,
        'is_private' => true,
        'transaction' => true,
    ];

}
