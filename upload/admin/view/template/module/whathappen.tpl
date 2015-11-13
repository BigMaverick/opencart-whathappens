<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-whathappen" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-whathappen" class="form-horizontal">
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-limit"><?php echo $entry_limit; ?></label>
            <div class="col-sm-10">
              <input type="text" name="whathappen_limit" value="<?php echo $whathappen_limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-namelength"><?php echo $entry_namelength; ?></label>
            <div class="col-sm-10">
              <input type="text" name="whathappen_namelength" value="<?php echo $whathappen_namelength; ?>" placeholder="<?php echo $entry_namelength; ?>" id="input-namelength" class="form-control" />
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-ajax"><?php echo $entry_ajax; ?></label>
            <div class="col-sm-10">
              <select name="whathappen_ajax" id="input-ajax" class="form-control">
                <?php if ($whathappen_ajax) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-datetime"><?php echo $entry_datetime; ?></label>
            <div class="col-sm-10">
              <select name="whathappen_datetime" id="input-datetime" class="form-control">
		        <?php if ($whathappen_datetime == '0') { ?>
                <option value="0" selected="selected"><?php echo $entry_datetime_no; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $entry_datetime_no; ?></option>
                <?php } ?>
				<?php if ($whathappen_datetime == '1') { ?>
                <option value="1" selected="selected"><?php echo $entry_datetime_circa; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $entry_datetime_circa; ?></option>
                <?php } ?>
				<?php if ($whathappen_datetime == '2') { ?>
                <option value="2" selected="selected"><?php echo $entry_datetime_date; ?></option>
                <?php } else { ?>
                <option value="2"><?php echo $entry_datetime_date; ?></option>
                <?php } ?>
				<?php if ($whathappen_datetime == '3') { ?>
                <option value="3" selected="selected"><?php echo $entry_datetime_time; ?></option>
                <?php } else { ?>
                <option value="3"><?php echo $entry_datetime_time; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
		  
		  
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>