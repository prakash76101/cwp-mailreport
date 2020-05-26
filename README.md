# cwp-mailreport
Installation Procedure
This code is  translated from portuguese language to english to get  email sent and recd report from postfix maillog
upload the file mailrelatorio.php in /usr/local/cwpsrv/htdocs/resources/admin/modules/

update(copy) the 3rdparty .php located in  /usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php 
with the code provided in 3rdparty.php file 
then follow the steps below to get the report
First you will need to locate your Postfix log. On most CentOS systems, this is /var/log/maillog by default.
	1)yum install perl-Date-Calc  (perl tools needed for pflogsumm)
  2)yum install mailx
  3)Installing Pflogsumm
	In this section, you will install and configure Pflogsumm.
  a) mkdir /tools (make a directory in root folder)
  b) cd /tools (change directory)
  c) sudo curl -O http://jimsun.linxnet.com/downloads/pflogsumm-1.1.3.tar.gz ( dowload the latest version)
  d) sudo tar -xzf pflogsumm-1.1.3.tar.gz ( unzip)
  e) sudo mv pflogsumm-1.1.3 pflogsumm (rename)
  f) sudo chmod +x /tools/pflogsumm/pflogsumm.pl (Make the Pflogsumm directory executable)
  g) sudo perl /tools/pflogsumm/pflogsumm.pl /var/log/maillog (Test the script by running the following command. Make sure you substitute /var/log/maillog with your actual Postfix log location.)
  You will see a large amount of information regarding your mail server, which go over in the next section
  Now you will set up a Cron job to run the Pflogsumm Perl script and   send the mail server stats to you as a daily email. This is great for   monitoring your mail server. The example below schedules the email for   1:01 PM every day. For details on how to customize the time the email is   sent, you should read the https://www.linode.com/docs/linux-tools/utilities/cron article.
  
  
  Cron Tab setup:
  sudo crontab -e
  00 01 * * * perl /tools/pflogsumm/pflogsumm.pl -e -d yesterday /var/log/maillog | mail -s 'Logwatch for Postfix' root.
  service crond restart
  
