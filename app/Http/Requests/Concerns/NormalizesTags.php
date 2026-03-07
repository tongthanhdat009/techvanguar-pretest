<?php

namespace App\Http\Requests\Concerns;

trait NormalizesTags
{
    protected function normalizeTags(?string $tags): array
    {
        return collect(explode(',', (string) $tags))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->values()
            ->all();
    }
}