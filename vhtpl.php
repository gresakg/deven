<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    ServerName <?php echo $domain; ?>

    DocumentRoot <?php echo $server_root; ?>
	
    <Directory <?php echo $server_root; ?>>
        Options Indexes FollowSymLinks
        AllowOverride All
	Require all granted
    </Directory>

    ErrorLog <?php echo $logpath; ?>/error.log

    # Possible values include: debug, info, notice, warn, error, crit,
    # alert, emerg.
    LogLevel notice

    CustomLog <?php echo $logpath; ?>/access.log combined

</VirtualHost>

