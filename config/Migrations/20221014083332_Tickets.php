<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Tickets extends AbstractMigration
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
        $this->table('ticket_transactions')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('record_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('state', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('notes', 'string', [
                'default' => null,
                'limit' => 4000,
                'null' => true,
            ])
            ->addColumn('is_current', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('is_private', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('data', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
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
                    'user_id',
                ]
            )
            ->addIndex(
                [
                    'record_id',
                ]
            )
            ->create();

        $this->table('ticket_types')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
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
            ->create();

        $this->table('tickets')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('ticket_type_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('subject', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('content', 'string', [
                'default' => null,
                'limit' => 500,
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
                    'user_id',
                ]
            )
            ->addIndex(
                [
                    'ticket_type_id',
                ]
            )
            ->create();

        $this->table('tickets_media')
            ->addColumn('ticket_id', 'integer', [
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
            ->addPrimaryKey(['ticket_id', 'media_id'])
            ->addIndex(
                [
                    'media_id',
                ]
            )
            ->addIndex(
                [
                    'ticket_id',
                ]
            )
            ->create();

        $this->table('ticket_transactions')
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT',
                ]
            )
            ->addForeignKey(
                'record_id',
                'tickets',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT',
                ]
            )
            ->update();

        $this->table('tickets')
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT',
                ]
            )
            ->addForeignKey(
                'ticket_type_id',
                'ticket_types',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT',
                ]
            )
            ->update();

        $this->table('tickets_media')
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
                'ticket_id',
                'tickets',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT',
                ]
            )
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
        $this->table('ticket_transactions')
            ->dropForeignKey(
                'user_id'
            )
            ->dropForeignKey(
                'record_id'
            )->save();

        $this->table('tickets')
            ->dropForeignKey(
                'user_id'
            )
            ->dropForeignKey(
                'ticket_type_id'
            )->save();

        $this->table('tickets_media')
            ->dropForeignKey(
                'media_id'
            )
            ->dropForeignKey(
                'ticket_id'
            )->save();

        $this->table('ticket_transactions')->drop()->save();
        $this->table('ticket_types')->drop()->save();
        $this->table('tickets')->drop()->save();
        $this->table('tickets_media')->drop()->save();
    }
}
