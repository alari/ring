<?
system("svn up http://svn.orena.org/ring/trunk /home/users/m/mirari/domains/ring --username messire --password Q6mTNA3 -r".(isset($_GET["r"])&&is_numeric($_GET["r"]?$_GET["r"]:"HEAD")));
`svn info /home/users/m/mirari/domains/ring | grep Revision  | awk -F ":" '{print $2}' > /home/users/m/mirari/domains/ring/version.txt`;
`svn co http://svn.orena.org/orena/trunk/O /home/users/m/mirari/domains/ring/O --username public --password orena`;
echo file_get_contents('/home/users/m/mirari/domains/ring/version.txt');