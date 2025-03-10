<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$table = "answers";

Capsule::schema()->dropIfExists($table);

Capsule::schema()->create("$table", function (Blueprint $table) {

  $table->increments("answer_id");
  $table->integer("answer");
  $table->integer("answer_option");
  $table->integer("obtained_marks");
  $table->integer("question_id");
});
