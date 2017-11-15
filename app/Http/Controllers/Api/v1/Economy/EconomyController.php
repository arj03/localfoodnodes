<?php

namespace App\Http\Controllers\Api\v1\Economy;

use Illuminate\Http\Request;
use App\Admin\Economy\Transaction;
use App\Admin\Economy\Parsers\Swedbank;

class EconomyController extends \App\Http\Controllers\Controller
{
    private $categories = [
        [
            "id" => 0,
            "label" => "External events",
            "type_label" => "Income - External events"
        ], [
            "id" => 1,
            "label" => "Starting capital",
            "type_label" => "Income - Starting capital"
        ], [
            "id" => 2,
            "label" => "Backers",
            "type_label" => "Income - Backers"
        ], [
            "id" => 3,
            "label" => "Travel",
            "type_label" => "Cost - Travel"
        ], [
            "id" => 4,
            "label" => "Bank and service fees",
            "type_label" => "Cost - Bank and service fees"
        ], [
            "id" => 5,
            "label" => "Other costs",
            "type_label" => "Cost - Other"
        ], [
            "id" => 6,
            "label" => "Web services",
            "type_label" => "Cost - Web services"
        ],
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
