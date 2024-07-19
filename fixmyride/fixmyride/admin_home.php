
<?php
// Start PHP session if needed
session_start();
?>
<style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            overflow-x: hidden;
            background-image: url('https://static.vecteezy.com/system/resources/previews/016/595/013/original/doodle-mechanic-seamless-pattern-background-vector.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            backdrop-filter: blur(3px);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #3a3a3a;
            color: white;
            position: relative;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header .left-section {
            display: flex;
            align-items: center;
        }
        .header .menu-icon {
            cursor: pointer;
            font-size: 30px;
            margin-right: 15px;
        }
        .header .menu-icon:hover {
            color: #bbbbbb;
        }
        .header .company-name {
            font-size: 24px;
            font-weight: bold;
        }
        .header h1 {
            margin: 0;
            flex-grow: 1;
            text-align: center;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .header .welcome-message {
            position: absolute;
            top: 20;
            left: 50%;
            transform: translateX(-50%);
            font-size: 24px;
            font-weight: bold;
        }
        .side-menu {
            width: 0;
            height: 100%;
            position: fixed;
            top: 0;
            left: 20px;
            background-color: #3a3a3a;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            color: white;
            box-shadow: 2px 0px 5px rgba(0,0,0,0.5);
        }
        .side-menu a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 20px;
            color: white;
            display: block;
            transition: 0.3s;
        }
        .side-menu a:hover {
            background-color: #575757;
        }
        .side-menu .close-btn {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 36px;
        }
        .side-menu .company-name {
            position: absolute;
            top: 20px;
            left: 25px;
            font-size: 24px;
            font-weight: bold;
            color: white;
        }
        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 60px);
            flex-direction: column;
        }
        .content button {
            margin: 20px;
            padding: 20px 40px;
            font-size: 24px;
            background-color: #e8491d;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 10px;
            transition: background-color 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
        }
        .content button:hover {
            background-color: #FF7518;
        }
        .content button i {
            margin-right: 10px;
            font-size: 30px;
        }
    </style>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
 
 <!-- Link to font-awesome for icons -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRn5gBS5dKY4f0/6i5aRZ4x63n5eP3+b5ePC6p59L" crossorigin="anonymous">
</head>
<body>
<div class="header">
<div class="left-section">
 <span class="menu-icon" onclick="openMenu()">&#9776;</span>
 <span class="company-name">FixMyRide</span>
</div>
<h1 class="welcome-message">WELCOME <?php echo isset($_SESSION['admin']) ? $_SESSION['admin'] : 'ADMIN'; ?></h1>
</div>

<div id="sideMenu" class="side-menu">
<span class="company-name">FixMyRide</span>
<a href="javascript:void(0)" class="close-btn" onclick="closeMenu()">&times;</a>
 <a href="#" onclick="Contactinfo()"><i class="fas fa-exclamation-triangle"></i> Contact Info: 9412889493 <br><br>Email: fixmyride@gmail.com</a>
 <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
 </div>

<div class="content">
 <button onclick="mechanicManagement()"><i class="fas fa-tools"></i> Mechanic Management</button>
 <button onclick="serviceRequests()"><i class="fas fa-clipboard-list"></i> Service Requests</button>
 </div>

<script>
 function openMenu() {
document.getElementById("sideMenu").style.width = "40%";
 }

function closeMenu() {
 document.getElementById("sideMenu").style.width = "0";
 }

function logout() {
 window.location.href = 'logout.php';
 }

 function sos() {
 window.location.href = 'sos.php';
 }

 function mechanicManagement() {
window.location.href = 'mechanic.php';
 }

 function serviceRequests() {
 window.location.href = 'service_requests.php';
 }


 </script>
</body>
</html>
