<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use FriendsOfBabba\Core\Export\Crud\CrudExcelDocument;
use FriendsOfBabba\Core\Model\Entity\User;
use FriendsOfBabba\Core\Model\Crud\Form;
use FriendsOfBabba\Core\Model\Crud\FormInput;
use FriendsOfBabba\Core\Model\Crud\Grid;
use FriendsOfBabba\Core\Model\Crud\GridField;
use SoftDelete\Model\Table\SoftDeleteTrait;
use FriendsOfBabba\Core\Model\Table\BaseTable;

/**
 * BlogPosts Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\BlogPost newEmptyEntity()
 * @method \App\Model\Entity\BlogPost newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\BlogPost[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BlogPost get($primaryKey, $options = [])
 * @method \App\Model\Entity\BlogPost findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\BlogPost patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPost[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPost|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogPost saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogPost[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\BlogPost[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\BlogPost[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\BlogPost[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BlogPostsTable extends BaseTable
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

        $this->setTable('blog_posts');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Search.Search', ['collectionClass' => \App\Model\Filter\BlogPostCollection::class]);


        // Worflow relationships
        // Relationship with current (last) transaction for BlogPosts
        $this->hasOne('Transactions', [
            'foreignKey' => 'record_id',
            'className' => 'BlogPostTransactions',
            'propertyName' => 'transaction',
            'dependent' => true,
            'conditions' => ['Transactions.is_current' => true]
        ]);

        $this->belongsToMany('BlogCategories', [
            'foreignKey' => 'blog_post_id',
            'targetForeignKey' => 'blog_category_id',
            'joinTable' => 'blog_posts_blog_categories',
            'propertyName' => 'blog_categories',
            'dependent' => true
        ]);

        // Relationship with all transaction for BlogPosts
        $this->hasMany('AllTransactions', [
            'foreignKey' => 'record_id',
            'className' => 'BlogPostTransactions',
            'propertyName' => 'all_transactions',
            'dependent' => true
        ]);
        $this->belongsTo('Authors', [
            'foreignKey' => 'author_id',
            'joinType' => 'INNER',
            'className' => 'FriendsOfBabba/Core.Users'
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
        $validator->requirePresence('author_id');

        $validator
            ->scalar('title')
            ->maxLength('title', 100)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('content')
            ->allowEmptyString('content');

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
        $rules->add($rules->existsIn(['author_id'], 'Authors'), ['errorField' => 'author_id']);

        return $rules;
    }

    public function getGrid(?User $user, bool $extends = TRUE): ?Grid
    {
        $grid = parent::getGrid($user, $extends);
        // Add custom exporter for data.
        $grid->addExporter("xlsx", new CrudExcelDocument($grid));
        $grid->setMobilePrimaryText("title");
        $grid->setMobileSecondaryText("content");
        $grid->setMobileLinkType("edit");
        $grid->setRowClick("edit");
        $grid->getField("author_id")->setSource("author.name");
        $grid->removeField("content");
        $grid->addField(GridField::create("blog_categories", "Categories")
            ->setComponent("ChipArrayField")
            ->setComponentProp("chipSource", "name"), "after", "content");

        return $grid;
    }

    public function getForm(?User $user, bool $extends = TRUE): ?Form
    {
        $form = parent::getForm($user, $extends);
        $form->getInput("author_id")
            ->setComponent("ReferenceSelectInput")
            ->setComponentProp("reference", "users")
            ->setComponentProp("optionText", "name");
        $form->getInput("title")
            ->setComponent("DebouncedTextInput")
            ->setComponentProp("maxLength", 100)
            ->fullWidth();
        $form->getInput("content")
            ->setComponent("DebouncedTextInput")
            ->setComponentProp("maxLength", 1000)
            ->setComponentProp("multiline", true)
            ->fullWidth();

        $form->addInput(FormInput::create("blog_categories", "Categories")
            ->setComponent("ReferenceCheckboxGroupInput")
            ->setComponentProp("reference", "blog-categories")
            ->setComponentProp("optionText", "name")
            ->fullWidth(), "after", "content");

        $form->addInput(
            FormInput::create("blog_post_comments", "Comments")
                ->setComponent("BlogPostCommentsField")
                ->fullWidth(),
            "after",
            "content"
        );
        return $form;
    }
}
