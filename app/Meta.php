<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    /**
     * Meta belongs to Model
     *
     * @return relation
     */
    public function metable()
    {
        return $this->morphTo();
    }
}
