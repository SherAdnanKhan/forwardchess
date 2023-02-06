<?php

namespace App\Http\Controllers\Site;

use App\Assets\GiftCard;
use App\Contracts\CartServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class GiftController
 * @package App\Http\Controllers\Site
 */
class GiftController extends AbstractSiteController
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $gift = $request->session()->has(CartServiceInterface::GIFT_CARD_KEY)
            ? $request->session()->get(CartServiceInterface::GIFT_CARD_KEY)
            : GiftCard::make([
                'amount' => 25
            ]);

        $this->addViewData([
            'gift' => $gift->toArray()
        ]);

        return view('site.pages.gift-card.form', $this->viewData);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request)
    {
        Validator::make($request->all(), [
            'amount'  => 'required|integer|min:1',
            'name'    => 'required|string|min:5|max:255',
            'email'   => 'required|email|max:100',
            'message' => 'string|min:5|max:1000',
        ])->validate();

        $request->session()->put(CartServiceInterface::GIFT_CARD_KEY, GiftCard::make($request->all()));

        return redirect()->route('site.gift-card.checkout');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function checkout(Request $request)
    {
        /** @var GiftCard $gift */
        $gift = $request->session()->get(CartServiceInterface::GIFT_CARD_KEY);
        if (empty($gift)) {
            return redirect(route('site.gift-card.index'));
        }

        $user = $request->user();

        $this->addViewData([
            'gift'    => json_encode($gift->toArray()),
            'billing' => json_encode([
                'firstName' => $user->firstName,
                'lastName'  => $user->lastName,
                'email'     => $user->email
            ])
        ]);

        return view('site.pages.gift-card.checkout', $this->viewData);
    }
}