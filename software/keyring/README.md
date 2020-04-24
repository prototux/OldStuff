# keyring
A simple password manager using GPG

## Structure
Configuration is stored in `$XDG_CONFIG_HOME/keyring/config`  
Passwords are stored in `$XDG_DATA_HOME/keyring`  
Passwords are encrypted using GPG

## USAGE
keyring [options] key
Options:
* -a				add a password
* -e				edit a password
* -d				delete a password
* -g [size]			generate random password (doesn't ask for one)
* -h				print this help
* -l				list the passwords

## Note on security
keyring doesn't ask for password, having the password in the configuration file, please create a (sub)key dedicated for it to avoid security issues.
