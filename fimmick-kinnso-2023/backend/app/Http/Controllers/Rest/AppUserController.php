<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Rest\RestController;
use Illuminate\Http\Request;
use App\Models\AppUser;

class AppUserController extends RestController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    // function __construct(Member $member, Rest $resource)
    function __construct(AppUser $model)
    {
        $this->model = $model;
        // $this->resource = $resource;
    }
}
