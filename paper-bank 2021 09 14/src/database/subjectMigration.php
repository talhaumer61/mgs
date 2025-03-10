<?php


use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$table = "subjects";

Capsule::schema()->dropIfExists($table);

Capsule::schema()->create($table, function (Blueprint $table) {
  $table->bigIncrements("subject_id");
  $table->string("name", 200);
  $table->integer("class_id");
});
