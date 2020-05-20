<?php
/**
 * Created by PhpStorm.
 * User: Pang
 * Date: 6/28/2017
 * Time: 10:21 AM
 */

namespace App\Model;

use App\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class InvoicesExport implements FromCollection
{
    protected $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }
    public function collection()
    {
        return new Collection($this->array);
    }
}