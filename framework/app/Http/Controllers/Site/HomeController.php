<?php

namespace App\Http\Controllers\Site;

use App\Common\DataCollector;
use App\Contracts\CartServiceInterface;
use App\Contracts\MailChimpServiceInterface;
use App\Events\ContactSavedEvent;
use App\Models\ContactMessage;
use App\Repositories\User\UserRepository;
use App\Services\NewsletterService;
use App\Services\Product\ProductService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Class HomeController
 * @package App\Http\Controllers\Site
 */
class HomeController extends AbstractSiteController
{
    private function testMailChimp()
    {
        $mailchimp = app(MailChimpServiceInterface::class);
        $response  = $mailchimp->test();
    }

    /**
     * Show the application dashboard.
     *
     * @param Request              $request
     * @param ProductService       $productService
     * @param DataCollector        $dataCollector
     * @param CartServiceInterface $cartService
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function index(Request $request, ProductService $productService, DataCollector $dataCollector, CartServiceInterface $cartService)
    {
        if (!empty($buyBookSKU = $request->input('buyBookId'))) {
            $product = $productService->all(['sku' => $buyBookSKU]);
            if (!$product->isEmpty()) {
                $cartService->addItem($product->first()->id);
                return redirect()->route('site.checkout');
            }
        }

        $this->addViewData([
            'groupedPublishers' => $this->groupData($dataCollector->getGlobalData()->publishers),
            'productsTabs'      => $productService->getHomeProductTabs(),
            'bestSellers'       => $productService->getBestSellers(),
            'mostWished'        => $productService->getMostWished(),
            'bestOfYear'        => $productService->getBestOfYear(),
        ]);

        return view('site.pages.home', $this->viewData);
    }

    /**
     * @param Request $request
     *
     * @return Factory|\Illuminate\View\View
     */
    public function showContact(Request $request)
    {
        $this->addViewData([
            'email' => $request->user() ? $request->user()->email : '',
        ]);

        return view('site.pages.contact', $this->viewData);
    }

    /**
     * @param Request        $request
     * @param UserRepository $userRepository
     *
     * @return RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveContact(Request $request, UserRepository $userRepository)
    {
        Validator::make($request->all(), [
            'name'               => 'required|string|min:5|max:255',
            'email'              => 'required|email|max:100',
            'subject'            => 'required|string|min:5|max:255',
            'message'            => 'required|string|min:10|max:1000',
            recaptchaFieldName() => recaptchaRuleName()
        ])->validate();

        $user = null;
        if ($request->user()) {
            $user = $request->user();
        } else {
            $user = $userRepository->first(['email' => $request->input('email')]);
        }

        event(new ContactSavedEvent(ContactMessage::create([
            'name'    => ucwords($request->input('name')),
            'email'   => $request->input('email'),
            'subject' => ucfirst($request->input('subject')),
            'message' => $request->input('message'),
            'userId'  => $user ? $user->id : 0
        ])));

        $request->session()->flash('success-message', 'Your message was sent!');

        return redirect(route('site.contact.show'));
    }

    /**
     * @param NewsletterService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(NewsletterService $service)
    {
        $service->store();

        return response()->json(['success' => 1]);
    }

    /**
     * @return Factory|\Illuminate\View\View
     */
    public function privacyPolicy()
    {
        return view('site.pages.privacy-policy');
    }

    /**
     * @return Factory|\Illuminate\View\View
     */
    public function termsOfService()
    {
        return view('site.pages.terms-of-service');
    }

    /**
     * @return Factory|\Illuminate\View\View
     */
    public function userGuide()
    {
        return view('site.pages.user-guide');
    }

    /**
     * @return Factory|\Illuminate\View\View
     */
    public function downloadInstructions()
    {
        return view('site.pages.download-instructions');
    }

    /**
     * @return Factory|\Illuminate\View\View
     */
    public function affiliate()
    {
        return view('site.pages.affiliate');
    }

    private function deleteFakeUsers(Request $request)
    {
        if ($request->ip() === '188.26.44.170') {
            $deleteUsers = function ($ids) {
                DB::table('users')->whereIn('id', $ids)->delete();
            };

            $results = DB::select(DB::raw("SELECT users.id FROM `users`
                LEFT JOIN `orders` on `orders`.`userId` = users.id
                where users.email_verified_at is null
                group by users.id
                having count(orders.id) = 0
                ORDER BY `users`.`created_at`DESC"));

            $users = [];
            foreach ($results as $result) {
                $users[] = $result->id;

                if (count($users) === 100) {
                    $deleteUsers($users);
                }
            }

            if (count($users)) {
                $deleteUsers($users);
            }

            dd($results);
        }
    }

    private function createDeletedUser()
    {
        /** @var UserRepository $userRepository */
        $userRepository = app(UserRepository::class);

        return $userRepository->store([
            'firstName' => 'fake',
            'lastName'  => 'account',
            'email'     => 'deleted_user@forwardchess.com',
            'password'  => md5('the-deleted-user-12345')
        ]);
    }
}
