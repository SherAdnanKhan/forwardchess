<?php

namespace App\Http\Requests\Blog;

use App\Http\Requests\AbstractFormRequest;
use App\Repositories\Blog\ArticleRepository;
use App\Rules\ArticleTagsRule;

/**
 * Class ArticleRequest
 * @package App\Http\Requests\Blog
 */
class ArticleRequest extends AbstractFormRequest
{
    /**
     * @var array
     */
    protected array $allowedActions = [
        'index',
        'show',
        'store',
        'update',
        'destroy'
    ];

    /**
     * @var array
     */
    protected array $publicActions = [
        'index',
        'show',
        'display',
    ];

    /**
     * @var array
     */
    protected array $aliasActions = [
        'tables'  => 'index',
        'home'    => 'index',
        'display' => 'show',
        'search'  => 'index',
        'restore' => 'update',
    ];

    /**
     * @var string
     */
    private string $ownModelParam = 'article';

    /**
     * @var string
     */
    private string $displayModelParam = 'url';

    /**
     * @param ArticleRepository $repository
     *
     * @return bool
     * @throws \App\Exceptions\AuthorizationException
     */
    public function authorize(ArticleRepository $repository)
    {
        $this->getActionName();

        if ($this->isRestoreAction()) {
            $repository->setTrashedIncludedInSearch(true);
        }


        if (!empty($title = $this->route($this->displayModelParam))) {
            $routeParam = $repository->getIdFromUrl($title);
            if (empty($routeParam)) {
                redirect_now(route('site.articles.index'));
            }
        } else {
            $routeParam = $this->route($this->ownModelParam);
        }

        switch ($this->actionName) {
            case 'show':
            case 'update':
            case 'destroy':
                $this->setModel($repository->getByIdOrFail($routeParam));
                break;
            default:
                $this->setModelClass(get_class($repository->getResource()));
                break;
        }
        return true;
    }

    /**
     * @return array
     */
    protected function globalRules(): array
    {
        return [
            'tags'        => ['array', app(ArticleTagsRule::class)],
            'title'       => ['string', 'max:250'],
            'content'     => ['string'],
            'publishDate' => ['string'],
            'active'      => ['boolean'],
        ];
    }

    /**
     * @return array
     */
    protected function storeRules(): array
    {
        return $this->addRequiredRule($this->globalRules(), [], [
            'active'
        ]);
    }
}
