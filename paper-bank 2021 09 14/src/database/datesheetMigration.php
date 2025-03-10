<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\DB;

$table = "datesheets";

Capsule::schema()->dropIfExists($table);

Capsule::schema()->create("$table", function (Blueprint $table) {
  $table->increments("datesheet_id");
  $table->integer("class_id");
  $table->boolean("status");
  $table->timestamp("created_at")->useCurrent();
});
