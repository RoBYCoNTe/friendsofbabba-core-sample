<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use SoftDelete\Model\Table\SoftDeleteTrait;
use FriendsOfBabba\Core\Model\Table\BaseTable;

/**
 * TicketTransactions Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\TicketsTable&\Cake\ORM\Association\BelongsTo $Tickets
 *
 * @method \App\Model\Entity\TicketTransaction newEmptyEntity()
 * @method \App\Model\Entity\TicketTransaction newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\TicketTransaction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TicketTransaction get($primaryKey, $options = [])
 * @method \App\Model\Entity\TicketTransaction findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\TicketTransaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TicketTransaction[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\TicketTransaction|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TicketTransaction saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TicketTransaction[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TicketTransaction[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\TicketTransaction[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TicketTransaction[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TicketTransactionsTable extends BaseTable
{
    use SoftDeleteTrait;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('ticket_transactions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Search.Search', ['collectionClass' => \App\Model\Filter\TicketTransactionCollection::class]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Tickets', [
            'foreignKey' => 'record_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->nonNegativeInteger('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->nonNegativeInteger('record_id')
            ->requirePresence('record_id', 'create')
            ->notEmptyString('record_id');

        $validator
            ->scalar('state')
            ->maxLength('state', 255)
            ->requirePresence('state', 'create')
            ->notEmptyString('state');

        $validator
            ->scalar('notes')
            ->maxLength('notes', 4000)
            ->allowEmptyString('notes');

        $validator
            ->boolean('is_current')
            ->notEmptyString('is_current');

        $validator
            ->boolean('is_private')
            ->notEmptyString('is_private');

        $validator
            ->scalar('data')
            ->allowEmptyString('data');

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');


        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);
        $rules->add($rules->existsIn(['record_id'], 'Tickets'), ['errorField' => 'record_id']);

        return $rules;
    }
}
