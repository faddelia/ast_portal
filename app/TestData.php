<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestData extends Model
{
	// because it's trying to find "test_datas" table...
    protected $table = 'test_data';
}
