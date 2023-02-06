<?php

namespace App\Http\Requests;

use App\Exceptions\AuthorizationException;
use App\Repositories\TestimonialRepository;

/**
 * Class TestimonialRequest
 * @package App\Http\Requests
 */
class TestimonialRequest extends AbstractFormRequest
{
    /**
     * @var array
     */
    protected array $allowedActions = [
        'index',
        'show',
        'store',
        'update',
        'destroy',
        'categories',
    ];

    /**
     * @var array
     */
    protected array $publicActions = [
        'index',
        'show'
    ];

    /**
     * @var string
     */
    private string $ownModelParam = 'testimonial';

    /**
     * @param TestimonialRepository $repository
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function authorize(TestimonialRepository $repository): bool
    {
        $this->getActionName();

        switch ($this->actionName) {
            case 'show':
            case 'update':
            case 'destroy':
                $this->setModel($repository->getByIdOrFail($this->route($this->ownModelParam)));
                break;
            default:
                $this->setModelClass(get_class($repository->getResource()));
                break;
        }

        return $this->isActionAuthorized();
    }

    protected function globalRules(): array
    {
        return [
            'user'        => ['string', 'max:250'],
            'video'       => ['max:250'],
            'description' => ['string'],
            'active'      => ['boolean'],
        ];
    }

    /**
     * @return array
     */
    protected function storeRules(): array
    {
        return $this->addRequiredRule($this->globalRules(), ['user']);
    }
}
