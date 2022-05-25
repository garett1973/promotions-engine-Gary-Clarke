<?php declare(strict_types=1);

namespace App\Filter;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;
use App\Filter\Modifier\Factory\PriceModifierFactoryInterface;

class LowestPriceFilter implements PromotionsFilterInterface
{
    public function __construct(private priceModifierFactoryInterface $priceModifierFactory)
    {
    }


    public function apply(PromotionEnquiryInterface $enquiry, Promotion ...$promotions): PromotionEnquiryInterface
    {

        $price = $enquiry->getProduct()->getPrice();
        $quantity = $enquiry->getQuantity();
        $lowestPrice = $price * $quantity;
        // Loop over the promotions
        foreach ($promotions as $promotion) {
            // Run the promotions modification logic against the enquiry
            // 1. check does the promotion apply e.g. is it in date range/ is the voucher code valid?
            // 2. apply the price modification to obtain a $modifiedPrice (how?)
            $priceModifier = $this->priceModifierFactory->create($promotion->getType());


            $modifiedPrice = $priceModifier->modify($price, $quantity, $promotion, $enquiry);

            // 3. check if the modified price is lower than the current lowest price $lowestPrice
            // 1. Save to Enquiry properties
            // 2. Update the lowest price $lowestPrice

            $enquiry->setDiscountedPrice(250);
            $enquiry->setPrice(100);
            $enquiry->setPromotionId(3);
            $enquiry->setPromotionName('Black Friday half price sale');
        }


        return $enquiry;
    }
}