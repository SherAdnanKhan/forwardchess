<?php

namespace App\Services\Blog;

use App\Http\Requests\Blog\TagRequest;
use App\Models\AbstractModel;
use App\Models\Blog\Tag;
use App\Repositories\Blog\TagRepository;
use App\Services\AbstractService;
use App\Services\Tables;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TagService
 * @package App\Services\Blog
 */
class TagService extends AbstractService
{
    use Tables;

    /**
     * TagService constructor.
     *
     * @param TagRequest    $request
     * @param Guard         $auth
     * @param TagRepository $repository
     */
    public function __construct(TagRequest $request, Guard $auth, TagRepository $repository)
    {
        parent::__construct($request, $auth, $repository);
    }

    /**
     * @return array
     */
    public function tables(): array
    {
        return $this->getTable(
            $this->request,
            $this->repository->getBuilder(),
            [
                'name'
            ]
        );
    }

    /**
     * @return Collection
     */
    public function getActiveTags()
    {
        return $this->repository->getBuilder()
            ->select([
                'blog_tags.id',
                'blog_tags.name',
            ])
            ->join('blog_articles_tags', 'blog_articles_tags.tagId', '=', 'blog_tags.id')
            ->join('blog_articles', 'blog_articles.id', '=', 'blog_articles_tags.articleId')
            ->where('blog_articles.active', true)
            ->groupBy('blog_tags.id', 'blog_tags.name')
            ->orderBy('blog_tags.name', 'ASC')
            ->get();
    }

    /**
     * @param null $tagName
     *
     * @return int|null
     */
    public function getTagIdByName($tagName = null): ?int
    {
        if ($tagName !== null) {
            $tagName = str_replace('-', ' ', $tagName);

            /** @var Tag $tag */
            $tag = $this->repository->getBuilder()->where('name', $tagName)->first();
            return $tag ? $tag->id : null;
        }

        return null;
    }

    /**
     * @return AbstractModel
     */
    public function store(): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        return $this->repository->store($this->processFields());
    }

    /**
     * @param bool $restore
     *
     * @return AbstractModel
     */
    public function update($restore = false): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        return $this->repository->update($this->request->getModel(), $this->processFields(), $restore);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function destroy(): bool
    {
        /** @var Tag $tag */
        $tag = $this->request->getModel();

        if (!$tag->articles->isEmpty()) {
            throw new \Exception('You cannot delete this tag because is has articles attached to it.');
        }

        return $this->repository->destroy($this->request->getModel());
    }

    /**
     * @return array
     */
    private function getRequestFormFields(): array
    {
        return [
            'name' => $this->request->input('name'),
        ];
    }
}