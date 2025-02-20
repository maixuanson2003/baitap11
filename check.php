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
            throw new Exception("Password must be at least 6 characters long.");
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function validatePassword($password) {
        return password_verify($password, $this->password);
    }

    public function __destruct() {
        echo "User {$this->name} is being destroyed.\n";
    }
}

// Bài Tập 2: Xây dựng hệ thống quản lý động vật
abstract class Animal {
    abstract public function makeSound();
}

trait Movable {
    public function walk() {
        echo get_called_class() . " is walking.\n";
    }
    public function fly() {
        echo get_called_class() . " is flying.\n";
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

// Bài Tập 3: Tạo ứng dụng quản lý thư viện
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
            return file_get_contents($this->filePath);
        } elseif ($name === 'writeTxt') {
            file_put_contents($this->filePath, $arguments[0]);
        }
    }

    public function __invoke() {
        return $this->readTxt();
    }

    public function __toString() {
        return "FileHandler managing: " . $this->filePath;
    }
}
