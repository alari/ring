<?
`svn up http://svn.orena.org/ring/trunk /home/users/m/mirari/domains/ring --username messire --password Q6mTNA3`;
`svn info /home/users/m/mirari/domains/ring | grep Revision  | awk -F ":" '{print $2}' > /home/users/m/mirari/domains/ring/version.txt`;