<?php

namespace App\Http\Controllers\Api\v1\Economy;

use Illuminate\Http\Request;
use App\Admin\Economy\Transaction;
use App\Admin\Economy\Parsers\Swedbank;

class EconomyController extends \App\Http\Controllers\Controller
{
    private $categories = [
        ["id" => 0, "label" => "External events"],
        ["id" => 1, "label" => "Starting capital"],
        ["id" => 2, "label" => "Backers"],
        ["id" => 3, "label" => "Travel"],
        ["id" => 4, "label" => "Bank and service fees"],
        ["id" => 5, "label" => "Other costs"],
        ["id" => 6, "label" => "Operating costs"],
    ];

    /**
     * Get all transactions.
     *
     * @param  Request $request
     * @return Collection
     */
    public function transactions(Request $request)
    {
        $transactions = Transaction::orderByDesc('date')->get();

        return [
            'transactions' => $transactions,
            'categories' => $this->categories,
        ];
    }

    /**
     * Get all transaction categories.
     *
     * @param  Request $request
     * @return Collection
     */
    public function transactionCategories(Request $request)
    {
        return $this->categories;
    }

    /**
     * Upload transaction file.
     *
     * @param  Request $request
     * @return
     */
    public function uploadTransactionsFile(Request $request)
    {
        if ($request->has('file')) {
            $swedbankParser = new Swedbank($request->file('file'));

            $swedbankParser->parse(function($validTransaction) {
                if ($validTransaction['ref'] === 'STRIPE') {
                    $validTransaction['category'] = 2;
                }

                $transaction = new Transaction();
                $errors = $transaction->validate($validTransaction);

                if ($errors->isEmpty()) {
                    Transaction::create($validTransaction);
                }
            });
        }
    }

    /**
     * Update a transaction
     *
     * @param Request $request
     * @return Transaction
     */
    public function updateTransaction(Request $request)
    {
        try {
            $transaction = Transaction::find($request->input('id'));
            $transaction->category = $request->input('category');
            $transaction->save();

            return $transaction;
        } catch(\Exception $e) {
            return response($e, 400);
        }
    }
}
