<?php

namespace App\Controller;

class ErrorController extends Controller
{
    public function show(string $errorMessage): void
    {
        $this->render("errors/default", [
            "errorMessage" => $errorMessage
        ]);
    }
}
