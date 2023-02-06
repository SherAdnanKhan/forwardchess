<?php

namespace App\Services\Product;

use Auth;
use Carbon\Carbon;
use App\Exceptions\AuthorizationException;
use App\Exceptions\CommonException;
use App\Http\Requests\Product\ReviewRequest;
use App\Models\AbstractModel;
use App\Models\Product\Product;
use App\Models\Product\Review;
use App\Repositories\User\UserRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ReviewRepository;
use App\Services\AbstractService;
use App\Services\Tables;
use App\Contracts\MobileGatewayInterface;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class ReviewService
 * @package App\Services\Product
 *
 * @property ReviewRepository $repository
 */
class ReviewService extends AbstractService
{
    use Tables;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var MobileGatewayInterface
     */
    private MobileGatewayInterface $mobileGateway;

    /**
     * ReviewService constructor.
     *
     * @param ReviewRequest     $request
     * @param Guard             $auth
     * @param ReviewRepository  $repository
     * @param ProductRepository $productRepository
     */
    public function __construct(ReviewRequest $request, Guard $auth, ReviewRepository $repository, ProductRepository $productRepository, UserRepository $userRepository, MobileGatewayInterface $mobileGateway)
    {
        parent::__construct($request, $auth, $repository);

        $this->productRepository = $productRepository;
        $this->userRepository    = $userRepository;
        $this->mobileGateway     = $mobileGateway;
    }

    /**
     * @return array
     */
    public function tables(): array
    {
        $builder = $this
            ->repository
            ->getBuilder($this->initCollectionFilters());

        return $this->getTable(
            $this->request,
            $builder,
            [
                'nickname',
                'title' => 'reviews.title'
            ]
        );
    }

    /**
     * @return AbstractModel
     * @throws AuthorizationException
     * @throws CommonException
     */
    public function store(): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());
        $this->addFormFields([
            'userId'   => $this->request->user()->id,
            'approved' => 1
        ]);

        $fields    = $this->processFields();
        $isAllowed = $this->repository->canGiveReview($fields['productId'], $fields['userId']);
        if (!$isAllowed) {
            throw new AuthorizationException;
        }

        /** @var Review $model */
        $model = $this->repository->store($this->processFields());
        if ($model->approved) {
            $this->updateProductRating($model->productId);
        }

        return $model;
    }

    /**
     * @param false $restore
     *
     * @return AbstractModel
     * @throws CommonException
     */
    public function update($restore = false): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        /** @var Review $model */
        $model       = $this->request->getModel();
        $oldApproved = $model->approved;

        $model = $this->repository->update($model, $this->processFields(), $restore);
        if ($oldApproved !== $model->approved) {
            $this->updateProductRating($model->productId);
        }

        return $model;
    }

    /**
     * @param int $productId
     *
     * @return bool
     * @throws CommonException
     */
    private function updateProductRating(int $productId): bool
    {
        /** @var Product $product */
        $product = $this->productRepository->getById($productId);

        $this->productRepository->update($product, ['rating' => $this->repository->getProductRating($productId)]);

        return true;
    }

    /**
     * @param array $filters
     *
     * @return array
     */
    protected function initCollectionFilters(array $filters = []): array
    {
        $isAdmin = $this->request->user() && $this->request->user()->isAdmin;

        $data = [
            'productId' => $this->request->input('productId'),
            'approved'  => $isAdmin ? $this->request->input('approved') : 1,
        ];

        return array_merge($data, $filters);
    }

    /**
     * @return array
     */
    private function getRequestFormFields(): array
    {
        return [
            'productId'   => $this->request->input('productId'),
            'title'       => $this->request->input('title'),
            'description' => $this->request->input('description'),
            'rating'      => $this->request->input('rating'),
            'approved'    => $this->request->input('approved')
        ];
    }

    /**
     * @param Product                $product
     *
     * @return array|mixed
     * @throws CommonException
     */
    public function getReviews(Product $product): array
    {
        $result = $this->mobileGateway->getReviews($product->sku);
        $reviews = $result['data'] ?? [] ;

        if (count($reviews)) {
            return $this->transformReviews($product, $reviews);
        }

        return [
            'reviews'    => [],
            'rating'     => 0,
            'userReview' => null
        ];
    }

    /**
     * @return array|mixed
     * @throws AuthorizationException
     */
    public function postReview() : array
    {
        $productSku = $this->productRepository->getById($this->request->productId)->sku;

        $this->setFormFields($this->getRequestFormFields());
        $this->addFormFields([
            'email'   => $this->request->user()->email,
            'bookId'  => $productSku,
            'userId'  => $this->request->user()->id,
        ]);

        $fields    = $this->processFields();
        $isAllowed = $this->repository->canGiveReview($fields['productId'], $fields['userId']);
        if (!$isAllowed) {
            throw new AuthorizationException;
        }

        return $this->mobileGateway->postReview($fields);
    }

    /**
     * @param Product       $product
     * @param array         $reviews
     *
     * @return array
     * @throws CommonException
     */
    protected function transformReviews(Product $product, array $reviews): array
    {
        $totalRating = 0;
        $userReview = null;
        foreach ($reviews as $key => $review) {
            $totalRating += (int) $review['rating'];
            $user = $this->userRepository->first(['email' => $review['email']]);
            $reviews[$key] = array_merge([
                    'productName'   => $product->title,
                    'created_at'    => Carbon::parse(round($review['created'] / 1000))->format('Y-m-d H:i'),
                    'nickname'      => $user['nickname'] ?: $user['firstName'] . ' ' . $user['lastName']
                ], $review);

            if (auth()->check() && auth()->user()->email === $review['email']) {
                $userReview = $reviews[$key];
            }
        }

        $rating = toIntAmount($totalRating / count($reviews));
        $this->productRepository->update($product, ['rating' => $rating]);

        return [
            'reviews'    => $reviews,
            'rating'     => $product->rating,
            'userReview' => $userReview
        ];
    }
}
