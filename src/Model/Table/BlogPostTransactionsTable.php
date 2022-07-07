<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use SoftDelete\Model\Table\SoftDeleteTrait;
use FriendsOfBabba\Core\Model\Table\BaseTable;

/**
 * BlogPostTransactions Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\BlogPostsTable&\Cake\ORM\Association\BelongsTo $BlogPosts
 *
 * @method \App\Model\Entity\BlogPostTransaction newEmptyEntity()
 * @method \App\Model\Entity\BlogPostTransaction newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostTransaction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostTransaction get($primaryKey, $options = [])
 * @method \App\Model\Entity\BlogPostTransaction findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\BlogPostTransaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostTransaction[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostTransaction|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogPostTransaction saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogPostTransaction[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\BlogPostTransaction[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\BlogPostTransaction[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\BlogPostTransaction[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BlogPostTransactionsTable extends BaseTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('blog_post_transactions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Search.Search', ['collectionClass' => \App\Model\Filter\BlogPostTransactionCollection::class]);


        // Worflow relationships
        // Relationship with current (last) transaction for BlogPostTransactions
        $this->hasOne('Transactions', [
            'foreignKey' => 'record_id',
            'className' => 'BlogPostTransactionTransactions',
            'propertyName' => 'transaction',
            'dependent' => true,
            'conditions' => ['Transactions.is_current' => true]
        ]);

        // Relationship with all transaction for BlogPostTransactions
        $this->hasMany('AllTransactions', [
            'foreignKey' => 'record_id',
            'className' => 'BlogPostTransactionTransactions',
            'propertyName' => 'all_transactions',
            'dependent' => true
        ]);
        $this->belongsTo('Users', [
            'className' => 'FriendsOfBabba/Core.Users',
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('BlogPosts', [
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
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

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
        $rules->add($rules->existsIn(['record_id'], 'BlogPosts'), ['errorField' => 'record_id']);

        return $rules;
    }
}
