<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use FriendsOfBabba\Core\Model\Crud\Form;
use FriendsOfBabba\Core\Model\Crud\FormInput;
use FriendsOfBabba\Core\Model\Entity\User;
use FriendsOfBabba\Core\Model\Crud\Grid;
use FriendsOfBabba\Core\Model\Crud\GridField;
use SoftDelete\Model\Table\SoftDeleteTrait;
use FriendsOfBabba\Core\Model\Table\BaseTable;

/**
 * BlogPostTaxonomies Model
 *
 * @property \App\Model\Table\BlogPostTaxonomiesTable&\Cake\ORM\Association\BelongsTo $ParentBlogPostTaxonomies
 * @property \App\Model\Table\BlogPostTaxonomiesTable&\Cake\ORM\Association\HasMany $ChildBlogPostTaxonomies
 *
 * @method \App\Model\Entity\BlogPostTaxonomy newEmptyEntity()
 * @method \App\Model\Entity\BlogPostTaxonomy newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostTaxonomy[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostTaxonomy get($primaryKey, $options = [])
 * @method \App\Model\Entity\BlogPostTaxonomy findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\BlogPostTaxonomy patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostTaxonomy[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostTaxonomy|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogPostTaxonomy saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogPostTaxonomy[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\BlogPostTaxonomy[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\BlogPostTaxonomy[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\BlogPostTaxonomy[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BlogPostTaxonomiesTable extends BaseTable
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

        $this->setTable('blog_post_taxonomies');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Search.Search', ['collectionClass' => \App\Model\Filter\BlogPostTaxonomyCollection::class]);


        $this->belongsTo('ParentBlogPostTaxonomies', [
            'className' => 'BlogPostTaxonomies',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('ChildBlogPostTaxonomies', [
            'className' => 'BlogPostTaxonomies',
            'foreignKey' => 'parent_id',
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
            ->nonNegativeInteger('parent_id')
            ->allowEmptyString('parent_id');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 100)
            ->requirePresence('slug', 'create')
            ->notEmptyString('slug');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentBlogPostTaxonomies'), ['errorField' => 'parent_id']);

        return $rules;
    }

    public function getGrid(?User $user, bool $extends = TRUE): ?Grid
    {
        $grid = parent::getGrid($user, $extends);
        $grid->addPermanentFilter("only_roots", true);
        $grid->removeField("parent_id");
        $grid->setRowClick("edit");
        return $grid;
    }

    public function getForm(?User $user, bool $extends = TRUE): ?Form
    {
        $form = parent::getForm($user, $extends);

        $form->setTitle(__("Blog Post Taxonomies"));
        $form->setRedirect("list");
        // $form->setRefresh(true);
        $form->setToolbarComponentProp("backReferenceTarget", "parent_id");
        $form->setToolbarComponentProp("backReference", "blog-post-taxonomies");
        $form->setToolbarComponentProp("backTab", null);

        $form->removeInput("parent_id");
        $form->getInput("name")->setComponent("DebouncedTextInput")->setComponentProp("maxLength", 100);
        $form->getInput("slug")->setComponent("SlugInput")->setComponentProp("dependency", "name");
        $form->addInput(
            FormInput::create("subtaxonomies", __("Sub taxonomies"))
                ->setComponent("ReferenceListField")
                ->setComponentProp("sort", ["field" => "Taxonomies.id", "order" => "desc"])
                ->setComponentProp("empty", __("No sub taxonomies."))
                ->setComponentProp("sorry", __("You have to save taxonomy first."))
                ->setComponentProp("perPage", 10)
                ->setComponentProp("reference", "blog-post-taxonomies")
                ->setComponentProp("target", "parent_id")
                ->setComponentProp("columns", [
                    GridField::create("id", __("#ID"))->setComponent("TextField"),
                    GridField::create("name", __("Name"))->setComponent("TextField"),
                    GridField::create("created", __("Created"))
                        ->setComponent("DateField")
                        ->setComponentProp("showTime", true),
                    GridField::create("modified", __("Modified"))
                        ->setComponent("DateField")
                        ->setComponentProp("showTime", true)
                ])
                ->fullWidth()
        );
        return $form;
    }
}
