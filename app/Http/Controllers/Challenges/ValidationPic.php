<?php

namespace App\Http\Controllers\Challenges;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;
use Image;
use App\Http\Controllers\Controller;


class ValidationPic extends Controller
{
    public function show($name)
    {
        $pic = Storage::disk('validation-proofs')->get($name);
        return Image::make($pic)->response();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSmall($name)
    {
        $pic = Storage::disk('validation-proofs')->get($name);
        $pic = Image::make($pic);
        $pic->resize(null, 200, function($constraint) {
            $constraint->aspectRatio();
        });
        return $pic->response();

    }
}
