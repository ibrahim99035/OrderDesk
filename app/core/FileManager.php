<?php

namespace App\Core;

class FileManager
{
    protected string $directory;
    protected array $allowedTypes = [];
    protected int $maxSize = 0;
    protected array $errors = [];

    public function __construct(string $directory = "public/uploads")
    {
        $this->directory = rtrim($directory,"/") . "/";

        if(!is_dir($this->directory)){
            mkdir($this->directory,0777,true);
        }
    }

    public function types(array $types): self
    {
        $this->allowedTypes = $types;
        return $this;
    }

    public function maxSize(int $size): self
    {
        $this->maxSize = $size;
        return $this;
    }

    public function upload(array $file)
    {
        if(!$this->validate($file)){
            return false;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $name = $this->randomName() . "." . $extension;

        $path = $this->directory . $name;

        if(!move_uploaded_file($file['tmp_name'],$path)){
            $this->errors[] = "Failed to move uploaded file";
            return false;
        }

        return $path ;
    }

    protected function validate(array $file): bool
    {
        if($file['error'] !== UPLOAD_ERR_OK){
            $this->errors[] = "Upload error code: " . $file['error'];
            return false;
        }

        if($this->maxSize && $file['size'] > $this->maxSize){
            $this->errors[] = "File too large";
            return false;
        }

        if(!empty($this->allowedTypes)){
            $mime = mime_content_type($file['tmp_name']);

            if(!in_array($mime,$this->allowedTypes)){
                $this->errors[] = "Invalid file type";
                return false;
            }
        }

        return true;
    }

    protected function randomName(): string
    {
        return bin2hex(random_bytes(16));
    }

    public function delete(string $path): bool
    {

        if(file_exists($path)){
            return unlink($path);
        }

        $this->errors[] = "File does not exist";
        return false;
    }

    public function exists(string $file): bool
    {
        return file_exists($this->directory . $file);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}