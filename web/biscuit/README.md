biscuit
=======

Biscuit is a minimal PHP "framework" (at least, a collection of often useful functions) and (very) minimal template system I often use in my projects. no endless file, no POO, no bullshit.

The goal isn't to be as big (and complicated) as symphony or cakePHP (hence the name, if you didn't get it), but to have a ready-to-use,
set of functions, like twitter's bootstrap.

It's released on WTFPL licence, which basicaly means you can do everything you want with it, i just release it in case of somebody
will find that useful, and to have a git repo with that, nothing more.


## Man biscuit:

### There's 3 files for biscuit:
* init.php, which you need to include in your controller's pages
* config.php, which include DB host/login/password, SHA1 salt and AES encryption key. you WILL NEED to edit it with your SQL creditentials.
* lib.php, which include all the functions

### Controller-template system:
Biscuit uses a "controller-template" system, basically, that means that you include init.php and do your SQL and data
stuff in a file.php , and have the html template in tpl/file.php (even you can have a file.php rendering a tpl/other.php).
That's useful to separate data treatment and templating, to have a easy to read code, so, biscuit is built around that.

### Template system:
The template system is pretty simple, you just need to call the render function as render($page, $vars).
The $vars param is optional, but if you include a array there, it will be extracted, to give an example, if you do
render('test', array('users' => $usersResults, 'articles' => $articlesResults), in your tpl, you do have an $users and a $articles array containing the $usersResults and $articlesResults.
In theory, it's built as you don't need to do more than echo and foreach in the template pages.

### Template structure:
As a legacy from my projects, the template system do have 3 separate mandatory templates (if you don't want them, just edit the function):
* tpl/head.php, having basicaly html to /head
* tpl/header.php, pretty obvious
* tpl/foot.php, having the footer and the end of the page to the /html tag.

### Lib functions:
Even you can just open it and read it, there's a list of the biscuit's functions:
* debug($var, $die = true): just print the content of the $var, and die (default) or continue.
* render($page, $vars = null): the template system...
* getVar($varName, $noHtml): get a $_POST or $_GET var, and disable HTML tags by default (anti XSS).
* delDir($dir): delete a directory and all files within, like rm -rf.
* genPassword($length = 9): generate a random password of $length characters
* passwordHash($password): generate a SHA-512 salted hash of $password.
* aesEncrypt($var): encrypt $var using AES 128 cipher.
* aesDecrypt($var): decrypt $var... you got it.
* getCurl($url): get content from $url via CURL.
* timeAgo($time): format a timestamp to something like '3 hours ago'
* formatSize($size): format full size to something human readable

NB: there's NO mysql_related functions, because i use PDO (and you should to), if you are a bad dev, don't count on biscuit to avoid SQL injections, use cakePHP or symphony or (insert bloat framework here) instead.

## Contributing:

If you have an idea of improvement/function/whatelse, feel free to send me a message/fork it :)

(if you're too lazy or dumb to know how to read and use around 100 lines of php, goto learn, it is a dev "tool", not a dumbass-proof non-coder-website-creation-bullshit thing.)
