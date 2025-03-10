<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$table = "papers";

Capsule::schema()->dropIfExists($table);

Capsule::schema()->create("$table", function (Blueprint $table) {

  $table->increments("paper_id");
  $table->integer("subject_id");
  $table->integer("class_id");
  $table->integer("section_id");
  $table->integer("teacher_id");
});
