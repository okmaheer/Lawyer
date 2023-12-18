<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChildhoodIllness extends Model
{
    protected $table = 'childhood_illness';

     /**
     * Get the childhood illness that  belongs to prescription.
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
            if (!ChildhoodIllness::all()->where('slug', $temp)->isEmpty()) {
                $i = 1;
                $new_slug = $temp . '-' . $i;
                while (!ChildhoodIllness::all()->where('slug', $new_slug)->isEmpty()) {
                    $i++;
                    $new_slug = $temp . '-' . $i;
                }
                $temp = $new_slug;
            }
            $this->attributes['slug'] = $temp;
        }
    }
     /**
     * Saving ChildhoodIllness
     *
     * @param mixed $request Request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function saveChildhoodIllness($request)
    {
        if (!empty($request)) {
            $this->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
            $this->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
            return $this->save();
        }
    }

    /**
     * Updating ChildhoodIllness
     *
     * @param mixed $request Request attributes
     * @param int   $id      get id for updation
     *
     * @return \Illuminate\Http\Response
     */
    public function updateChildhoodIllness($request, $id)
    {
        if (!empty($request)) {
            $illness = self::find($id);
            if ($illness->title != $request['title']) {
                $illness->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
            }
            $illness->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
            return $illness->save();
        }
    }
}
