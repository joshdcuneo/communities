<?php

namespace App\Models;

use App\Models\Concerns\HasOwner;
use App\Models\Concerns\IsOwned;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string|null $name
 * @property string|null $description
 */
class Community extends Model implements IsOwned
{
    use HasFactory;
    use HasOwner;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];
}
