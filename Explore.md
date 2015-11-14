<h2>Basic of functionality</h2>
The universal construction of files and folders in Dollop gives you opportunity to easy manage and develop different types of modules and add-ons. What we mean when we saying modules and add-ons, what's are differences and importance. <br>The modules in Dollop are folders that's contents basics functionality sectors like already knowable part of websites, users, news, blogs and etc.<br>The add-ons are the sub-functionality in the modules. I will give you one quick example to understand me correctly. Lets say we have module "news" in our website and we need to add a comment to already created module. The solution is very easily. We just need to add comment add-on in add-on folder build.prop int configuration will supply part of configuration that's needed by installation of "comment" add-on. I must note here if you are new to Dollop, that the all installations in platform Dollop are only executable inlet from users that's have access to Control Panel. The customisation of HTML design can be done by editing a default comment template file in module "news" or just copy template file in used theme and modified from there.<br>One of futures of Dollop is to show all available searched templates and themes sub files.<br>
Note:<br> When installing the Dollop CMS kernel class is used like a real class<br> in other cases kernel class is  used in static mode, working only with functions.<br>This does not mean, the function do not interact one with other function.<br>
<br>
<h3>Boot file</h3>

The format of this file is init.<br>This file contending all configuration of website. It's very imported to have backup of this file with your last changes. Other options available for this file are glue code and master initial configuration, allow you to manipulate whole website.<br>
<br>
<h3>Glue code</h3>
<p>This option can attach PHP script with custom configuration and variables used by the external source to main source of Dollop.</p>

<h3>Master/Slave configuration</h3>
Other good future you can examine is master/slave configuration. The idea of this is to load your desired configuration without unpleasant search in configuration fies.<br>To perform this operation, you must prepare "<a href='BOOT.md'>BOOT</a>.prop" and fill needed strings with data.<br>Now upload file in to root of website and erase "config.php" and reload your website. Click submit on showed form and "next" on second page of installation shield. This operation will re-build Dollop configuration.<br>In future I will show all default configuration strings used in Dollop.<br>
<br>
<h3>Classes from class library</h3>
Now is time to mention how to load the PHP classes from class library.<br>Classes are grouped in folders like HTML, MySQL and etc. Like this way you can use many different class that have same use or functionality.<br>One good example, loading mysql_air class like $mysql=new mysql_ai;<br>This will load "ai.php" class feom mysql folder.<br>
<br>
<br>
<h2>System file association</h2>

<a></a>
<h3>Module</h3>
We offer a constantly growing number of new website features that can be easily added to your website at any time. Add-ons are simpler features that do not require a management interface, whereas modules are more complex features that come with a management interface integrated to the main Dollop 4 management functionality.<br>
More about Module file association can be found <a href='page?view=24'>here</a>.<br>
<br>
<h3>Rebooting (new configuration data)</h3>
<strong></strong>Тo renew boot configuration of Dollop, you need to erase config.php file from web root directory and Dollop script will load again <a href='BOOT.md'>BOOT</a>.dp file.<br>
<br>
<h3>Quick note</h3>
<p>Boot file contains hex keys for whole website configuration of Dollop. Changing this data will mess up your website functionality.<br>
<a></a>
<h3>Theme</h3>
The theme in Dollop is facial, html fila used by the Dollop CMS to contribute your design in your website. <br>Themes used by Dollop are based on HTML,CSS and JavaScript files. Therefore this limit many options are available from Dollop design treatment. <br>One of opportunity that offert Dollop are custom HTML header, sub theme files, sub request theme files <br>data, custom init-tags and etc. <br>The theme init configuration is merged with Dollop design init configuration, so this allows you to create the website theme without filling every time initialization data. You can create your own theme with using <a href='page?view=26'>this tutorial.</a>
<strong> Keep in mind, the whole website design dependent on caches, so every time when you do some changes in website it required to be logged as admin.</strong>
<blockquote>
When Control Panel is active, the "kernel" extend the system to comprehensive data.<br>
<br>
</blockquote>
<h3>Templates</h3>
<strong></strong>Templates are available in three Dollop system sectors in priority order like, theme templates, external script templates and spear parts (system default). The HTML template sectors in theme don not have limit of numer, to create your custom sectors (tags) . <br>In order to create your custom template with replacement tags follow the link to <strong>template tutorial</strong>.