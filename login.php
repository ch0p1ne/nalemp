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
	
	  <form method="post" action="login_traitement.php">
	  
        <div class="login">
            <div class="login-screen">
                <div class="app-title">
                    <h1>Welcome ok PHP</h1>
                </div>
    
                <div class="register-form">
               
                    <div class="control-group">
                    <input type="text" class="login-field" value="" placeholder="Numero" name="login_number" id="login_number">
                    <label class="login-field-icon fui-user" for="login_number"></label>
                    </div>
    
                    <div class="control-group">
                    <input type="password" class="login-field" value="" placeholder="password" name="password_sp" id="signinSrPasswordExample2">
                    <label class="login-field-icon fui-lock" for="signinSrPasswordExample2"></label>
                    </div>
    
                    <!--<a class="btn btn-primary btn-large btn-block" href="#">Login</a>-->
					
					<button type="submit" class="btn btn-block btn-sm btn-primary transition-3d-hover">Se connecter</button>
					
                    <a class="login-link" href="register.php">S'inscrire</a>
                </div>
				

            </div>
        </div>
		
		   </form>
    </body>
 

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#register-form").on("submit", function(event) {
        event.preventDefault(); // Empêcher le rechargement de la page

        var formData = {
            login_name: $("#login_name").val(),
            login_number: $("#login_number").val(),
            password_sp: $("#password_sp").val()
        };

        $.ajax({
            url: "register_traitement.php", // Fichier PHP de traitement
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                console.log("Réponse serveur : ", response);
                if (response.status === "success") {
                    alert(response.message);
                    window.location.href = response.redirect; // Rediriger après inscription
                } else {
                    alert(response.message); // Afficher les erreurs
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur AJAX :", error);
                alert("Une erreur s'est produite lors de l'inscription.");
            }
        });
    });
});
</script>


</html> 