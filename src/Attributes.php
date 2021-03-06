<?php

declare(strict_types=1);

/*
 * This file is part of the Gocanto Attributes Package
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gocanto\Relay;

use Gocanto\Relay\Support\AttributesCollection;
use Gocanto\Relay\Types\Any;
use Gocanto\Relay\Support\PromotersCollection;

abstract class Attributes
{
    private AttributesCollection $attributes;

    private PromotersCollection $promoters;

    /**
     * @param array $data
     * @param array $promoters
     * @throws AttributesException
     */
    public function __construct(array $data = [], array $promoters = [])
    {
        $this->parse($data, $promoters);
    }

    /**
     * @param array $data
     * @param array $promoters
     * @throws AttributesException
     */
    private function parse(array $data = [], array $promoters = []): void
    {
        $attributes = new AttributesCollection();
        $promoters = new PromotersCollection($promoters);

        foreach ($data as $field => $value) {
            $attributes->add($field, $promoters->getTypeFor($field, $value));
        }

        $this->attributes = $attributes;
        $this->promoters = $promoters;
    }

    /**
     * @param string $field
     * @return Type|null
     * @throws AttributesException
     */
    public function get(string $field): ?Type
    {
        $builder = new Builder($this->attributes, $this->promoters);

        return $builder->build($field);
    }

    /**
     * @return array<int|string, Any>
     */
    public function toArray(): array
    {
        return $this->attributes->toArray();
    }
}
