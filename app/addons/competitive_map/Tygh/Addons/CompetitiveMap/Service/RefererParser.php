<?php

namespace Tygh\Addons\CompetitiveMap\Service;

class RefererParser
{
    protected $queryParams = [];

    public function __construct(string $referer)
    {
        $parsed = parse_url($referer);
        parse_str($parsed['query'] ?? '', $this->queryParams);
    }

    public function getFeaturesHash(): ?string
    {
        return $this->queryParams['features_hash'] ?? null;
    }
}
