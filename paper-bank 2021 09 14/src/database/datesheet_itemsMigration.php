<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$table = "datesheet_items";

Capsule::schema()->dropIfExists($table);

Capsule::schema()->create("$table", function (Blueprint $table) {
  $table->increments("datesheet_item_id");
  $table->integer("datesheet_id");
  $table->boolean("paper_id");
  $table->timestamp("starting_at");
  $table->integer("duration");
});
