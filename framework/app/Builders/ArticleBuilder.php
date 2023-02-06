<?php

namespace App\Builders;

use App\Models\Blog\Article;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ArticleBuilder
 * @package App\Builders
 */
class ArticleBuilder extends AbstractBuilder implements BuilderInterface
{
    /**
     * @var string
     */
    private $tagsTable;

    /**
     * @var array
     */
    private $tagsIds = [];

    /**
     * @var string
     */
    private $title;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @param array $data
     *
     * @return ArticleBuilder
     */
    public static function make(array $data = []): BuilderInterface
    {
        /** @var ArticleBuilder $instance */
        $instance = app(ArticleBuilder::class);

        $mapper = [
            'tagId'         => 'setTagsIds',
            'tagsIds'       => 'setTagsIds',
            'title'         => 'setTitle',
            'trashIncluded' => 'setIsTrashIncluded',
            'active'        => 'setActive',
            'sortBy'        => 'setSortBy',
        ];

        $bulkFields = [];

        foreach ($data as $property => $value) {
            if (is_null($value)) {
                continue;
            }

            if (!array_key_exists($property, $mapper)) {
                $bulkFields[$property] = $value;
                continue;
            }

            switch ($property) {
                case 'tagId':
                    $value = [$value];
                    break;
            }

            call_user_func_array([$instance, $mapper[$property]], [$value]);
        }

        $instance->setBulkFields($bulkFields);
        return $instance;
    }

    /**
     * ArticleBuilder constructor.
     *
     * @param Article $page
     */
    public function __construct(Article $page)
    {
        $this->resource  = $page;
        $this->mainTable = $page->getTable();
        $this->tagsTable = $page->tags()->getTable();
    }

    /**
     * @return Builder
     */
    public function init(): Builder
    {
        $this->builder = $this->isTrashIncluded ? $this->resource->withTrashed() : $this->resource->query();

        $this
            ->addFields()
            ->addJoins()
            ->addWhere()
            ->addSortBy();

        return $this->builder;
    }

    /**
     * @param array $tagsIds
     *
     * @return ArticleBuilder
     */
    public function setTagsIds(array $tagsIds): ArticleBuilder
    {
        $this->tagsIds = $tagsIds;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return ArticleBuilder
     */
    public function setTitle(string $title): ArticleBuilder
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param bool $active
     *
     * @return ArticleBuilder
     */
    public function setActive(bool $active): ArticleBuilder
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return ArticleBuilder
     */
    private function addFields(): ArticleBuilder
    {
        $fields = array_map(function ($field) {
            return "{$this->mainTable}.{$field}";
        }, [
            'id',
            'title',
            'content',
            'url',
            'publishDate',
            'active',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);

        $this->builder = $this->builder->select($fields);

        return $this;
    }

    /**
     * @return ArticleBuilder
     */
    private function addJoins(): ArticleBuilder
    {
        if (!empty($this->tagsIds)) {
            $this->builder = $this->builder->join($this->tagsTable, 'blog_articles.id', '=', 'blog_articles_tags.articleId');
        }

        return $this;
    }

    /**
     * @return ArticleBuilder
     */
    private function addWhere(): ArticleBuilder
    {
        $mappers = [
            'tagsIds' => function ($value) {
                return $this->builder->whereIn("{$this->tagsTable}.tagId", $value);
            },

            'title' => function ($value) {
                return $this->builder->where(function ($query) use ($value) {
                    $query->where("{$this->mainTable}.title", 'like', "%{$value}%");
                });
            },

            'active' => function ($value) {
                return $this->builder->where("{$this->mainTable}.active", '=', $value ? 1 : 0);
            },

        ];

        foreach ($mappers as $property => $value) {
            if (empty($this->{$property})) {
                continue;
            }

            $this->builder = $value($this->{$property});
        }

        $this->addBulkFields();

        return $this;
    }
}
