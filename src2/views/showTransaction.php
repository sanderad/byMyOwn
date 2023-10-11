<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
            }

            table tr th, table tr td {
                padding: 5px;
                border: 1px #eee solid;
            }

            tfoot tr th, tfoot tr td {
                font-size: 20px;
            }

            tfoot tr th {
                text-align: right;
            }
        </style>
    </head>
    <body>

<?php
use App\Models\TransactionModel as T;
T::runTransaction();
T::CalculateNetTotal();
?>
        <table>
            <thead>
                <tr>
                    <?php foreach (T::$header as $head) ?>
                    <th><?= $head[0] ?></th>
                    <th><?= $head[1] ?></th>
                    <th><?= $head[2] ?></th>
                    <th><?= $head[3] ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty(T::$transactions)): ?>
                    <?php foreach(T::$transactions as $transaction): ?>
                        <tr>
                            <td><?= T::formatDate($transaction['date']) ?></td>
                            <td><?= $transaction['check']?></td>
                            <td><?= $transaction['description'] ?></td>
                            <td>
                                <?php if ($transaction['amount'] < 0): ?>
                                    <span style="color: red;">
                                        <?= T::addDollar($transaction['amount']) ?>
                                    </span>
                                <?php elseif ($transaction['amount'] > 0): ?>
                                    <span style="color: green;">
                                        <?= T::addDollar($transaction['amount']) ?>
                                    </span>
                                <?php else: ?>
                                    <?= T::addDollar($transaction['amount']) ?>
                                <?php endif ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                        <?php endif ?> 
                        
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total Income:</th>
                    <td><?= T::TotalIncome() ?></td>
                </tr>
                <tr>
                    <th colspan="3">Total Expense:</th>
                    <td><?= T::TotalExpense() ?></td>
                </tr>
                <tr>
                    <th colspan="3">Net Total:</th>
                    <td><?= T::NetTotal() ?></td>
                </tr>
            </tfoot>
        </table>
    </body>
</html>