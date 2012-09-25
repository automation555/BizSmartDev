<?php
    error_reporting(0);
    $error = array();
    define('DS', DIRECTORY_SEPARATOR);
    
    function rcopy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);

        while(false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . DS . $file)) {
                    rcopy($src . DS . $file, $dst . DS . $file);
                } else {
                    if (!copy($src . DS . $file, $dst . DS . $file)) {
                        return false;
                    }
                }
            }
        }

        closedir($dir);

        return true;
    }

    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);

            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . DS . $object) == "dir") {
                        rrmdir($dir . DS . $object);
                    } else {
                        unlink($dir .DS . $object);
                    }
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }    
    
    if (isset($_GET['download'])) {
        $old_mask = umask(0);

        function download_file($remote, $local) {
            if (extension_loaded('curl')) {
                $cp = curl_init($remote);
                $fp = fopen($local, "w+");

                if (!$fp) {
                    curl_close($cp);
                    return false;
                } else {
                    curl_setopt($cp, CURLOPT_FILE, $fp);
                    curl_exec($cp);
                    curl_close($cp);
                    fclose($fp);
                }
            } else {
                $url = parse_url($remote);
                $port = isset($url['port']) ? $url['port'] : 80;
                $port = $url['scheme'] == 'https' ? 443 : $port;
                $fp = fsockopen($url['host'], $port, $errno, $errstr, 15);

                if ($fp) {
                    $response = '';
                    $header = "GET {$url['path']} " . strtoupper($url['scheme']) . "/1.0\r\n";
                    $header .= "Host: {$url['host']}\r\n";
                    $header .= "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:8.0) Gecko/20100101 Firefox/8.0\r\n";
                    $header .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
                    $header .= "Accept-Language: es-es,es;q=0.8,en-us;q=0.5,en;q=0.3\r\n";
                    $header .= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n";
                    $header .= "Accept-Encoding: gzip, deflate\r\n";
                    $header .= "Keep-Alive: 300\r\n";
                    $header .= "Connection: keep-alive\r\n";
                    $header .= "Referer: {$url['scheme']}://{$url['host']}\r\n\r\n";

                    fputs($fp, $header);

                    while($line = fread($fp, 4096)){
                        $response .= $line;
                    }

                    fclose($fp); 

                    $pos = strpos($response, "\r\n\r\n"); 
                    $response = substr($response, $pos + 4);

                    $fp = fopen($local, "w+");

                    if (!$fp) {
                        return false;
                    } else {
                        fwrite($fp, $response);
                        fclose($fp);

                        return true;
                    }                    
                } else {
                    return false;
                }
            }

            return true;
        }
    
        // Call back is called for each file extracted and we need to make sure
        // the permissions are the same as the parent folder.
        function extract_callback($p_event, &$p_header) {
            $current_dir = dirname(__FILE__);
            $current_perms = substr(sprintf('%o', fileperms($current_dir)), -4);
            chmod($current_dir . DS . $p_header['filename'], octdec($current_perms));
            return 1;
        }

        if (download_file('https://nodeload.github.com/QuickAppsCMS/QuickApps-CMS/zipball/1.1', 'install.zip') && 
            download_file('http://www.quickappscms.org/files/installer/pclzip.txt', 'pclzip.php')
        ) {
            require('pclzip.php');

            rename('index.php', 'index.php.off');

            $archive = new PclZip('install.zip');

            if ($archive->extract(PCLZIP_CB_POST_EXTRACT, 'extract_callback')) {
                unlink('pclzip.php');
                unlink('install.zip');;
                unlink('index.php.off');

                $dh = opendir(realpath('.' . DS));

                while (($file = readdir($dh)) !== false) {
                    if (!in_array($file, array('.', '..')) && is_dir($file)) {
                        $gitFolder = $file;
                        break;
                    }
                }

                closedir($dh);

                rcopy(realpath("./{$gitFolder}/"), realpath('.'));
                rrmdir(realpath("./{$gitFolder}/"));
            } else {
                $error = array(0 => array('header' => 'Uh oh!', 'body' => "QuickApps was not able to unzip the files it needs. (" . $archive->errorInfo(true) . ")", 'refresh' => false));
            }
        } else {
            $error = array(0 => array('header' => 'Uh oh!', 'body' => "QuickApps was not able to download the files it needs. Make sure this directory is writable by the web server and then click \"Try again\".", 'refresh' => false));
        }
        
        umask($old_mask);
        
        if (count($error) == 0) {
            die('ok');
        } else {
?>
<div id="alert">
    <div class="error">
        <div class="tr"></div>
        <div class="content">
            <div class="fixed icon">
                <strong><?php echo $error[0]['header']; ?></strong>
            </div>
        </div>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>

<p><?php echo $error[0]['body']; ?></p>

<fieldset>
    <button id="the_button_error" class="primary_lg" onclick="go();">Try again</button>
</fieldset>
<?php
        exit;
        }
    } else {
		$error = array();
		function process_tests($tests) {
			global $error;
			foreach($tests as $test) {
				if ( !$test['test']) { 
					$error[] = array('header' => $test['header'], 'body' => $test['body']);
				}

			}
		}		
		
		$tests = array (
			0 => array (
					"header" => "PHP version 5.2.8 or higher",
					"test" => (version_compare(PHP_VERSION, '5.2.8', 'ge') ),
					"body" => "Quickapps requires a version of PHP greater than 5.2.8. Quickapps is not compatible at this time. Consult your host about upgrading your PHP installation."
			),
			1 => array (
					"header" => "MySQL enabled in PHP",
					"test" => (extension_loaded('mysql') || extension_loaded('mysqli')),
					"body" => "PHP must be installed with the MySQL extension loaded"
			),    				  				
			2 => array (
					"header" => "Safe mode disabled",
					"test" => (ini_get('safe_mode') == false || ini_get('safe_mode') == '' || strtolower(ini_get('safe_mode')) == 'off'),
					"body" => "<b>safe_mode</b> must be disabled in php.ini"
			),
			3 => array (
					"header" => "cURL or fsockopen enabled for server to server communication",
					"test" => (extension_loaded('curl') || function_exists('fsockopen')),
					"body" => "In order for QuickApps to communicate with the package server and download your installation's package, either the cURL library or the fsockopen PHP function must be enabled"
			),            
			4 => array (
					"header" => "Folder write permission",
					"test" => is_writable(dirname(__FILE__)),
					"body" => "Before installing, Quickapps needs to be able to write to this folder. Please change the permissions on this directory to allow the web server to write to it, then click \"Try again\"."
					)
			);		
		
		process_tests($tests);
		
		
    }
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Quickapps Installer</title>
<link rel="stylesheet" href="http://cms.quickapps.es/files/installer/style.css" type="text/css" />
<style type="text/css" media="screen">
    p.disclaimer {font-size:10px;padding:0 15px;}
    fieldset {text-align:right;}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js"></script>
