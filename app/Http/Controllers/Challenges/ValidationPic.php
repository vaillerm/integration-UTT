<?php

namespace App\Http\Controllers\Challenges;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;
use Image;
use App\Http\Controllers\Controller;


class ValidationPic extends Controller
{

    private function formats() : array 
    {
        return [
            "jpg",
            "png",
            "jpeg"
        ];
    }

    public function show($name)
    {
        if($this->hasCorrectFormat($name))
        {
            $pic = Storage::disk('validation-proofs')->get($name);
            return Image::make($pic)->response();
        }else {
            return new Response("Mauvais format");
        }
    }

    /**
     * Check whether the file has the extension png or jpg
     * it's not enough to check the format, but that's 
     * better than nothing I guess
     */
    private function hasCorrectFormat($name) 
    {
        foreach($this->formats() as $format) 
        {
            if(str_contains($name, $format)) 
                return true;
        }
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSmall($name)
    {
        if($this->hasCorrectFormat($name))
        {
            $pic = Storage::disk('validation-proofs')->get($name);
            $pic = Image::make($pic);
            $pic->resize(200, 200, function($constraint) {
                $constraint->aspectRatio();
            });
            return $pic->response();
        } else {
            return new Response("Mauvais format");
        }
    }
}
