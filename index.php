<?php
	error_reporting(E_ALL ^ (E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_DEPRECATED));
	
	include("Net/SFTP.php");
	
	$main = array("	<form action = '' method = 'post'>
						<table>
							<tr>
								<td>
									<font face = 'Arial'>
										Raspberry Pi IP:
									</font>
								</td>
							
								<td>
									<input type = 'text' name = 'ip' value = '".$_POST["ip"]."'>
								</td>
							</tr>
							
							<tr>
								<td>
									<font face = 'Arial'>
										Username:
									</font>
								</td>
								
								<td>
									<input type = 'text' name = 'user' value = '".$_POST["user"]."'>
								</td>
							</tr>
							
							<tr>
								<td>
									<font face = 'Arial'>
										Password:
									</font>
								</td>
								
								<td>
									<input type = 'password' name = 'pass' value = '".$_POST["pass"]."'>
								</td>
							</tr>
							
							<tr>
								<table>
									<tr>
										<td>
											<input style = 'width: 100px' type = 'submit' name = 'connect' value = 'CONNECT'>
										</td>
								
										<td>
											<input style = 'width: 100px' type = 'reset' name = 'reset' value = 'RESET'>
										</td>
									</tr>
								</table>
							</tr>
						</table>
					</form>",
					
			"	<center>							
					<form action = '' method = 'post'>
						<table>
							<tr>
								<td>
									<input style = 'width: 100px' type = 'submit' name = 'photo' value = 'TAKE PHOTO'>
								</td>
							
								<td>
									<button style = 'width: 100px' onClick = 'image.innerHTML = ".'"<img width = 800 height = 480>"'."'> 
										RESET 
									</button>
								</td>
								
								<td>
									<input style = 'width = 100px' type = 'submit' name = 'disconnect' value = 'DISCONNECT'>
								</td>
							</tr>
						</table>
					</form>
					
					<fieldset style = 'width: 800px; height: 477px;'>
						<div id = 'image'>
							<img width = 800 height = 480>
						</div>
					</fieldset>");
	
	print("	<html>
				<title>
					PiCamera()
				</title>
				
				<head>
					<link rel = 'icon' href = 'icon.png'>
				</head>
				
				<body>
					<div id = 'main'>
						".$main[(int)($_COOKIE["c"])]."
					</div>
				</body>
			</html>");
			
	if(isset($_POST["connect"]))
	{
		$error = "";
		
		if(strcmp($_POST["ip"], "") == 0)
		{
			$error .= "IP Address cannot be blank!".'\n';
		}
		
		if(strcmp($_POST["user"], "") == 0)
		{
			$error .= "Username cannot be blank!".'\n';
		}
		
		if(strcmp($_POST["pass"], "") == 0)
		{
			$error .= "Password cannot be blank!".'\n';
		}
		
		if(strcmp($error, "") == 0)
		{
			$ssh = new Net_SFTP($_POST["ip"]);
			
			if(!$ssh->login($_POST["user"], $_POST["pass"]))
			{
				print("	<script>							
							alert('Fatal Error! Could not connect to Raspberry Pi!');
							
							document.cookie = 'c=0; expires=Fri, 31 Dec 2100 23:59:59 UTC';
							document.cookie = 'ip=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
							document.cookie = 'user=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
							document.cookie = 'pass=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
							
							location.href = 'index.php';
						</script>");
			}
			
			else
			{
				$ssh->put("/home/pi/Desktop/camera.py", "camera.py", NET_SFTP_LOCAL_FILE);
				
				print("	<script>
							document.cookie = 'c=1; expires=Fri, 31 Dec 2100 23:59:59 UTC';
							document.cookie = 'ip=".$_POST["ip"]."; expires=Fri, 31 Dec 2100 23:59:59 UTC';
							document.cookie = 'user=".$_POST["user"]."; expires=Fri, 31 Dec 2100 23:59:59 UTC';
							document.cookie = 'pass=".$_POST["pass"]."; expires=Fri, 31 Dec 2100 23:59:59 UTC';
							
							alert('Connected to Raspberry Pi!');
							
							location.href = 'index.php';
						</script>");
			}
		}
		
		else
		{
			print("	<script>
						alert('".$error."');
					</script>");
		}
	}
	
			
	if(isset($_POST["photo"]))
	{	
		$ssh = new Net_SFTP($_COOKIE["ip"]);
		
		if(!$ssh->login($_COOKIE["user"], $_COOKIE["pass"]))
		{
			print("	<script>
						alert('Error! Could not retrieve photo from Raspberry!');
						
						document.cookie = 'c=0; expires=Fri, 31 Dec 2100 23:59:59 UTC';
						document.cookie = 'ip=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
						document.cookie = 'user=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
						document.cookie = 'pass=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
						
						location.href = 'index.php';
					</script>");
		}
		
		else
		{
			$cmd = $ssh->exec("sudo python /home/pi/Desktop/camera.py");
			
			$ssh->get("/home/pi/Desktop/test.jpg", "test.jpg");	
				
			print("	<script>
						image.innerHTML = '<img src = ".'"test.jpg"'." width = 800 height = 480>';
					</script>");	
		}
	}	
	
	if(isset($_POST["disconnect"]))
	{		
		print("	<script>
					alert('Disconnected from Raspberry Pi!');
					
					document.cookie = 'c=0; expires=Fri, 31 Dec 2100 23:59:59 UTC';
					document.cookie = 'ip=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
					document.cookie = 'user=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
					document.cookie = 'pass=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
						
					location.href = 'index.php';
				</script>");
	}
?>