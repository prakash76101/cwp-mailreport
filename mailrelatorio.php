	<li><a href="index.php?module=mailrelatorio"><span class="icon16 icomoon-icon-arrow-right-3"></span>Email Report</a></li> 
	<?php
	set_time_limit(1800);
	if ( !isset( $include_path ) )
	{
		echo "invalid access";
		exit( );
	}
	?>
	<h2> Email Report </h2>


	Provides the report of the emails.
	<br />
	<?php
	if(file_exists('/tools/pflogsumm/pflogsumm.pl')){// check if the program exists
		?>
		<form action="index.php?module=mailrelatorio" method="post" id="gerarelform">
		<input type="hidden" name="gerarel" value="true" />
		<table>
		<tr>
		<td>   	
		<select name="parm_d">
		<option value="logcorrente" <?php if(@$_POST['parm_d'] == 'logcorrente'){ echo 'selected="selected"'; } ?> >From the current log (/var/log/mailog)</option>
		<option value="today" <?php if(@$_POST['parm_d'] == 'today'){ echo 'selected="selected"'; } ?> >Today</option>
		<option value="yesterday" <?php if(@$_POST['parm_d'] == 'yesterday'){ echo 'selected="selected"'; } ?> >Yesterday</option>
		</select>
		</td>
		<td>
		<input type="checkbox" name="extensivo" value="true" <?php if(@$_POST['extensivo'] == 'true'){ echo ' checked="checked"'; } ?> > Extensive (detailed)
		</td>
		<td>
		<input type="checkbox" name="todososlogs" value="true" <?php if(@$_POST['todososlogs'] == 'true'){ echo ' checked="checked"'; } ?> >
		Include all archived logs (use only if log churn is high)
		</td>
		</tr>
		<tr>
		<td colspan="3">
		<center><button type="submit" class="btn btn-info" id="gerarrealtoriobut">Generate report</button></center>
		</td>
		</tr>
		</table>
		</form>
		<p>
	  <?php
		if(@$_POST['gerarel'] == true){
			$param = '';
			if(@$_POST['parm_d'] == 'today'){
				$param .= '-d today ';	
			}else if(@$_POST['parm_d'] == 'yesterday'){
				$param .= '-d yesterday ';
			}
			if(@$_POST['extensivo'] == true){
				$param .= '-e ';
			}
			$outrosarq = "";
			if(@$_POST['todososlogs'] == true){
				$retornoprompt = array();
				$outrosarq = " ";// include first space
				exec("ls -l /var/log/maillog-*", $retornoprompt);
				for($i = 0; $i < count($retornoprompt); $i++){
					$logfile = explode(' ', $retornoprompt[$i]);
					$outrosarq .= $logfile[8]." ";
				}	
			}
			$retornoprompt = array();
			exec("/tools/pflogsumm/pflogsumm.pl ".$param."/var/log/maillog".$outrosarq, $retornoprompt);
			$fechatable = false;
			$col = 0;
			$pula_linha = false;
			echo '<pre>';
			for($i = 0; $i < count($retornoprompt); $i++){
				echo $retornoprompt[$i]."<br />";
			}
			echo '</pre>';
		}
	}else{//verifica se o programa existe
	?>
	O programa <b>Pflogsumm</b> not installed follow the steps below and install the program with the following directory:<br />
	/tools/pflogsumm/pflogsumm.pl</p>
		<p>The <b>Pflogsumm</b> program is not installed, follow the steps below and install the program with the following directory:<br />
		  /tools/pflogsumm/pflogsumm.pl<br />
		</p>
	<h2>Prerequisites</h2>
	<p>Make sure these prerequisites are installed:</p>
	<ul>
	  <li>Postfix</li>
	  <li>Perl 5.004</li>
	  <li>Perl&rsquo;s Date::Calc module</li>
	</ul>
	<p>Perl 5.004 will most likely be installed by default. Run this command to install the <strong>Date::Calc</strong> module:</p>
	<div>
	  <table>
		<tbody>
		  <tr>
			<td><pre>1</pre></td>
			<td><pre>sudo yum install perl-Date-Calc  </pre></td>
		  </tr>
		</tbody>
	  </table>
	</div>
	<p>Finally, you will need to locate your Postfix log. On most CentOS systems, this is /var/log/maillog by default.Also to send email by cron you need to install mailx  </P><td><pre>yum install mailx</pre></td>
	<h2>Installing Pflogsumm</h2>
	<p>In this section, you will install and configure Pflogsumm.</p>
	<ol>
	  <li>
		<p>Move to the /usr/local directory:</p>
		<div>
		  <table>
			<tbody>
			  <tr>
				<td><pre>1</pre></td>
				<td><pre>mkdir /tools</pre></td>
			  </tr>
			  <tr>
				<td><pre>2</pre></td>
				<td><pre>cd /tools</pre></td>
			  </tr>
			</tbody>
		  </table>
		</div>
	  </li>
	  <li>
		<p>Use <strong>curl</strong> to download Pflogsumm:</p>
		<div>
		  <table>
			<tbody>
			  <tr>
				<td><pre>1</pre></td>
				<td><pre>sudo curl -O http://jimsun.linxnet.com/downloads/pflogsumm-1.1.3.tar.gz  </pre></td>
			  </tr>
			</tbody>
		  </table>
		</div>
	  </li>
	  <li>
		<p>Expand the files:</p>
		<div>
		  <table>
			<tbody>
			  <tr>
				<td><pre>1</pre></td>
				<td><pre>sudo tar -xzf pflogsumm-1.1.3.tar.gz  </pre></td>
			  </tr>
			</tbody>
		  </table>
		</div>
	  </li>
	  <li>
		<p>Rename the Pflogsumm directory:</p>
		<div>
		  <table>
			<tbody>
			  <tr>
				<td><pre>1</pre></td>
				<td><pre>sudo mv pflogsumm-1.1.3 pflogsumm  </pre></td>
			  </tr>
			</tbody>
		  </table>
		</div>
	  </li>
	  <li>
		<p>Make the Pflogsumm directory executable:</p>
		<div>
		  <table>
			<tbody>
			  <tr>
				<td><pre>1</pre></td>
				<td><pre>sudo chmod +x /tools/pflogsumm/pflogsumm.pl  </pre></td>
			  </tr>
			</tbody>
		  </table>
		</div>
	  </li>
	</ol>
	<h2>Testing</h2>
	<p>Test the script by running the following command. Make sure you substitute <strong>/var/log/maillog</strong> with your actual Postfix log location.</p>
	<div>
	  <table>
		<tbody>
		  <tr>
			<td><pre>1</pre></td>
			<td><pre>sudo perl /tools/pflogsumm/pflogsumm.pl /var/log/maillog  </pre></td>
		  </tr>
		</tbody>
	  </table>
	</div>
	<p>You will see a large amount of information regarding your mail server, which we&rsquo;ll go over in the next section.</p>
	<h2>Reading Output</h2>
	<p>Your report output will contain the following information in an easy-to-read textual format perfect for email:</p>
	<ul>
	  <li>
		<dl>
		  <dt>Total number of:</dt>
		  <dd>
			<ul>
			  <li>Messages received, delivered, forwarded, deferred, bounced and rejected</li>
			  <li>Bytes in messages received and delivered</li>
			  <li>Sending and Recipient Hosts/Domains</li>
			  <li>Senders and Recipients</li>
			  <li>Optional SMTPD totals for number of connections, number of hosts/domains connecting, average connect time and total connect time</li>
			</ul>
		  </dd>
		</dl>
	  </li>
	  <li>Per-Day Traffic Summary (for multi-day logs)</li>
	  <li>Per-Hour Traffic (daily average for multi-day logs)</li>
	  <li>Optional Per-Hour and Per-Day SMTPD connection summaries</li>
	  <li>
		<dl>
		  <dt>Sorted in descending order:</dt>
		  <dd>
			<ul>
			  <li>
				<dl>
				  <dt>Recipient Hosts/Domains by message count, including:</dt>
				  <dd>
					<ul>
					  <li>Number of messages sent to recipient host/domain</li>
					  <li>Number of bytes in messages</li>
					  <li>Number of defers</li>
					  <li>Average delivery delay</li>
					  <li>Maximum delivery delay</li>
					</ul>
				  </dd>
				</dl>
			  </li>
			  <li>Sending Hosts/Domains by message and byte count</li>
			  <li>Optional Hosts/Domains SMTPD connection summary</li>
			  <li>Senders by message count</li>
			  <li>Recipients by message count</li>
			  <li>Senders by message size</li>
			  <li>Recipients by message size with an option to limit these reports to the top nn.</li>
			</ul>
		  </dd>
		</dl>
	  </li>
	  <li>
		<dl>
		  <dt>A Semi-Detailed Summary of:</dt>
		  <dd>
			<ul>
			  <li>Messages deferred</li>
			  <li>Messages bounced</li>
			  <li>Messages rejected</li>
			</ul>
		  </dd>
		</dl>
	  </li>
	  <li>Summaries of warnings, fatal errors, and panics</li>
	  <li>Summary of master daemon messages</li>
	  <li>Optional detail of messages received, sorted by domain, then sender-in-domain, with a list of recipients-per-message.</li>
	  <li>Optional output of &ldquo;mailq&rdquo; run</li>
	</ul>
	<p>This list was taken from the http://jimsun.linxnet.com/postfix_contrib.html website, where you can read additional information about the output.</p>
	<h2>Scheduling Reports with Cron</h2>
	<p>Now you&rsquo;ll set up a Cron job to run the Pflogsumm Perl script and   send the mail server stats to you as a daily email. This is great for   monitoring your mail server. The example below schedules the email for   1:01 PM every day. For details on how to customize the time the email is   sent, you should read the https://www.linode.com/docs/linux-tools/utilities/cron article.</p>
	<ol>
	  <li>
		<p>Open the <strong>root</strong> user&rsquo;s Crontab by running the following command:</p>
		<div>
		  <table>
			<tbody>
			  <tr>
				<td><pre>1</pre></td>
				<td><pre>sudo crontab -e  </pre></td>
			  </tr>
			</tbody>
		  </table>
		</div>
	  </li>
	  <li>
		<p>00 01 * * * perl /tools/pflogsumm/pflogsumm.pl -e -d yesterday /var/log/maillog | mail -s 'Logwatch for Postfix' root.</p>
		<p>root&rsquo;s Crontab</p>
		<blockquote>
		  <p>If this is your first time using Cron, you will have to select your preferred text editor.</p>
		</blockquote>
	  </li>
	  <li>
		<p>Save the changes to your Cron file. For <strong>nano</strong>, this is Ctrl-x y.</p>
		<blockquote>
		  <p>Non-root users will not have permission to access the mail log.</p>
		</blockquote>
	  </li>
	</ol>
	<p>You will now receive daily emails with your Postfix mail server   stats. It&rsquo;s a great way to keep track of what your server is doing.</p>
	<h1>More Information</h1>
	<p>You may wish to consult the following resources for additional   information on this topic. While these are provided in the hope that   they will be useful, please note that we cannot vouch for the accuracy   or timeliness of externally hosted materials.</p>
	<ul>
	  <li>http://jimsun.linxnet.com/postfix_contrib.html</li>
	</ul>
	<ul>
	  <li>Detalhes do programa: http://linux.die.net/man/1/pflogsumm</li>
	</ul>
	<?php
	}//verifica se o programa existe
	?>
