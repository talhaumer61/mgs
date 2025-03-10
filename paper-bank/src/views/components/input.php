<?php

function input($classes, $label, $name, $placeholder)
{
  echo "<div class='form-group $classes'>
          <label class='control-label text-capetilize'>$label<span class=' required' aria-required='true'>*</span></label>
          <input type='text' class='form-control' name='$name' id='$name' required='' title='$placeholder' aria-required='true'>
        </div>";
}
