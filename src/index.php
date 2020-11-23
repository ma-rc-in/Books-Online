<?php
/* The below code is used to call the connectTodatabase file so that this page can establish connection with the databse*/
require_once('databaseConnection.php');
$connectionDatabase = connectTodatabase();
?>

<?php
/*The below code is used for login system. If the current session's id is equal to the memberID retreived from the database, the user will be to the home page and save books, etc.*/
session_start();
if (isset($_SESSION["memberID"])) {
    $currentMemberID = $_SESSION["memberID"];
} else {
}


/* The below code is used for search function using search bar. It looks for the name "searchBarButton" so that rest of the code can be trigerred*/
$booksListXML = simplexml_load_file('booklist.xml');
if (isset($_POST['searchBarButton'])){
    $searchBarButton = $_POST['searchBar'];
    echo $searchBarButton;
    $queryBooksList = "channel/item[title = '$searchBarButton' or author = '$searchBarButton']";
} else {
    $queryBooksList = 'channel/item';
}

/* The below code is used for displaying the books from the "bookslist.xml" file. It looks for the name "bookTableSubmit" to be able to trigger rest of the code*/

$booksListMethod = $booksListXML->xpath($queryBooksList);
$booksListXML = simplexml_load_file('booklist.xml');
$queryBooksList = 'channel/item';

if (isset($_POST['bookTableSubmit'])){
    $bookTableSubmit = $_POST['bookTableSubmit'];
    $queryBooksList = "channel/item[bookid = '$bookTableSubmit']";
    $booksListMethod = $booksListXML->xpath($queryBooksList);
    foreach($booksListMethod as $currentBook){
        $dateSaved = new DateTime('now');
        $dateSaved = $dateSaved->format("Y-m-d H:i:s");
        $qry = "INSERT INTO saved_books (bookID, memberID, bookTitle, author, description, link, yearPublished, dateSaved)
        VALUES (:bookID, :memberID, :bookTitle, :author, :description, :link, :yearPublished, :dateSaved)";
        $stmt = $connectionDatabase->prepare($qry);
        $stmt->bindParam('bookID', $currentBook->bookid, PDO::PARAM_STR);
        $stmt->bindParam('memberID', $currentMemberID, PDO::PARAM_STR);
        $stmt->bindParam('bookTitle', $currentBook->title, PDO::PARAM_STR);
        $stmt->bindParam('author', $currentBook->author, PDO::PARAM_STR);
        $stmt->bindParam('description', $currentBook->description, PDO::PARAM_STR);
        $stmt->bindParam('link', $currentBook->link, PDO::PARAM_STR);
        $stmt->bindParam('yearPublished', $currentBook->yearPublished, PDO::PARAM_STR);
        $stmt->bindParam('dateSaved', $dateSaved, PDO::PARAM_STR);
        $stmt->execute();
        header('location:index.php');
    }
}
?>


<!--Beggining of the website. Meta tag to inform about the data in this file, title tag of this page-->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>Home Page</title>
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
      <span class="bookIcon"><img src="images/bookIcon.png" alt=" " width="64" height="64"></span>
      <span class="headerText"> Online -</span>
  </div>
  </a>

<!-- The below code is used to display certain elements of the page depending on the user's memberID. If the user is logged in, the page will display elements appropriate for logged in users, such as saved books page.-->
<?php
if (isset($_SESSION['memberID'])) {
    echo '<nav><a href="index.php"><div class="buttonsNav">Home</div></a><a href="booksListPage.php"><div class="buttonsNav">My books</div></a><a href="logoutPage.php"><div class="buttonsNav">Logout</div></a></nav>';
}
  else{
     echo '<nav><a href="index.php"><div class="buttonsNav buttonsNavLogout">Home</div></a><a href="loginPage.php"><div class="buttonsNav buttonsNavLogout">Login</div></a></div></nav>';}
?>
  <main>
<!--The below php code embeded in form tag is used for searching books using the search function-->
    <form class="searchForm" method="post">
      <input class="searchBar" name="searchBar" placeholder="Please enter your text here" />
      <input class="searchBarButton" name="searchBarButton" type="submit" value="Search"/>
      <br>
    </form>

<!--The below php code embeded in form tag is the continuation of the search function. It displays the data retreived from the "booklist.xml" file-->
  <form id="addBookForm" method="post">
    <?php
    foreach($booksListMethod as $currentBook){
      if (isset($_SESSION['memberID'])) {
      echo '<div class="wrapperBooksContent">';

      echo '<div class="bookTitle bookEntity">';
      echo  "<li class='bookTitleText'>$currentBook->title</li>";
      echo  "<li class='bookTitleText'>$currentBook->author</li>";
      echo  "<li class='bookTitleText'>$currentBook->yearPublished</li>";
      echo  '</div>';

      echo '<div class="bookDescription bookEntity">';
      echo    "<p class='bookEntityText'>$currentBook->description</p>";
      echo  '</div>';

      echo  '<div class="bookView bookEntity">';
      echo    "<a class='viewBook' href='$currentBook->link;' target='_blank'>View on <br> Amazon</a><br />";;
      echo    "<button class='addToBookList' name='bookTableSubmit' type='submit' value='$currentBook->bookid'/>Do you want to add this book to your book list?</button>";
      echo  '</div>';
    }
    else {

      /* The below code is used for displaying content available for unlogged users*/
      echo '<div class="wrapperBooksContent">';

      echo '<div class="bookTitle bookEntity">';
      echo  "<li class='bookTitleText'>$currentBook->title</li>";
      echo  "<li class='bookTitleText'>$currentBook->author</li>";
      echo  "<li class='bookTitleText'>$currentBook->yearPublished</li>";
      echo  '</div>';

      echo '<div class="bookDescription bookEntity">';
      echo    "<p class='bookEntityText'>$currentBook->description</p>";
      echo  '</div>';

      echo  '<div class="bookView bookEntity" style="margin-top: 110px;">';
      echo    "<a class='viewBook' href='$currentBook->link;' target='_blank'>View on <br> Amazon</a><br />";;
      echo  '</div>';
    }
    }
    ?>
    </form>

    <!--The  below footer is used to display some information across the website-->
  <footer>
  </footer>
</div>
</main>

<div class="backgroundImage positionBackgrounds"></div>
<div class="backgroundBlack positionBackgrounds"></div>
</body>
<a href="#scrollUpPoint" title="scrollUp" class="scrollUpButton"><img width="64px" height="64px" src="images/scrollUpIcon.png" alt="scrollUp"/></a>
</html>
