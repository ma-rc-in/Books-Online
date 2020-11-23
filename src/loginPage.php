<?php
/* The below code is used to call the connectTodatabase file so that this page can establish connection with the databse*/
require_once('databaseConnection.php');
$connectionDatabase = connectTodatabase();
?>

<?php
/*Setting variable destined to hold error message*/
$errorLogin ="";

/*The below code is used for validating the user. If the user will put empty login details, the system will throw an error.
However, the below code won't be trigerred because inputs used for email and password are using required property so the user won't be able
to submit them empty, but the code were wrote before the property was added and I left it here just in case*/
if (isset($_POST['login'])) {
  if (empty($_POST['email']) || empty($_POST['password'])) {
    $errorLogin = "Please enter your email address and password.";
  }
  else{}

    /*The below code retreives input from the input fields and then compares it with the login details with the data stored in the database*/
    $emailAddress = $_POST['email'];
    $password = $_POST['password'];
    $verifyUserDetails = "select * from members where email=:email";
    $verifyUserDetails = $connectionDatabase->prepare($verifyUserDetails);
    $verifyUserDetails->bindParam('email', $emailAddress, PDO::PARAM_STR);
    $verifyUserDetails->execute();
    $verifyUserDetailsProcess = $verifyUserDetails->fetchObject();
    $password1 = @$verifyUserDetailsProcess->password;
    if (password_verify($password, $password1)){
      session_start();
      $currentMemberID = $verifyUserDetailsProcess->memberID;
      $_SESSION["memberID"] = $currentMemberID;
      header('location:index.php');
    }
    else {
      $errorLogin = "** Login details are incorrect. Please try again **";
    }
  }
  ?>
  <!--Beggining of the website. Meta tag to inform about the data in this file, title tag of this page-->
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8" />
    <!--Link to the CSS file that provides the code for styles for this page-->
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="stylesBooksWebsite.css">
    <!--The below code helps different devices to recognise the page and set appropriate scaling of this page-->
    <meta name="viewport" content="width=device-width, initial-scale=1">

  </head>

  <body>

    <!--The below code put inside a tag is used to create the header with the logo and text inside so that the user can click on it and open the index.php page-->
    <a href="index.php">
      <div class="wrapperHeader">
        <span class="headerText">- Books</span>
        <span class="bookIcon"><img src="images/bookIcon.png" alt="bookIcon" width="64" height="64"></span>
        <span class="headerText"> Online -</span>
      </div>
    </a>
    <main>
      <!--The below code put inside form tag is used to process the login credentials. It can display an error message and two inputs for email and password, as well as the submit button-->

      <form class="formLogin" action="loginPage.php" method="post" style="margin-bottom: 140px;">
        <div class="labelsForm">Enter your email and password below: </div>
        <div class="labelsForm showError"><?php echo $errorLogin; ?></div>


        <input type="text" class="loginFormEmailText" name="email" id="email"  placeholder="Username" required />
        <input type="password" class="loginFormPasswordText" name="password" id="password" placeholder="Password" required>
        <input type="submit" class="loginFormSubmitButton" name="login" id="login" value="Submit">
      </form>

      <!--The  below footer is used to display some information across the website-->
      <footer style="margin-top: 0px; position: fixed;">
      </footer>
    </main>

    <!--The  below div is used to place the background image. It uses a class which is located at the CSS file.-->
    <div class="backgroundImage positionBackgrounds"></div>
    <!--The below div is used to create the black background that is put above the background picture to create a transparent background-->
    <div class="backgroundBlack positionBackgrounds"></div>
  </body>
  </html>
