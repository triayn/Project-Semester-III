<?php
require_once('web/koneksi.php');
require_once('web/authenticate.php');
$database = koneksi::getInstance();
$conn = $database->getConnection();
$userAuth = authenticate($_POST,[
    'uri'=>$_SERVER['REQUEST_URI'],
    'method'=>$_SERVER['REQUEST_METHOD']
],$conn);
if(!is_null($userAuth) && $userAuth['status'] == 'success'){
	$userAuth = $userAuth['data'];
    if(!in_array($userAuth['role'],['super admin','admin seniman','admin tempat','admin sewa','admin pentas'])){
        header('Location: /dashboard.php');
    }
}
$tPath = ($_SERVER['APP_ENV'] == 'local') ? '' : $_SERVER['APP_FOLDER'];
$csrf = $GLOBALS['csrf'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style></style>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disporabudpar - Nganjuk</title>
    <link rel="stylesheet" href="<?php echo $tPath; ?>/public/css/utama/login.css?">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="<?php echo $tPath; ?>/public/img/icon/utama/logo.png" rel="icon">
</head>
<body>
    <!-- <img class="wave" src="https://raw.githubusercontent.com/sefyudem/Responsive-Login-Form/master/img/wave.png"> -->
    <div class="container">
        <div class="img">
            <img style="width: 400px;" src="/public/img/icon/utama/login.svg">
        </div>
        <div class="login-content">
            <form action="web/login.php" method="post" class="form-login" id="loginForm">
                <h2>Selamat Datang!</h2>
                <div class="input-div one">
                    <div class="i">
                        <i class='bx bx-at'></i>
                    </div>
                    <div>
                        <h5>Email</h5>
                        <input type="email" name="email" id="inpEmail" class="input">
                        <!-- <input class="input" type="text"> -->
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class='bx bxs-lock'></i>
                    </div>
                    <div class="div">
                        <h5>Kata Sandi</h5>
                        <input type="password" name="password" id="inpPassword" class="input">
                        <!-- <input class="input" type="password"> -->
                    </div>
                </div>
                <input type="submit" class="btn" name="login" value="Masuk" id="submit">
                <!-- <input type="submit" class="btn" value="Masuk" name="login"> -->
                </div>
            </form>
        </div>
    </div>
    <div id="preloader" style="display: none;"></div>
    <div id="greenPopup" style="display:none"></div>
    <div id="redPopup" style="display:none"></div>
    <script> 
        const inputs = document.querySelectorAll(".input");
        function addcl(){
        	let parent = this.parentNode.parentNode;
            console.log('tambah focus');
        	parent.classList.add("focus");
        }
        function remcl(){
            console.log('tambah blur');
        	let parent = this.parentNode.parentNode;
        	if(this.value == ""){
        		parent.classList.remove("focus");
        	}
        }
        inputs.forEach(input => {
        	input.addEventListener("focus", addcl);
        	input.addEventListener("blur", remcl);
        });
</script>
</body>

</html>