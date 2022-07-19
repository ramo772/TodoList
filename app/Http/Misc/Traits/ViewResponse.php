<?php

namespace App\Http\Misc\Traits;

trait ViewResponse
{

    public function pagLimit($limit = 10)
    {
        return $limit;
    }

	public function getIndexView($name, $data)
    {
        return view( $name . '.index', compact('name', 'data'));
    }

}