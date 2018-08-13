<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>
<div style="margin-top:20px;width:100%;height:auto;" class="center form-control">
<h1 style="margin-top:-6px;color:tomato;width:100%;">Admin Tools</h1>
<?php
  if ($_SESSION['cad_level'] == 0) {
    echo '<a href="' . BASE_URL . '/admin/"><button class="btn btn-primary form-control center" style="width:100%; margin-top:6px;">Website Settings</button></a>';
  }
?>
<a href="<?php echo BASE_URL; ?>/ums/"><button class="btn btn-primary form-control center" style="width:100%; margin-top:6px;">User Managment System</button></a>
<a href="<?php echo BASE_URL; ?>/admin/logs/"><button class="btn btn-primary form-control center" style="width:100%; margin-top:6px;">Logs</button></a>
<a href="<?php echo BASE_URL; ?>/admin/ip-bans/"><button class="btn btn-primary form-control center" style="width:100%; margin-top:6px;">IP Bans</button></a>
<a href="<?php echo BASE_URL; ?>/admin/ip-lookup/"><button class="btn btn-primary form-control center" style="width:100%; margin-top:6px;">IP Lookup</button></a>
<a href="<?php echo BASE_URL; ?>/admin/user-lookup/"><button class="btn btn-primary form-control center" style="width:100%; margin-top:6px;">User Lookup</button></a>
<div id="ress" style="margin-top:20px;width:100%;height:auto;" class="center form-control"><font color="purple">Access: <?php if ($_SESSION['cad_level'] == 0) { echo "Super Admin"; } if ($_SESSION['cad_level'] == 1) { echo "Admin"; }?></font></div>
</div>