<?php

namespace App\Services\Blog;

use App\Exceptions\CommonException;
use App\Http\Requests\Blog\ArticleRequest;
use App\Models\AbstractModel;
use App\Models\Blog\Article;
use App\Repositories\Blog\ArticleRepository;
use App\Searches\PageSearch;
use App\Services\AbstractService;
use App\Services\Tables;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class ArticleService
 * @package App\Services\Blog
 */
class ArticleService extends AbstractService
{
    use Tables;

    /**
     * ArticleService constructor.
     *
     * @param ArticleRequest    $request
     * @param Guard             $auth
     * @param ArticleRepository $repository
     */
    public function __construct(ArticleRequest $request, Guard $auth, ArticleRepository $repository)
    {
        parent::__construct($request, $auth, $repository);
    }

    /**
     * @return array
     */
    public function tables(): array
    {
        $searchFields = [
            'title' => 'blog_articles.title'
        ];

        $builder = $this
            ->repository
            ->getBuilder($this->initCollectionFilters());

        return $this->getTable(
            $this->request,
            $builder,
            $searchFields
        );
    }

    /**
     * @param array $filters
     *
     * @return Collection
     */
    public function allItems(array $filters = []): Collection
    {
        return $this->repository->get($this->initCollectionFilters($filters));
    }

    /**
     * @return AbstractModel
     * @throws CommonException
     */
    public function store(): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());
        $fields = $this->processFields();
        $tags   = $this->getTags($fields);

        /** @var ArticleRepository $repository */
        $repository = $this->repository;

        /** @var Article $model */
        $model = $repository->store($fields);

        if ($tags) {
            $repository->saveTags($model, $tags);
        }

        return $model;
    }

    /**
     * @param bool $restore
     *
     * @return AbstractModel
     * @throws CommonException
     */
    public function update($restore = false): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        /** @var Article $model */
        $model = $this->request->getModel();

        /** @var ArticleRepository $repository */
        $repository = $this->repository;

        $fields = $this->processFields();
        $tags   = $this->getTags($fields);

        if (!empty($fields) || $restore) {
            $model = $repository->update($model, $fields, $restore);
        }

        if ($tags) {
            $repository->saveTags($model, $tags);
        }

        return $model;
    }

    /**
     * @param array $fields
     *
     * @return mixed|null
     */
    private function getTags(array &$fields)
    {
        $tags = isset($fields['tags']) ? $fields['tags'] : null;

        unset($fields['tags']);

        return $tags;
    }

    /**
     * @param null $tagId
     *
     * @return LengthAwarePaginator
     */
    public function getArticlesByTag($tagId = null): LengthAwarePaginator
    {
        $search = ['active' => 1];
        if ($tagId) {
            $search['tagId'] = $tagId;
        }

        return $this
            ->repository
            ->getBuilder($search)
            ->orderBy('blog_articles.publishDate', 'desc')
            ->paginate(20);
    }

    /**
     * @param string $url
     *
     * @return Article
     */
    public function getArticleByUrl(string $url): ?Article
    {
        /** @var Article $article */
        $article = $this->repository->getBuilder()->where('url', $url)->first();

        return $article;
    }

    /**
     * @param array $filters
     *
     * @return array
     */
    protected function initCollectionFilters(array $filters = []): array
    {
        /** @var PageSearch $search */
        $search = app(PageSearch::class, ['data' => $this->request->all()]);

        return array_merge($search->getFilters(), $filters);
    }

    /**
     * @return array
     */
    private function getRequestFormFields(): array
    {
        return $this->getRequestFields($this->request, [
            'tags'        => null,
            'title'       => '',
            'content'     => '',
            'publishDate' => null,
            'active'      => false,
        ]);
    }
}