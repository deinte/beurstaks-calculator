<?php

declare(strict_types=1);

namespace App\Content\Controllers;

use App\Content\Actions\ParseMarkdownPageAction;
use Illuminate\View\View;

class ShowPageController
{
    public function __invoke(string $slug, ParseMarkdownPageAction $action): View
    {
        $page = $action->execute($slug);

        if ($page === null) {
            abort(404);
        }

        return view('content.show', ['page' => $page]);
    }
}
