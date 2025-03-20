<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic HTML CSS Login Form</title>
    <link rel="stylesheet" href="./css1/styles.css">
	
	<style>
	
	 
	
	</style>
</head> 
    <body>
	
	  <form method="post" action="register_traitement.php">
	  
        <div class="login">
            <div class="login-screen">
                <div class="app-title">
                    <h1>Welcome ok PHP</h1>
                </div>
    
                <div class="register-form">
                <div class="control-group">
                    <input type="text" class="login-field" value="" placeholder="Nom & Prenom" name="login_name" id="login_name">
                    <label class="login-field-icon fui-user" for="login_name"></label>
                    </div>
                    <div class="control-group">
                    <input type="text" class="login-field" value="" placeholder="Numero" name="login_number" id="login_number">
                    <label class="login-field-icon fui-user" for="login_number"></label>
                    </div>
    
                    <div class="control-group">
                    <input type="password" class="login-field" value="" placeholder="password" name="password_sp" id="signinSrPasswordExample2">
                    <label class="login-field-icon fui-lock" for="signinSrPasswordExample2"></label>
                    </div>
    
                    <!--<a class="btn btn-primary btn-large btn-block" href="#">Login</a>-->
					
					<button type="submit" class="btn btn-block btn-sm btn-primary transition-3d-hover">S'Inscrire</button>
					
                    <a class="login-link" href="login.php">Se connecter</a>
                </div>
				

            </div>
        </div>
		
		   </form>
    </body>
 

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



</html> 