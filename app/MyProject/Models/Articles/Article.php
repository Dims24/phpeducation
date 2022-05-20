<?php
namespace MyProject\Models\Articles;
use MyProject\Models\Users\User;
class Article
{


    private mixed $title;
//    private $text;
//    private $author;

    public function __construct()
    {

        $this->title = $this->selectTitle();
//        $this->text = $text;
//        $this->author = $author;
    }

    public function getTitle(): mixed
    {
        $result = [
          "title" => $this->title
        ];
        return $result;
    }

    private function selectTitle()
    {
        $connection = new \Database\DatabaseConnection(
            database: "phptest",
            username: "dataphp",
            password: "1234"
        );
        $query = new \Database\QueryBuilder("articles", connection: $connection->getConnection());
        return $query->select("name")->first()[0];
    }
//    public function getText(): string
//    {
//        return $this->text;
//    }
//
//    public function getAuthor(): User
//    {
//        return $this->author;
//    }
}