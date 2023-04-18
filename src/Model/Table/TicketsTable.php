<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use FriendsOfBabba\Core\Model\Entity\User;
use FriendsOfBabba\Core\Model\Crud\Form;
use FriendsOfBabba\Core\Model\Crud\FormInput;
use FriendsOfBabba\Core\Model\Crud\Grid;
use SoftDelete\Model\Table\SoftDeleteTrait;
use FriendsOfBabba\Core\Model\Table\BaseTable;

/**
 * Tickets Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Ticket newEmptyEntity()
 * @method \App\Model\Entity\Ticket newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Ticket[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Ticket get($primaryKey, $options = [])
 * @method \App\Model\Entity\Ticket findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Ticket patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Ticket[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Ticket|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ticket saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ticket[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ticket[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ticket[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ticket[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TicketsTable extends BaseTable
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

        $this->setTable('tickets');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Search.Search', ['collectionClass' => \App\Model\Filter\TicketCollection::class]);
        $this->addBehavior('FriendsOfBabba/Core.Media', ['media']);

        // Worflow relationships
        // Relationship with current (last) transaction for Tickets
        $this->hasOne('Transactions', [
            'foreignKey' => 'record_id',
            'className' => 'TicketTransactions',
            'propertyName' => 'transaction',
            'dependent' => true,
            'conditions' => ['Transactions.is_current' => true]
        ]);

        // Relationship with all transaction for Tickets
        $this->hasMany('AllTransactions', [
            'foreignKey' => 'record_id',
            'className' => 'TicketTransactions',
            'propertyName' => 'all_transactions',
            'dependent' => true
        ]);
        $this->belongsTo('Users', [
            'className' => 'FriendsOfBabba/Core.Users',
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('TicketTypes', [
            'foreignKey' => 'ticket_type_id',
            'joinType' => 'LEFT',
        ]);

        $this->belongsToMany('Media', [
            'className' => 'FriendsOfBabba/Core.Media',
            'foreignKey' => 'ticket_id',
            'joinTable' => 'tickets_media',
            'joinType' => 'LEFT',
            'targetForeignKey' => 'media_id',
            'propertyName' => 'media',
            'dependent' => true
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
            ->scalar('subject')
            ->maxLength('subject', 100)
            ->requirePresence('subject', 'create')
            ->notEmptyString('subject');

        $validator
            ->scalar('content')
            ->maxLength('content', 500)
            ->requirePresence('content', 'create')
            ->notEmptyString('content');

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

        return $rules;
    }

    public function getForm(?User $user, bool $extends = TRUE): ?Form
    {
        $form = parent::getForm($user, $extends);
        $form->setRedirect(Form::REDIRECT_EDIT);
        $form->getInput("user_id")
            ->setComponent("ReferenceAutocompleteInput")
            ->setComponentProp("reference", "users")
            ->setComponentProp("optionText", "name");

        $form->getInput("ticket_type_id")
            ->setComponent("ReferenceSelectInput")
            ->setComponentProp("reference", "ticket-types")
            ->setComponentProp("optionText", "name");

        $form->getInput("subject")
            ->setComponent("CountableTextInput")
            ->setComponentProp("maxLength", 100);

        $form->getInput("content")
            ->setComponent("CountableTextInput")
            ->setComponentProp("maxLength", 500)
            ->setComponentProp("multiline", true)
            ->fullWidth();

        $form->addInput(FormInput::create('media', __("Media"))
            ->setComponent("MediaInput")
            ->setComponentProp("title", "filename")
            ->setComponentProp("multiple", true)
            ->setUseWorkflow(true), "after", "content");

        return $form;
    }

    public function getGrid(?User $user, bool $extends = TRUE): ?Grid
    {
        $grid = parent::getGrid($user, $extends);
        $grid->getField("user_id")->setSource("user.name");
        $grid->getField("ticket_type_id")->setSource("ticket_type.name");
        $grid->getField('subject')->setComponent("LongTextField");
        $grid->getField('content')->setComponent("LongTextField");

        return $grid;
    }
}
