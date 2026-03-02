<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

trait PerPageTrait
{
    private const PER_PAGE_ALLOWED = [10, 20, 50];
    private const PER_PAGE_DEFAULT = 20;

    protected function adminPerPage(Request $request): int
    {
        $n = $request->integer('per_page', self::PER_PAGE_DEFAULT);
        return in_array($n, self::PER_PAGE_ALLOWED, true) ? $n : self::PER_PAGE_DEFAULT;
    }
}
