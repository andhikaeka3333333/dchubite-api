<?php

// app/Models/ProfitsReport.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitsReport extends Model
{
    use HasFactory;
    protected $fillable = ['report_date', 'total_revenue', 'total_cost', 'total_profit'];
}

