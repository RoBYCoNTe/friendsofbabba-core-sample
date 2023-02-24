<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use FriendsOfBabba\Core\Model\Entity\User;
use FriendsOfBabba\Core\Model\Crud\Form;
use FriendsOfBabba\Core\Model\Crud\Grid;
use SoftDelete\Model\Table\SoftDeleteTrait;
use FriendsOfBabba\Core\Model\Table\BaseTable;

/**
 * BlogPostComments Model
 *
 * @property \App\Model\Table\BlogPostsTable&\Cake\ORM\Association\BelongsTo $BlogPosts
 *
 * @method \App\Model\Entity\BlogPostComment newEmptyEntity()
 * @method \App\Model\Entity\BlogPostComment newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostComment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostComment get($primaryKey, $options = [])
 * @method \App\Model\Entity\BlogPostComment findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\BlogPostComment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostComment[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostComment|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogPostComment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogPostComment[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\BlogPostComment[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\BlogPostComment[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\BlogPostComment[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BlogPostCommentsTable extends BaseTable
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

        $this->setTable('blog_post_comments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Search.Search', ['collectionClass' => \App\Model\Filter\BlogPostCommentCollection::class]);

        $this->belongsTo('BlogPosts', [
            'foreignKey' => 'blog_post_id',
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
            ->nonNegativeInteger('blog_post_id')
            ->requirePresence('blog_post_id', 'create')
            ->notEmptyString('blog_post_id');

        $validator
            ->scalar('comment_text')
            ->maxLength('comment_text', 1000)
            ->requirePresence('comment_text', 'create')
            ->notEmptyString('comment_text');

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
        $rules->add($rules->existsIn(['blog_post_id'], 'BlogPosts'), ['errorField' => 'blog_post_id']);

        return $rules;
    }

    public function getForm(?User $user, bool $extends = TRUE): ?Form
    {
        $form = parent::getForm($user, $extends);
        // $form->setRedirect(NULL);
        $form->setRefresh(TRUE);
        $form->setToolbarComponentProp("backReferenceTarget", "blog_post_id");
        $form->setToolbarComponentProp("backReference", "blog-posts");
        $form->setToolbarComponentProp("backTab", 0);
        $form->removeInput("blog_post_id");
        $form->getInput("comment_text")
            ->setComponent("DebouncedTextInput")
            ->setComponentProp("multiline", true)
            ->setComponentProp("maxLength", 500)
            ->fullWidth()
            ->setLabel(__("Comment"));


        return $form;
    }

    public function getGrid(?User $user, bool $extends = TRUE): ?Grid
    {
        $grid = parent::getGrid($user, $extends);
        $grid->getField("comment_text")->setComponent("LongTextField");
        return $grid;
    }
}
