<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class BlogPostSlug extends AbstractMigration
{
    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up()
    {

        $this->table('blog_posts')
            ->addColumn('slug', 'string', [
                'after' => 'title',
                'default' => null,
                'length' => 100,
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

        $this->table('blog_posts')
            ->removeColumn('slug')
            ->update();
    }
}
