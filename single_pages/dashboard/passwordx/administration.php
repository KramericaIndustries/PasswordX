<?php defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Custom Admin panel for PasswordX - Provides a simplified entrypoint into all the various backend admin panels
 * (c) 2014 PasswordX
 * Apache v2 License
 */
?>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('PasswordX Dashboard'), t('This is your personal control panel for administrating PasswordX.'), false, false);?>
<div class="ccm-pane-body"> 
<h2>Hello <?php echo ucfirst($uobj->getUserName()); ?></h2>
<div class="row-fluid">
 <div class="span9">
 <p>This is the main PasswordX control panel. Here you have access to the most common functions. If you need help, please take a look at the user guide.</p>

 </div>
 <div class="span3">
  <a href="<?php echo $this->controller->guide_url; ?>" target="_blank" class="btn primary pull-right"><span class="icon-file icon-white"></span> User Guide</a>
 </div>
</div>

<hr>
<h2>Passwords &amp; Content</h2>
	<div class="well" >

	<ul class="nav nav-list" >
		
	<li>
	<a href="/index.php/dashboard/passwordx/designer/"><i class="icon-th"></i> Design your own Password Blocks</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/blocks/types/"><i class="icon-wrench"></i> Manage all Block/Content Types</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/files/search/"><i class="icon-picture"></i> File Manager</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/passwordx/export/"><i class="icon-download-alt"></i> Export Passwords</a>
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
	
	<?php
	 //Token for putting page in edit mode
	 $token = Loader::helper('validation/token')->generate();
	?>
	
	<li>
	<a href="/index.php/login/?ctask=check-out&ccm_token=<?php echo $token; ?>"><i class="icon-picture"></i> Customize Login page</a>
	</li>		
	
	<li>
	<a href="/index.php/dashboard/system/basics/icons/"><i class="icon-star"></i> Bookmark Icons</a>
	</li>	
	
	<li>
	<a href="/index.php/dashboard/system/basics/editor/"><i class="icon-align-left"></i> Rich Text Editor</a>
	</li>	
	
	<li>
	<a href="/index.php/dashboard/system/basics/multilingual/"><i class="icon-font"></i> Languages</a>
	</li>	
	
	<li>
	<a href="/index.php/dashboard/system/mail/method/"><i class="icon-wrench"></i> Mail/SMTP Method</a>
	</li>

	</ul>
	</div>
	
<h2>Security &amp; Login</h2>
	<div class="well" >
	<ul class="nav nav-list">

	<li>
	<a href="/index.php/dashboard/passwordx/two_factor_configuration/"><i class="icon-cog"></i> Two-Factor Authentication Configuration</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/permissions/ip_blacklist/"><i class="icon-warning-sign"></i> IP Blacklist</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/permissions/users/"><i class="icon-warning-sign"></i> User Permissions</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/permissions/files/"><i class="icon-warning-sign"></i> File Manager Permissions</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/permissions/file_types/"><i class="icon-warning-sign"></i> Allowed File Types</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/reports/logs/"><i class="icon-time"></i> View Logs</a>
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
	<a href="/index.php/dashboard/"><i class=""></i> Standard Concrete5 Dashboard</a>
	</li>
	
	<li>
	<a href="/index.php/dashboard/system/"><i class=""></i> All Standard Concrete5 System & Settings</a>
	</li>
	
	</ul>
	</div>

	<div style="text-align: center;">
	<p>PasswordX <?php echo APP_VERSION; ?> (c) <?php echo date("Y"); ?> - <a href="<?php echo GITHUB_REPO_URL; ?>">gitHub</a> - <a href="/LICENSE.txt">Apache V2 license</a></p>
	 Built with the <a href="<?php echo CONCRETE5_ORG_URL; ?>">Concrete5 MVC Framework</a>
	</div>
	

</div> 

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper()?>