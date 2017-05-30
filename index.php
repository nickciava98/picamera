<?php
	error_reporting(E_ALL ^ (E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_DEPRECATED));
	
	include("Net/SFTP.php");
	
	$main = array("	<table cellspacing = '5'>
						<tr>
							<td>
								<h1>
									<font face = 'Arial'>
										Connection to Raspberry Pi
									</font>
								</h1>
							</td>
						</tr>
					</table>
					
					<form action = '' method = 'post'>
						<table cellspacing = '5'>
							<tr>
								<td>
									<font face = 'Arial'>
										<b>
											Raspberry Pi IP:
										</b>
									</font>
								</td>
							
								<td>
									<input type = 'text' name = 'ip' value = '".$_POST["ip"]."'>
								</td>
							</tr>
							
							<tr>
								<td>
									<font face = 'Arial'>
										<b>
											Username:
										</b>
									</font>
								</td>
								
								<td>
									<input type = 'text' name = 'user' value = '".$_POST["user"]."'>
								</td>
							</tr>
							
							<tr>
								<td>
									<font face = 'Arial'>
										<b>
											Password:
										</b>
									</font>
								</td>
								
								<td>
									<input type = 'password' name = 'pass' value = '".$_POST["pass"]."'>
								</td>
							</tr>
							
							<tr>
								<table cellspacing = '5'>
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
					</form>
					
					<table cellspacing = '5'>
						<tr>
							<td>
								<font face = 'Arial'>
									Copyright &copy 2017 Niccol&ograve Ciavarella. All rights reserved.
								</font>
							</td>
						</tr>
						
						<tr>							
							<td align = 'center'>
								<a title = 'GNU Affero General Public License v3' href = 'http://www.gnu.org/licenses/agpl-3.0.txt' target = '_blank'><img src = 'agplv3.png'></a>
							</td>
						</tr>
					</table>",
					
			"	<center>
					<table cellspacing = '5'>
						<tr>
							<td>
								<h1>
									<font face = 'Arial'>
										Take Photo from Raspberry Pi
									</font>
								</h1>
							</td>
						</tr>
					</table>
												
					<form action = '' method = 'post'>
						<table cellspacing = '5'>
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
					</fieldset>
					
					<br>
					
					<table cellspacing = '5'>
						<tr>
							<td>
								<font face = 'Arial'>
									Copyright &copy 2017 Niccol&ograve Ciavarella. All rights reserved.
								</font>
							</td>
						</tr>
						
						<tr>							
							<td align = 'center'>
								<a title = 'GNU Affero General Public License v3' href = 'http://www.gnu.org/licenses/agpl-3.0.txt' target = '_blank'><img src = 'agplv3.png'></a>
							</td>
						</tr>
					</table>
				</center>");
	
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
						alert('Error! Could not retrieve photo from Raspberry Pi!');
						
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
			
			$ssh->get("/home/pi/Desktop/test.jpg", "photo.jpg");	
				
			print("	<script>
						image.innerHTML = '<a title = ".'"photo.jpg"'." href = ".'"photo.jpg"'." target = ".'"_blank"'."><img src = ".'"photo.jpg"'." width = 800 height = 480></a>';
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