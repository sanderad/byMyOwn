<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;
use Exception;

class TransactionModel extends Model
{
    public static $header = [];
    public static $transactions = [];
    public static $amountForSum = [];
    public static $new = [];
    public static $netTotal;
    public static $totalIncome = [];
    public static $totalExpense = [];

    public static function runTransaction()
    {
            $model = new self();
        
            $model->db->beginTransaction();
    
            $file_path = STORAGE_PATH . '/' . basename($_FILES['transaction']['name']);
    
            move_uploaded_file($_FILES['transaction']['tmp_name'], $file_path);
    
            $csv_file = fopen($file_path, 'r');
    
            self::$header[] = fgetcsv($csv_file);
    
            while (($row = fgetcsv($csv_file)) !== false) {
                $query = "INSERT INTO transactions (dat_e, chec_k, descriptio_n, amount)
                        VALUES (:dat_e, :chec_k, :descriptio_n, :amount)";
                $stmt = $model->db->prepare($query);

                
                
                $stmt->execute([
                    ':dat_e' => $row[0],
                    ':chec_k' => (int) $row[1],
                    ':descriptio_n' => $row[2],
                    ':amount' => str_replace(['$', ','], '', $row[3])
                ]);
                self::$amountForSum[] = $row[3];
                self::$transactions[] = ['date' => $row[0], 'check' => $row[1], 'description' => $row[2], 'amount' => str_replace(['$', ','], '', $row[3])];
                //echo $this->showTransactionM();
                
            }
    
            fclose($csv_file);
            $model->db->commit();

        //echo ':d';




        //return (string) $this->amountForSum;
    }

    public static function CalculateNetTotal()
    {

        foreach(self::$amountForSum as $eachAmount) {
            self::$new[] = str_replace(['$', ','], '', $eachAmount); 
        }

        self::$netTotal = array_sum(self::$new);

        //return self::$netTotal;
    }

    public static function NetTotal()
    {
        return TransactionModel::addDollar(self::$netTotal);
    }

    public static function TotalIncome()
    {
        $x = [];
        foreach(self::$new as $each) {
            if ($each > 0) {
                $x[] = $each;
            }
        }
        
        self::$totalIncome = array_sum($x);
        return TransactionModel::addDollar(self::$totalIncome);
    }

    public static function TotalExpense()
    {
        $x = [];
        foreach(self::$new as $each) {
            if ($each < 0) {
                $x[] = $each;
            }
        }
        
        self::$totalExpense = array_sum($x);
        return TransactionModel::addDollar(self::$totalExpense);
    }

    public static function addDollar(float $amount): string
    {
        $isNegative = $amount < 0;

        return ($isNegative ? '-' : '') . '$' . number_format(abs($amount), 2);
    }

    public static function formatDate($date): string
    {
        return date('M j, Y', strtotime($date));
    }
}