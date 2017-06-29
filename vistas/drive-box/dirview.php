<?php 
include(dirname(__FILE__) ."/class/dir.php"); 
?> 
<!DOCTYPE html>
<html> <head>
  <meta charset="utf-8">
  <meta http-Equiv="Cache-Control" Content="no-cache">
  <meta http-Equiv="Pragma" Content="no-cache">
  <meta http-Equiv="Expires" Content="0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <title>iDrive Sipejuve</title>
  <link rel="stylesheet" href="./assets/css/listr.pack.css" />
  <script>
//        window.onload = function () {
//            window.parent.chatApp.ajustarIframe("directory", "contenidoArchivosModal");
//        };
  </script>
</head>
<body dir="ltr">
  <div class="container" id="content">
    <div class="row">
      <div class="col-xs-6 col-sm-4 col-md-3 col-xs-offset-6 col-sm-offset-8 col-md-offset-9">
          <div class="form-group has-feedback">
            <label class="control-label sr-only" for="search">Search</label>
            <input type="text" id="listr-search" class="form-control" placeholder="Buscar...">
          <i class="fa fa-fw fa-search form-control-feedback"></i>
         </div>
      </div>
    </div>
    <div class="table-responsive">
      <table id="listr-table" class="table table-hover">
        <thead>
          <tr>
            <th id="file-count" class="text-right" data-sort="int">#</th>
            <th class="col-sm-6 text-left" data-sort="string">Nombre</th>
            <th class="col-sm-2 text-right" data-sort="int">Tamaño</th>
            <th class="col-sm-2 text-right" data-sort="int">Fecha</th>
            <th class="col-sm-2 text-right" data-sort="string">Subido por</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <td colspan="5">
              <small class="pull-left text-muted" dir="ltr"><?=$nofiles?> archivo(s), <?=number_format(($espacio/1024/1024),2)?>MB en total</small>
            </td>
          </tr>
        </tfoot>
        <tbody>

        <tr>
          <?php
            foreach($filelist as $dirent):
            ?>

            <tr>
            <td class="text-muted text-right" data-sort-value="<?=$dirent['id']?>"><?=$dirent['id']?></td>
            <td class="text-left" data-sort-value="<?=$dirent['filename']?>">
            <i class="fa fa-fw <?=$dirent['fileicon']?> "></i>&nbsp;
            <a href="<?=$dirent['filenamereal']?>" class="<?=$dirent['modalclass']?>"  data-modified="<?=$dirent['kbytes']?>"><?=$dirent['filename']?></a></td>
            <td class="text-right" data-sort-value="<?=$dirent['bytes']?>" title="<?=$dirent['bytes']?> bytes"><?=$dirent['kbytes']?></td>
            <td class="text-right" data-sort-value="<?=$dirent['utime']?>" title="<?=$dirent['dtime']?>"><?=$dirent['dtime']?></td>
            <td class="text-right" data-sort-value="<?=$dirent['owner']?>"><?=$dirent['owner']?></td>
            </tr>

            <?php
            endforeach;
            ?>

          </tr>
        </tbody>
      </table>
    </div>

    <div class="modal fade" id="viewer-modal" tabindex="-1" role="dialog" aria-labelledby="file-name" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-body"></div>
          <div class="modal-footer">
            <div class="pull-left">
              <button type="button" class="btn btn-link highlight hidden">Apply syntax highlighting</button>
            </div>
            <div class="pull-right">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="./assets/js/jquery.min.js"></script>
  <script type="text/javascript" src="./assets/js/listr.pack.js"></script>

</body>
</html>
