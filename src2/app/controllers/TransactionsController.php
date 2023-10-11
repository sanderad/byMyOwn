<?php

declare (strict_types=1);

namespace App\Controllers;

use App\Models\TransactionModel;
use App\View;

class TransactionsController
{

    public function sendTransaction(): View
    {   
        return View::make('sendTransaction');
    }

    public function showTransaction(): View
    {   
        return View::make('showTransaction');
    }

    public function show()
    {

        $transactionModel = new TransactionModel();

        return $transactionModel->runTransaction();
    }


}