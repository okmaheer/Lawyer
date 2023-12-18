<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LaboratoryTest extends Model
{
    protected $table = 'laboratory_test';

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
             if (!LaboratoryTest::all()->where('slug', $temp)->isEmpty()) {
                 $i = 1;
                 $new_slug = $temp . '-' . $i;
                 while (!LaboratoryTest::all()->where('slug', $new_slug)->isEmpty()) {
                     $i++;
                     $new_slug = $temp . '-' . $i;
                 }
                 $temp = $new_slug;
             }
             $this->attributes['slug'] = $temp;
         }
     }

      /**
      * Saving Laboratory Test
      *
      * @param mixed $request Request attributes
      *
      * @return \Illuminate\Http\Response
      */
    public function saveLaboratoryTest($request)
     {
         if (!empty($request)) {
             $this->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
             $this->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
             return $this->save();
         }
     }
 
     /**
      * Updating Laboratory Test
      *
      * @param mixed $request Request attributes
      * @param int   $id      get id for updation
      *
      * @return \Illuminate\Http\Response
      */
    public function updateLaboratoryTest($request, $id)
     {
        if (!empty($request)) {
            $laboratory_test = self::find($id);
            if ($laboratory_test->title != $request['title']) {
                $laboratory_test->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
            }
            $laboratory_test->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
            return $laboratory_test->save();
         }
    }
}
