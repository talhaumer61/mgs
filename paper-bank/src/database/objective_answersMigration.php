<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$table = "objective_answers";

Capsule::schema()->dropIfExists($table);

Capsule::schema()->create("$table", function (Blueprint $table) {

  $table->increments("answer_id");
  $table->integer("question_id");
  $table->integer("answer_option");
  $table->integer("is_correct");
});
