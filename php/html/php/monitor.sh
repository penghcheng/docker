#!/bin/bash
active=`ps aux|grep consumer.php|grep -v grep | wc -l`
if [ $active == 0 ];then
	echo "php" >> /var/www/html/test.log
		/usr/local/bin/php /var/www/html/php/consumer.php > /dev/null &
fi
	echo "php111" >> /var/www/html/test.log