<script type="text/javascript" charset="utf-8">
    
    function go() {
        var btn = $('the_button');
        btn.addClassName('progress');
        btn.style.opacity = 0.5;
        btn.innerHTML = 'Downloading...';
        dl();
    }
    
    function dl() {
        var myAjax = new Ajax.Updater('error', 'index.php?download', { 
            method: 'get',
            evalScripts: true,
            onComplete: function() {
                var val = $('error').innerHTML;
                if (val == 'ok') {
                    location.href = './';
                } else {
                    $('error').show();
                    $('welcome').hide();
                }
            }
        });
    }
</script>
</head>
<body id="login">
    
    <div id="dummy" style="display:none"></div>
    
    <div id="login-container">
        
        <div id="login-content" style="width:350px;">

            <div class="module">

                <div class="module-head">
                    <h3><img src="http://cms.quickapps.es/files/installer/img/logo.png" /></h3>
                </div>

                <div class="wrap">
                    
                    <div class="content">
                        
                        <div id="error"<?php if (count($error) == 0): ?> style="display:none"<?php endif; ?>>
                            <?php  if (count($error) > 0) {  ?>
                            <div id="alert">
                                <div class="error">
                                    <div class="tr"></div>
                                    <div class="content">
                                        <div class="fixed icon">
                                            <strong>Uh oh!</strong>
                                        </div>
                                    </div>
                                    <div class="bl"><div class="br"></div></div>
                                </div>
                            </div>
							<?php foreach( $error as $e): ?>
                            <p>- <?php echo $e['body']; ?></p><br/>
							<?php endforeach; ?>
                            <fieldset>
                                <button id="the_button_error" class="primary_lg" onclick="location.href='./';">Try again</button>
                            </fieldset>
                            
                            <?php } ?>
                        </div>
						
						
                        
                        <div id="welcome"<?php if (count($error) > 0): ?> style="display:none"<?php endif; ?>>
                            <p>Welcome to Quickapps! Before installing, Quickapps needs to download the files necessary for it to run on your server. To get started, click the button below.</p>
                        
                            <fieldset>
                                <button id="the_button" class="primary_lg" onclick="go();">Start installation</button>
                            </fieldset>
                        </div>
                    </div> <!--close content-->

                </div> <!--close module wrap-->

                <div class="module-footer">
                    <div>&nbsp;</div>
                </div>

            </div> <!--close module-->
            
        </div> <!--close login-content-->
        
    </div> <!--close login-container-->
    
    <img src="http://cms.quickapps.es/files/installer/img/bttn_primary_lg_spin.gif" style="display:none" />
    
</body>
</html>
