<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{

    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function addItemCart(Request $request)
    {
        $validation = $this->cartRepository->addOrUpdateCartItemValidation($request);
        if ($validation) {
            return $validation;
        }

        if ($this->cartRepository->addOrUpdateCustomerItem($request)) {
            return $this->apiResponse("item added to cart successfully");
        }
        return $this->unKnowError("Error while saving the item to cart");
    }

    public function removeItemCart(Request $request)
    {
        $validation = $this->cartRepository->removeCartItemValidation($request);
        if ($validation) {
            return $validation;
        }

        if ($this->cartRepository->removeCustomerItem($request)) {
            return $this->apiResponse("item removed from cart successfully");
        }
        return $this->notFoundResponse("no item in cart for user to delete");
    }

    public function userCheckout($customer_id)
    {
        $items = $this->cartRepository->getCustomerCheckout($customer_id);
        if (count($items) > 0) {
            return $this->apiResponse(CartResource::collection($items));
        }
        return $this->notFoundResponse('no items found for checkout');

    }

}
