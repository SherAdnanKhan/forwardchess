<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\AbstractFormRequest;
use App\Repositories\Product\ProductRepository;
use App\Rules\ProductCategoriesRule;

/**
 * Class ProductRequest
 * @package App\Http\Requests\Product
 */
class ProductRequest extends AbstractFormRequest
{
    /**
     * @var array
     */
    protected array $allowedActions = [
        'index',
        'show',
        'sample',
        'store',
        'update',
        'destroy',
        'reviews',
        'shoppingCart'
    ];

    /**
     * @var array
     */
    protected array $publicActions = [
        'index',
        'show',
        'display',
        'sample',
        'reviews',
        'shoppingCart'
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
        'sample'  => 'show',
    ];

    /**
     * @var string
     */
    private string $ownModelParam = 'product';

    /**
     * @var string
     */
    private string $displayModelParam = 'url';

    /**
     * @param ProductRepository $repository
     *
     * @return bool
     * @throws \App\Exceptions\AuthorizationException
     */
    public function authorize(ProductRepository $repository)
    {
//        echo '<pre>';
//        \DB::listen(function($query) {
//            var_dump($query->sql);
//        });
        $this->getActionName();
        if ($this->isRestoreAction()) {
            $repository->setTrashedIncludedInSearch(true);
        }

        if (!empty($title = $this->route($this->displayModelParam))) {
            $routeParam = $repository->getIdFromUrl($title);
            if (empty($routeParam)) {
                redirect_now(route('site.products.index'));
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

        return $this->isActionAuthorized();
    }

    /**
     * @return array
     */
    protected function globalRules(): array
    {
        // fix this
        return [
            'publisherId' => ['numeric', 'max:25'],
            'categories'  => ['array', app(ProductCategoriesRule::class)],
            'title'       => ['string', 'max:250'],
            'description' => ['string'],
            'author'      => ['string', 'max:250'],
            'sku'         => ['required_with:image', 'string', 'max:250'],
            'image'       => ['nullable', 'string'],
            'price'       => ['numeric'],
            'discount'    => ['nullable', 'numeric'],
            'nrPages'     => ['integer'],
            'publishDate' => ['string'],
            'position'    => ['integer'],
            'active'      => ['boolean'],
            'isBundle'    => ['boolean'],
        ];
    }

    /**
     * @return array
     */
    protected function storeRules(): array
    {
        // fix this
        return $this->addRequiredRule($this->globalRules(), [], [
            'discount',
            'position',
            'active'
        ]);
    }
}
