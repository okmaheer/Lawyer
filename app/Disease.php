<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    protected $table = 'diseases';
    
     /**
     * Get the prescriptions that  belongs to childhood_illness.
     *
     * @return relation
     */
    public function prescriptions ()
    {
        return $this->belongsToMany('App\Prescription')->withPivot('prescription_id');
    }

    /**
     * Set slug before saving in DB
     *
     * @param string $value value
     *
     * @access public
     *
     * @return string
     */
    public function setSlugAttribute($value)
    {
        if (!empty($value)) {
            $temp = str_slug($value, '-');
            if (!Disease::all()->where('slug', $temp)->isEmpty()) {
                $i = 1;
                $new_slug = $temp . '-' . $i;
                while (!Disease::all()->where('slug', $new_slug)->isEmpty()) {
                    $i++;
                    $new_slug = $temp . '-' . $i;
                }
                $temp = $new_slug;
            }
            $this->attributes['slug'] = $temp;
        }
    }
     /**
     * Saving Disease
     *
     * @param mixed $request Request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function saveDisease($request)
    {
        if (!empty($request)) {
            $this->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
            $this->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
            return $this->save();
        }
    }

    /**
     * Updating Disease
     *
     * @param mixed $request Request attributes
     * @param int   $id      get id for updation
     *
     * @return \Illuminate\Http\Response
     */
    public function updateDisease($request, $id)
    {
        if (!empty($request)) {
            $disease = self::find($id);
            if ($disease->title != $request['title']) {
                $disease->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
            }
            $disease->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
            return $disease->save();
        }
    }
}
