<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    /**
     * Corresponding table
     *
     * @var string table name
     */
    protected $table = 'titles';

    public $timestamps = false;

    protected $guarded = [];

    public $incrementing = false;

    /**
     * Relation between title and employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_no', 'emp_no'); // 'foreign_key', 'owner_key'
    }
}
