<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use FriendsOfBabba\Core\Export\Crud\CrudExcelDocument;
use FriendsOfBabba\Core\Model\Crud\Filter;
use FriendsOfBabba\Core\Model\Entity\User;
use FriendsOfBabba\Core\Model\Crud\Form;
use FriendsOfBabba\Core\Model\Crud\FormInput;
use FriendsOfBabba\Core\Model\Crud\Grid;
use FriendsOfBabba\Core\Model\Crud\GridField;
use FriendsOfBabba\Core\Model\CrudFactory;
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
        $this->addBehavior('FriendsOfBabba/Core.Media', [
            'Media',
            'Thumbnail'
        ]);
        $this->addBehavior('FriendsOfBabba/Core.DateTime', [
            'published'
        ]);


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

        $this->belongsTo('Authors', [
            'foreignKey' => 'author_id',
            'joinType' => 'INNER',
            'className' => 'FriendsOfBabba/Core.Users'
        ]);

        $this->belongsToMany('Media', [
            'className' => 'FriendsOfBabba/Core.Media',
            'foreignKey' => 'blog_post_id',
            'joinTable' => 'blog_posts_media',
            'joinType' => 'LEFT',
            'targetForeignKey' => 'media_id',
            'propertyName' => 'media',
            'dependent' => true
        ]);
        $this->belongsTo('Thumbnails', [
            'className' => 'FriendsOfBabba/Core.Media',
            'foreignKey' => 'thumbnail_media_id',
            'joinType' => 'LEFT',
            'propertyName' => 'thumbnail',
        ]);
        $this->belongsToMany('Users', [
            'className' => 'FriendsOfBabba/Core.Users',
            'foreignKey' => 'blog_post_id',
            'joinTable' => 'blog_posts_users',
            'joinType' => 'LEFT',
            'targetForeignKey' => 'user_id',
            'propertyName' => 'users',
            'dependent' => true,
        ]);

        // Relationship with all transaction for BlogPosts
        $this->hasMany('AllTransactions', [
            'foreignKey' => 'record_id',
            'className' => 'BlogPostTransactions',
            'propertyName' => 'all_transactions',
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

        $validator->add('users', 'custom', [
            'rule' => function ($value, $context) {
                return count($value) > 0;
            },
            'message' => __('You must select at least one user'),
        ]);


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
        $grid->setTitle(__("Posts"));
        $grid->addExporter("xlsx", new CrudExcelDocument($grid));
        $grid->setMobilePrimaryText("title");
        $grid->setMobileSecondaryText("content");
        $grid->setMobileLinkType("edit");
        $grid->setRowClick("edit");
        $grid->getField("author_id")->setSource("author.name");
        $grid->getField("title")
            ->setComponent("LongTextField")
            ->setComponentProp("minWidth", 300);
        $grid->removeField("content");
        $grid->removeField("thumbnail_media_id");
        $grid->addField(GridField::create("blog_categories", "Categories")
            ->setComponent("ChipArrayField")
            ->setComponentProp("chipSource", "name"), "after", "title");

        $grid->addField(GridField::create("completed_fields_perc", __("Completed"), "ProgressField"), "after", "published");

        $grid->addFilter(Filter::create("date_range", __("Date range"), "DateRangeInput")->alwaysOn());
        $grid->addFilterDefaultValue("date_range", "today");
        $grid->addFilterDefaultValue("start_at", date("Y-m-d"));
        $grid->addFilterDefaultValue("end_at", date("Y-m-d"));
        return $grid;
    }

    public function getForm(?User $user, bool $extends = TRUE): ?Form
    {
        $form = parent::getForm($user, $extends);
        $form->removeInput("thumbnail_media_id");
        $form->setRedirect("edit");
        $form->getInput("author_id")
            ->setComponent("ReferenceAutocompleteInput")
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
            ->setUseWorkflow()
            ->fullWidth(), "after", "content");

        $form->addInput(
            FormInput::create("blog_post_comments", "Comments")
                ->setComponent("ReferenceListField")
                ->setComponentProp("sort", [
                    "field" => "BlogPostComments.created",
                    "order" => "desc"
                ])
                ->setUseWorkflow()
                ->setComponentProp("empty", __("No comment added"))
                ->setComponentProp("sorry", __("You have to save post one time before you can add comments"))
                ->setComponentProp("perPage", 5)
                ->setComponentProp("reference", "blog-post-comments")
                ->setComponentProp("target", "blog_post_id")
                ->setComponentProp("mobilePrimaryText", "comment_text")
                ->setComponentProp("mobileSecondaryText", "created")
                ->setComponentProp("mobileSecondaryComponent", "DateField")
                ->setComponentProp("mobileSecondaryComponentProps", ["showTime" => true])
                ->setComponentProp("columns", [
                    GridField::create("comment_text", __("Comment"))
                        ->setSortBy("BlogPostComments.comment_text")
                        ->setComponent("LongTextField"),

                    // Random fields necessary to simulate long lists with responsive behavior
                    // GridField::create("random_text_1", __("Random Text 1"))
                    //     ->setComponent("LongTextField")
                    //     ->setSortable(FALSE),
                    // GridField::create("random_text_2", __("Random Text 2"))
                    //     ->setComponent("LongTextField")
                    //     ->setSortable(FALSE),
                    // GridField::create("random_text_3", __("Random Text 3"))
                    //     ->setComponent("LongTextField")
                    //     ->setSortable(FALSE),
                    // GridField::create("random_date_1", __("Random Date 1"))
                    //     ->setComponent("DateField")
                    //     ->setSortable(FALSE),
                    // GridField::create("random_numb_1", __("Random Number 1"))
                    //     ->setComponent("TextField")
                    //     ->setSortable(FALSE),

                    GridField::create("created", __("Created"))
                        ->setSortBy("BlogPostComments.created")
                        ->setComponent("DateField")
                        ->setComponentProp("showTime", true)
                ])
                ->fullWidth(),
            "after",
            "content"
        );
        $form->addInput(FormInput::create('media', __("Media Collection"))
            ->setComponent("MediaInput")
            ->setComponentProp("title", "filename")
            ->setComponentProp("multiple", true)
            ->setComponentProp("empty", __("No media added"))
            ->setUseWorkflow(), "after", "blog_post_comments");

        $form->addInput(FormInput::create("thumbnail", __("Thumbnail"))
            ->setComponent("MediaInput")
            ->setComponentProp("title", "filename")
            ->setComponentProp("accept", "image/*")
            ->setComponentProp("multiple", false)
            ->setComponentProp("empty", __("No thumbnail"))
            ->setUseWorkflow(), "after", "title");

        $form->addInput(FormInput::create("users", __("Users"))
            ->setComponent("UsersInput")
            ->setComponentProp("helperText", null)
            ->setUseWorkflow(), 'after', 'media');
        return $form;
    }
}
