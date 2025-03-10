<?php

use \Illuminate\Database\Capsule\Manager as Capsule;
use \Illuminate\Database\Schema\Blueprint;

$table = "classes_has_sections";

Capsule::schema()->dropIfExists($table);

Capsule::schema()->create($table, function (Blueprint $table) {
    $table->increments('id');
    $table->bigInteger('class_id');
    $table->bigInteger('section_id');
});

