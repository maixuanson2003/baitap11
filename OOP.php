<?php

// Bài Tập 1: Xây dựng lớp quản lý người dùng
class User {
    private $name;
    private $email;
    private $password;

    public function __construct($name, $email, $password) {
        $this->name = $name;
        $this->email = $email;
        $this->setPassword($password);
    }

    public function __get($property) {
        return $this->$property ?? null;
    }

    public function __set($property, $value) {
        if ($property === 'password') {
            $this->setPassword($value);
        } else {
            $this->$property = $value;
        }
    }

    private function setPassword($password) {
        if (strlen($password) < 6) {
            throw new Exception("Password phai lon hon 6");
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function validatePassword($password) {
        return password_verify($password, $this->password);
    }

    public function __destruct() {
        echo "destruct is call.\n";
    }
}

// bai 2
abstract class Animal {
    abstract public function makeSound();
}

trait Movable {
    public function walk() {
        echo  " is walking.\n";
    }
    public function fly() {
        echo " is flying.\n";
    }
}

class Dog extends Animal {
    use Movable;
    public function makeSound() {
        echo "dog";
    }
}

class Cat extends Animal {
    use Movable;
    public function makeSound() {
        echo "cat";
    }
}

class Bird extends Animal {
    use Movable;
    public function makeSound() {
        echo "Brid";
    }
}

// bai 3
interface Borrowable {
    public function borrow();
    public function returnItem();
}

interface Reservable {
    public function reserve();
}

class LibraryItem {
    protected $title;
    protected $status = "available";

    public function __construct($title) {
        $this->title = $title;
    }
}

class Book extends LibraryItem implements Borrowable, Reservable {
    public function borrow() {
        if ($this->status !== "available") throw new Exception("Book is not available.");
        $this->status = "borrowed";
    }
    public function returnItem() { $this->status = "available"; }
    public function reserve() { $this->status = "reserved"; }
}


class FileHandler {
    private $filePath;
    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function __call($name, $arguments) {

        if ($name === 'readTxt') {
            $myfile = fopen($this->filePath, "r") or die("Unable to open file!");
            $content="";
            while(!feof($myfile)) {
                $content =fgets($myfile) . "<br>";
            }
            return  $content;
        } elseif ($name === 'writeTxt') {
            $myfile = fopen($this->filePath, "w") or die("Unable to open file!");
            fwrite($myfile, $arguments[0]);
            fclose($myfile);
        }

    }

    public function __invoke() {
        return $this->readTxt();
    }

    public function __toString() {
        return "FileHandler managing: " . $this->filePath;
    }
}
$fileHandler = new FileHandler("son.txt");
$fileHandler->writeTxt("Hello, this is a test!");
echo $fileHandler->readTxt();
echo $fileHandler();
echo $fileHandler;

