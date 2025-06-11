<!DOCTYPE html>
<html lang="en">
<head>
    <style>
      /* simple animations to gently load elements into page */
      @keyframes fade-in {
        0% {opacity: 0%;}
        50% {opacity: 25%;}
        100% {opacity: 100%;}
      }
      .soft-load {
        animation: fade-in 1s ease-in 0s 1;
      }

      .slow-load {
        animation: fade-in 2s ease-in 0s 1;
      }

      .very-slow-load {
        animation: fade-in 3s ease-in 0s 1;
      }

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
</head>
<body>
    <?php

    //create a book class
    class Book {
      // Properties of book class
      public function __construct(
        public string $title,
        public string $author,
        public string $isbn,
        public bool $isAvailable = true
      ){}
    }

    //create a library class
    class Library {
      private array $books = [];
      
      //add a book to the library
      public function add_book(Book $book): void {
        $this->books[$book->isbn] = $book;
      }

      //remove a book from the library
      public function remove_book(Book $isbn): bool {
        if (isset($this->books[$isbn])) {
          unset($this->books[$isbn]);
          return true;
        }
        return false;
      }

      //function to search for a book by title or author
      public function search_books(string $query): array {
        return array_filter($this->books, function($book) use ($query){
          return stripos($book->title, $query) !== false || stripos($book->author, $query) !== false;
        });
      }

      //function to borrow a book
      public function borrow_book(string $isbn): bool {
        if (isset($this->books[$isbn]) && $this->books[$isbn]->isAvailable) {
          $this->books[$isbn]->isAvailable = false;
          return true;
        }
        return false;
      }


      // function to return a book 
      public function return_book(string $isbn): bool {
        if (isset($this->books[$isbn]) && !$this->books[$isbn]->isAvailable) {
          $this->books[$isbn]->isAvailable = true;
          return true;
        }
        return false;
      }

      //function to get all books
      public function get_all_books(): array{
        return $this->books;
      }

    } 


    // function to display a book's information
    function display_book(Book $book): void {
      echo "<ul class='soft-load'>";
      echo "<li>Title: {$book->title}</li>";
      echo "<li>Author: {$book->author}</li>";
      echo "<li>ISBN: {$book->isbn}</li>";
      echo "<li>Status: " . ($book->isAvailable ? "Available" : "Borrowed") . "</li>";
      echo "</ul>";

    }

    //function display all books in the library
    function display_library(Library $library): void {
      $books = $library->get_all_books();
      if (empty($books)) {
        echo "<br>The library is empty.<br>";
      }else {
        foreach ($books as $book) {
          display_book($book);
        }
      }
    }

    //Create a new library
    $library = new library();

    //Add some books to the library
    $library->add_book(new Book("Phobe", "Paula Gooder", "4455849120367"));
    $library->add_book(new Book("Redshirts", "John Scalzi", "4475849120389"));
    $library->add_book(new Book("The Science of Food", "Marty Jopson", "4465849120342"));

    //display updated library state
    echo "<br>Initial Library State: <br>";
    display_library($library);


    //search for a book
    $search_query = "Phobe";
    $search_results = $library->search_books($search_query);
    echo "<div class='slow-load'><br>Search results for $search_query:<br>";
    foreach($search_results as $book){
      display_book($book);
    }
    echo "</div>";

    //Borrow a book
    $borrow_isbn = "4475849120389";
    if ($library->borrow_book($borrow_isbn)) {
      echo "<div class='slow-load'><br>Book with ISBN $borrow_isbn has been borrowed.<br>";
    } else {
      echo "<div class='slow-load'><br>Failed to borrow book with ISBN $borrow_isbn.<br>";
    }
    echo"</div>";

    //Display new library state
    echo "<div class='very-slow-load'><br>Library after borrowing: <br>";
    display_library($library);
    

    //return a book
    $return_isbn = "4475849120389";
    if($library->return_book($return_isbn)){
      echo "<br>Book with ISBN $return_isbn has been returned.<br>";
    } else {
      echo "<br>Failed to return book with ISBN $return_isbn.<br>";
    }

    //Display final library state
    echo "<br>Final library state: <br>";
    display_library($library);
    echo "</div>";
    ?>
</body>
</html>