<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class BlogPostComments extends AbstractMigration
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
        $this->table('blog_post_comments')
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
            ->addColumn('comment_text', 'string', [
                'default' => null,
                'limit' => 1000,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('deleted', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'blog_post_id',
                ]
            )
            ->create();

        $this->table('blog_post_comments')
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

        $this->table('user_profiles')
            ->addColumn('phone', 'string', [
                'after' => 'surname',
                'default' => null,
                'length' => 30,
                'null' => false,
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
        $this->table('blog_post_comments')
            ->dropForeignKey(
                'blog_post_id'
            )->save();

        $this->table('user_profiles')
            ->removeColumn('phone')
            ->update();

        $this->table('blog_post_comments')->drop()->save();
    }
}
