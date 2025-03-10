<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$table = "teachers";

//Check if the table exist
Capsule::schema()->dropIfExists($table);

Capsule::schema()->create($table, function (Blueprint $table) {
  $table->bigIncrements("teacher_id");
  $table->string("teacher_name", 100);
  $table->string("email", 70);
  $table->string("teacher_cnic", 13);
  $table->integer("age");
  $table->string("phone", 11);
  $table->integer("salary");
  $table->integer("gender"); // Index of an array will be stored in
  $table->string("address");
  $table->string("image")->nullable();
  $table->timestamps();
});
