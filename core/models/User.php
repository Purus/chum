<?php

namespace Chum\Core\Models;

class User
{
	protected $table = 'users';
	protected $fillable = ['firstName', 'lastName', 'updated_at', 'created_at'];

}