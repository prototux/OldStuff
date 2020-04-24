PlopBox
=======

The sources of plopbox.fr, a system to manage our left4dead2 and teamspeak3 servers for me and my friends.

# Requirements
* Sudo
* P7zip, p7zip-full and p7zip-rar
* Two (or edit the files) Left4Dead2 servers (But it should work with others SRCDS ones), and a TeamSpeak3 server

# How to use it (yeah, like that note):
* Basically, everything is in install/
* Copy config.php.dist to config.php
* Edit config.php with username/password/etc
* Use install.php to install the DB
* Copy/edit the .sh scripts
* Edit sudoers to match your unix user and scripts path
* DELETE THE INSTALL/ FOLDER! if not, everybody can add himself as a admin!

# Warning
Remember that it was made to be custom for plopbox.fr, not to be installed in someone else's server so, i can't guarantee it will work flawlessly. a bit of PHP and bash skills may be needed to work with this code.

I still need to clean the srcds class and the ts3 class because it's just overkill for what i'm doing...

Install.php is now untested, i'm to tired (it's 5:30AM here right now) to setup a test env to test if it works correctly.
