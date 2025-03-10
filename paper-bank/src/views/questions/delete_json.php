<form action="<?= URLROOT ?>/questions/delete/<?=$data['id']?>" class="mb-lg validate" method="post" accept-charset="utf-8" novalidate="novalidate">
  <header class="panel-heading">
    <h4 class="panel-title"><?=$data['title']?></h4>
  </header>

  <div class="panel-body">
    <div class="row">
      <div class="col-md-12 mx-auto">
        <div class="form-group">
          <input type="hidden" name="id_class" value="<?=$data['id_class']?>">
          <input type="hidden" name="id_subject" value="<?=$data['id_subject']?>">
          <h5 class="text-center"> Are You Sure? You want to delete this Question!</h3>
        </div>
      </div>
    </div>
  </div>

  <footer class="panel-footer">
    <div class="row">
      <div class="col-md-12 text-right">
        <button class="btn btn-default modal-dismiss">No</button>
        <button type="submit" id="submit" name="submit" class="mr-xs btn btn-primary ">Yes</button>
      </div>
    </div>
  </footer>
</form>