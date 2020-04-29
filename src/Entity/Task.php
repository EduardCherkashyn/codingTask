<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="task")
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $user_name;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $email;
    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $text;
    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $completed;
    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $edited;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * @param string $user_name
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return bool
     */
    public function isCompleted()
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    }

    /**
     * @return bool
     */
    public function isEdited()
    {
        return $this->edited;
    }

    /**
     * @param bool $edited
     */
    public function setEdited($edited)
    {
        $this->edited = $edited;
    }
}
