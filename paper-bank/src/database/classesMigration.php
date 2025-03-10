<?php


use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;


Capsule::schema()->dropIfExists("classes");

Capsule::schema()->create('classes', function (Blueprint $table) {
  $table->increments("class_id");
  $table->string("name", 100);
  $table->timestamp("created_at")->useCurrent();
});
