Dollop - Installation
=========================

CONTENTS OF THIS FILE: 
 * Known issues
 * Requirements and notes
 * Installation preparations
 * Installation
 * More information


 
KNOWN ISSUES
========================= 

        NOTE:
        This version of Dollop is unstable and  not all problems have been identified.
        Feel free to help us in establishing all shortcomings of Dollop.
    
        - VERY IMPORTANT: the `.htaccess` file must be loaded by Apache.
        - The installation manager doesn't always show the main content of installation.
        - The requirements checker is not tested thoroughly and may have some problems with establishing Dollop requirements.
 
 
 
REQUIREMENTS AND NOTES
=========================

        - LINUX web server.
        - Apache version 2.0 (or higher) is recommended.
        - *The `RewriteEngine` is required! Check the `.htaccess` file for more info.
        - PHP 5.3 (or higher) (http://www.php.net/).
        - MySQL 5.0.15 (or higher) (http://www.mysql.com/).
    
        NOTE:
        At the moment Dollop can be run properly only on a Linux server with Apache 2 and Mysql database.


    
INSTALLATION PREPARATIONS
=========================

        - Confirm that the user under which Apache runs has permission to write into the website directory.
        - Check can be done e.g. via `ls -la` command on the website root directory.
        - Confirm that the mcrypt extension is installed and running properly . 
        - For problems on Ubuntu server just use `sudo apt-get install php5-mcrypt` for installation.
        - Confirm that the GD Library is installed and running properly. 
        - For problems on Ubuntu server just use `sudo apt-get install php5-gd` for installation.
        - Confirm that the Mysql process is running on the server.





INSTALLATION        
=========================
    
1. Download and extract Dollop.

   You can obtain the latest Dollop release from http://fire1.eu -- "dollop files"
   are available in .tar.gz and .zip formats and can be extracted using most
   compression tools.

   To download and extract the files, on a typical Unix/Linux command line, use
   the following commands:

   $  wget http://(for now is not available)
   $  tar -zxvf dollop-[code name].xx.tar.gz

   This will create a new `dollop-[code name].xx/` containing all Dollop files and
   directories. Next move the contents of that directory into a directory

   within your web server's document root or your public HTML directory,
   continue with this command:

   $  mv dollop-[code name].xx/* dollop-[code name].xx/.htaccess /path/to/your/installation

2. Optionally, download a translation.

   By default, Dollop is installed in English, and other languages may be
   installed later. If you prefer to install Dollop in another language from the start:


   - Download a translation file for the correct Dollop version and language
     from the translation server: http://(for now is not available)

   - Place the file into your language directory. 
       language/

   For detailed instructions, visit http://fire1.eu/


3. Run the install script.

   You will be guided through several screens to set up the database, add the
   site maintenance account (the first user, also known as user/1 (root) ), and provide all web site settings.
   
   During installation, several files and directories need to be created, which
   the install script will try to do automatically. However, on some hosting
   environments, manual steps are required, and the install script will tell
   you that it cannot proceed until you fix certain issues.
   
    This is normal and does not indicate a problem with your server.

a). If you get problems with connection to mysql just delete the 'db.php' file from server.
     The installation manager will ask again for mysql information.
     This option is available to moment that '[BOOT].dp' file exists on website root. 

MORE INFORMATION   
=========================   
    
    For more information have a look at the Documentation.txt or Documentation.html files.