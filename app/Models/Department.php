<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * Corresponding table
     *
     * @var string table name
     */
    protected $table = 'departments';

    /**
     * Real primary key
     *
     * @var string table name
     */
    protected $primaryKey = 'dept_no';
}
