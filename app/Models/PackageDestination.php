<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageDestination extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'package_destinations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'package_id',
        'destination_id',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the package for this pivot record.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the destination for this pivot record.
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
