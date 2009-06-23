<?
$folder = dirname(__FILE__);
shell_exec("svn up http://svn.orena.org/ring/trunk $folder --username messire --password Q6mTNA3");
shell_exec("svn info $folder | grep Revision  | awk -F \":\" '{print $2}' > $folder/version.txt");
shell_exec("svn co http://svn.orena.org/orena/trunk/O $folder/O --username public --password orena");
echo file_get_contents($folder.'/version.txt');