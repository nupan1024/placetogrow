<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Invoices/List');
    }
}
