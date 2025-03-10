<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$table = "students";

//Check if the table exist
Capsule::schema()->dropIfExists($table);

Capsule::schema()->create($table, function (Blueprint $table) {
    $table->bigIncrements("student_id");
    $table->string("student_name", 100);
    $table->string("guardian_name", 100);
    $table->string("guardian_cnic", 13);
    $table->integer("age");
    $table->date("dob");
    $table->date("admission_date");
    $table->string("phone", 11);
    $table->integer("fee");
    $table->boolean("gender"); // Index of an array will be stored in
    $table->integer("form_no");
    $table->integer("section_id"); // Reference to the sections table
    $table->string("roll_num");
    $table->string("city", 70);
    $table->string("address");
    $table->string("image")->nullable();
    $table->timestamps();
});
