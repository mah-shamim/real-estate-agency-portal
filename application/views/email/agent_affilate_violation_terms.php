<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?php echo $title; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="padding: 10px">
<a href="<?php echo site_url(); ?>"><img title="website logo" alt="logo png" src="<?php echo base_url('admin-assets/img/stamp.png');?>" /></a>
<br />
<?php 

    $message = str_replace("{address}", $property['address'], lang_check('agent_affilate_violation_terms'));
    $message = str_replace("{date_submit}", $property['date'], $message);
    
    echo $message

?>:
<br />
<a href="<?php echo site_url(); ?>"><?php 
$json_obj = json_decode($property['json_object']);
echo $json_obj->field_10; ?></a>
<br />
</body>
</html>