<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$table = "questions";

Capsule::schema()->dropIfExists($table);

Capsule::schema()->create("$table", function (Blueprint $table) {

  $table->increments("question_id");
  $table->text("question");
  $table->boolean("is_objective")->default(false);
  $table->integer("marks");
  $table->integer("paper_id");
});
