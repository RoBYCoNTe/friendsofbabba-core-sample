<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class BlogCategoriesOrderIndex extends AbstractMigration
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
        $this->table('blog_categories')
            ->addColumn('order_index', 'integer', [
                'after' => 'name',
                'default' => '0',
                'length' => null,
                'null' => true,
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
        $this->table('blog_categories')
            ->removeColumn('order_index')
            ->update();
    }
}
