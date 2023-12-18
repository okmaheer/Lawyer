<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MartialStatus extends Model
{
    protected $table = 'martial_status';

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
            if (!MartialStatus::all()->where('slug', $temp)->isEmpty()) {
                $i = 1;
                $new_slug = $temp . '-' . $i;
                while (!MartialStatus::all()->where('slug', $new_slug)->isEmpty()) {
                    $i++;
                    $new_slug = $temp . '-' . $i;
                }
                $temp = $new_slug;
            }
            $this->attributes['slug'] = $temp;
        }
    }
     /**
     * Saving Martial Status
     *
     * @param mixed $request Request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function saveMartialStatus($request)
    {
        if (!empty($request)) {
            $this->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
            $this->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
            return $this->save();
        }
    }

    /**
     * Updating Martial Status
     *
     * @param mixed $request Request attributes
     * @param int   $id      get id for updation
     *
     * @return \Illuminate\Http\Response
     */
    public function updateMartialStatus($request, $id)
    {
        if (!empty($request)) {
            $martial_status = self::find($id);
            if ($martial_status->title != $request['title']) {
                $martial_status->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
            }
            $martial_status->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
            return $martial_status->save();
        }
    }
}
