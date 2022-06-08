<?php

namespace App\Cache;

use App\Entity\Product;
use Symfony\Contracts\Cache\CacheInterface;
use \App\Repository\PromotionRepository;
use Symfony\Contracts\Cache\ItemInterface;

class PromotionCache
{
    public function __construct(private CacheInterface $cache, private PromotionRepository $promotionRepository)
    {
    }

    public function findValidForProduct(Product $product, string $requestDate): ?array
    {
        $key = sprintf('find-valid-for-product-%d', $product->getId());

        return $this->cache->get($key, function(ItemInterface $item) use ($product, $requestDate) {

            $item->expiresAfter(3600);

            return $this->promotionRepository->findValidForProduct(
                $product,
                date_create_immutable($requestDate)
            );
        });
    }
}