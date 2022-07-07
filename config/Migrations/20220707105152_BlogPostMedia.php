<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class BlogPostMedia extends AbstractMigration
{
    public $autoId = false;

    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up()
    {
        $this->table('blog_posts_media')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('blog_post_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('media_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(
                [
                    'media_id',
                ]
            )
            ->addIndex(
                [
                    'blog_post_id',
                ]
            )
            ->create();

        $this->table('blog_posts_media')
            ->addForeignKey(
                'media_id',
                'media',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT',
                ]
            )
            ->addForeignKey(
                'blog_post_id',
                'blog_posts',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT',
                ]
            )
            ->update();

        $this->table('blog_posts')
            ->addColumn('thumbnail_media_id', 'integer', [
                'after' => 'author_id',
                'default' => null,
                'length' => null,
                'null' => true,
                'signed' => false,
            ])
            ->update();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down()
    {
        $this->table('blog_posts_media')
            ->dropForeignKey(
                'media_id'
            )
            ->dropForeignKey(
                'blog_post_id'
            )->save();

        $this->table('blog_posts')
            ->removeColumn('thumbnail_media_id')
            ->update();

        $this->table('blog_posts_media')->drop()->save();
    }
}
