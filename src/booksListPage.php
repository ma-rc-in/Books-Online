<?php
/* The below code is used to call the connectTodatabase file so that this page can establish connection with the databse*/
require_once('databaseConnection.php');
$connectionDatabase = connectTodatabase();
?>
<?php

/*Starting the current session*/
session_start();

/*The below code is used for login system. If the current session's id is equal to the memberID retreived from the database, the user will be able to log in and move to the home page and save books, etc.*/
if (isset($_SESSION["memberID"])) {
  $currentMemberID = $_SESSION["memberID"];
} else {
  header('location:index.php');
}
/*The below code is used to delete a single books based on its bookID and memberID*/
$bookRemovedMessage="";
if (isset($_POST['removeBookFunction'])) {
  $bookID =  $_POST['removeBookFunction'];
  $stmt = $connectionDatabase->prepare("DELETE from saved_books where bookID=:bookID and memberID=:memberID");
  $stmt->bindParam('bookID', $bookID, PDO::PARAM_STR);
  $stmt->bindParam('memberID', $currentMemberID, PDO::PARAM_STR);
  $stmt->execute();
}

/*The below code is used to all books from this page and the database*/
if (isset($_POST['removeAllBooks'])) {
  $bookID =  $_POST['removeAllBooks'];
  $stmt = $connectionDatabase->prepare("DELETE from saved_books");
  $stmt->execute();
}

/*Attmpted export function. It looks for the "exportBooksFunction" call from the website so that the below code could be triggered.
if (isset($_POST['exportBooksFunction'])) {
$exportBooks = new SimpleXMLElement();
foreach()
{
}
}
print($exportBooks->asXML());
}
*/

?>
<!--Beggining of the website. Meta tag to inform about the data in this file, title tag of this page-->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>Books list Page</title>
  <!--Link to the CSS file that provides the code for styles for this page-->
  <link rel="stylesheet" type="text/css" href="stylesBooksWebsite.css">
  <!--The below code helps different devices to recognise the page and set appropriate scaling of this page-->
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<!--The below id within body tag is used to the scroll button so that the button knows where to scroll-->
<body id="scrollUpPoint">

  <!--The below code put inside a tag is used to create the header with the logo and text inside so that the user can click on it and open the index.php page-->
  <a href="index.php">
    <div class="wrapperHeader">
      <span class="headerText">- Books</span>
      <span class="bookIcon"><img src="images/bookIcon.png" alt="bookIcon" width="64" height="64"></span>
      <span class="headerText"> Online -</span>
    </div>
  </a>
  <!-- The below code is used to display certain elements of the page depending on the user's memberID. If the user is logged in, the page will display elements appropriate for logged in users, such as saved books page.-->
  <?php
  if (isset($_SESSION['memberID'])) {
    echo '<nav><a href="index.php"><div class="buttonsNav">Home</div></a><a href="booksListPage.php"><div class="buttonsNav">My books</div></a><a href="#"><div class="buttonsNav" name="exportBooksFunction">Export books</div></a></nav>';
  }
  else{
    echo '<a href="index.php"><div class="buttonsNav buttonsNavLogout">Home</div></a><a href="loginPage.php"><div class="buttonsNav buttonsNavLogout">Login</div></a>';}
    ?>
    <main>
      <!--The below php code embeded in form tag is used to retreive saved books from the database. Books are then displayed in divs and can be removed by using two buttons-->
      <form action="booksListPage.php" method="post">
        <?php
        $booksListQuery = "select * from saved_books where memberID=:memberID";
        $stmt = $connectionDatabase->prepare($booksListQuery);
        $stmt->bindParam('memberID', $currentMemberID, PDO::PARAM_STR);
        $stmt->execute();
        while ($row = $stmt->fetchObject())
        {
          $bookID = $row->bookID;
          $titleBook = $row->bookTitle;
          $authorBook = $row->author;
          $yearBook = $row->yearPublished;
          $descriptionBook = $row->description;
          $linkBook = $row->link;
          echo '<div class="wrapperBooksContent">';

          echo '<div class="bookTitle bookEntity">';
          echo  "<li class='bookTitleText'>$titleBook</li>";
          echo  "<li class='bookTitleText'>$authorBook</li>";
          echo  "<li class='bookTitleText'>$yearBook</li>";
          echo  '</div>';

          echo '<div class="bookDescription bookEntity">';
          echo    "<p class='bookEntityText'>$descriptionBook</p>";
          echo  '</div>';

          echo  '<div class="bookView bookEntity">';
          echo    "<a class='viewBook' href='$linkBook' target='_blank'>View on <br> Amazon</a><br/>";
          /* The below button can be used to delete single book*/
          echo    "<button class='removeBookButton' name='removeBookFunction' type='submit' value='$bookID'/>Remove this book</button>";
          /* The below button can be used to delete all books*/
          echo    "<button class='removeBookButton changeColourRemoveAll' name='removeAllBooks' type='submit' value='$bookID'/>Remove all books</button>";
          echo  '</div>';
        }
        ?>
      </form>

      <!--The  below footer is used to display some information across the website-->
      <footer style="position: fixed">
      </footer>
    </div>
  </main>

  <!--The  below div is used to place the background image. It uses a class which is located at the CSS file.-->
  <div class="backgroundImage positionBackgrounds"></div>
  <!--The below div is used to create the black background that is put above the background picture to create a transparent background-->
  <div class="backgroundBlack positionBackgrounds"></div>
</body>
<!--This picutre was retreived from here: https://www.flaticon.com/free-icon/up-arrow_892692?term=arrow%20up&page=1&position=3# and it is copyright free
It is embeded on the right side of the website to allow the user to scroll to the top of the page once this button is clicked-->
<a href="#scrollUpPoint" title="scrollUp" class="scrollUpButton"><img width="64px" height="64px" src="images/scrollUpIcon.png" alt="scrollUp"/></a>
</html>
