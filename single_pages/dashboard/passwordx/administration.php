<?php defined('C5_EXECUTE') or die("Access Denied.");
 /*
  Custom Admin panel for PasswordX - Provides a simplified entrypoint into all the various backend admin panels
  (c) 2014 PasswordX
  MIT License
  AHJ
 */
 
   
?>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('PasswordX Dashboard'), t('This is your personal control panel for administrating PasswordX.'), false, false);?>
<div class="ccm-pane-body"> 
<h2>Hello <?php echo ucfirst($uobj->getUserName()); ?></h2>
<div class="row-fluid">
 <div class="span9">
 <p>This is your personal control panel for administrating PasswordX. Here you have access to the most common functions. If you need help, please take a look at the user guide.</p>

 </div>
 <div class="span3">
  <a href="<?php echo $this->controller->guide_url; ?>" target="_blank" class="btn primary pull-right"><span class="icon-file icon-white"></span> User Guide (PDF)</a>
 </div>
</div>

<hr>


<h2>Interface</h2>

	<div class="well" >

	<ul class="nav nav-list" >
		
	<li>
	<a href="/index.php/dashboard/passwordx/administration/"><i class="icon-th"></i> (Todo) Manage Password Blocks</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/blocks/types/"><i class="icon-wrench"></i> Block Types</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/basics/icons/"><i class=""></i> Bookmark Icons</a>
	</li>	
	
	<li>
	<a href="/index.php/dashboard/system/basics/editor/"><i class=""></i> Rich Text Editor</a>
	</li>	
	
	<li>
	<a href="/index.php/dashboard/system/basics/multilingual/"><i class=""></i> Languages</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/files/search/"><i class="icon-picture"></i> File Manager</a>
	</li>	
	
	
	
	</ul>
	</div>

<h2>Users</h2>

	<div class="well" >
	<ul class="nav nav-list">
		
	<li>
	<a href="/index.php/dashboard/users/search/"><i class="icon-user"></i> Manage Users</a>
	</li>
	
		
	<li>
	<a href="/index.php/dashboard/users/groups/"><i class="icon-globe"></i> User Groups</a>
	</li>

	</ul>
	</div>

	
<h2>Configuration</h2>

	<div class="well" >
	<ul class="nav nav-list">
	
	<li>
	<a href="/index.php/dashboard/users/authy/"><i class="icon-cog"></i> Two-Factor (Authy) Configuration</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/mail/method/"><i class=""></i> Mail/SMTP Method</a>
	</li>

	</ul>
	</div>
	
<h2>Security</h2>

	<div class="well" >
	<ul class="nav nav-list">

	<li>
	<a href="/index.php/dashboard/system/permissions/ip_blacklist/"><i class=""></i> IP Blacklist</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/permissions/users/"><i class=""></i> User Permissions</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/permissions/files/"><i class=""></i> File Manager Permissions</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/permissions/file_types/"><i class=""></i> Allowed File Types</a>
	</li>
	
	
	</ul>
	</div>	

	
<h2>Developer</h2>

	<div class="well" >
	<ul class="nav nav-list">
	
	<li>
	<a href="/index.php/dashboard/system/permissions/maintenance_mode/"><i class=""></i> Maintenance Mode</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/environment/info/"><i class=""></i> Environment Information</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/environment/debug/"><i class=""></i> Debug Settings</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/environment/logging/"><i class=""></i> Logging Settings</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/environment/file_storage_locations/"><i class=""></i> File Storage Locations</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/environment/proxy/"><i class=""></i> Proxy Server</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/backup_restore/backup/"><i class=""></i> Backup Database</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/backup_restore/database/"><i class=""></i> Database XML</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/"><i class=""></i> Standard Concrete5 Dashboard</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/"><i class=""></i> All Standard Concrete5 System & Settings</a>
	</li>
	

	</ul>
	</div>


</div> 
<script type="text/javascript">

$(function() {

 
});

</script>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper()?>