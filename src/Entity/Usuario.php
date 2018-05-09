<?php
/**
 * PHP version 7.2
 * src\Entity\Usuario.php
 */

namespace TDW18\Usuarios\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * User
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name                 = "usuarios",
 *     uniqueConstraints    = {
 *          @ORM\UniqueConstraint(
 *              name="IDX_UNIQ_USER", columns={ "username" }
 *          ),
 *          @ORM\UniqueConstraint(
 *              name="IDX_UNIQ_EMAIL", columns={ "email" }
 *          )
 *      }
 *     )
 */
class Usuario implements \JsonSerializable
{
    /**
     * Id
     *
     * @var integer
     *
     * @ORM\Column(
     *     name     = "id",
     *     type     = "integer",
     *     nullable = false
     *     )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    private $id;

    /**
     * Username
     *
     * @var string
     *
     * @ORM\Column(
     *     name     = "username",
     *     type     = "string",
     *     length   = 32,
     *     nullable = false,
     *     unique   = true
     *     )
     */
    private $username;

    /**
     * Email
     *
     * @var string
     *
     * @ORM\Column(
     *     name     = "email",
     *     type     = "string",
     *     length   = 60,
     *     nullable = false,
     *     unique   = true
     *     )
     */
    private $email;

    /**
     * Enabled
     *
     * @var boolean
     *
     * @ORM\Column(
     *     name     = "enabled",
     *     type     = "boolean",
     *     nullable = false
     *     )
     */
    private $enabled;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     name="master",
     *     type="boolean",
     *     options={ "default" = false }
     * )
     */
    protected $isMaestro = false;

    /**
     * IsAdmin
     *
     * @var boolean
     *
     * @ORM\Column(
     *     name     = "admin",
     *     type     = "boolean",
     *     nullable = true,
     *     options  = { "default" = false }
     *     )
     */
    private $isAdmin;

    /**
     * @var Cuestion[] $cuestiones
     *
     * @ORM\OneToMany(
     *     targetEntity="Cuestion",
     *     mappedBy="creador"
     * )
     */
    protected $cuestiones;

    /**
     * Password
     *
     * @var string
     *
     * @ORM\Column(
     *     name     = "password",
     *     type     = "string",
     *     length   = 60,
     *     nullable = false
     *     )
     */
    private $password;

    /**
     * User constructor.
     *
     * @param string $username username
     * @param string $email email
     * @param string $password password
     * @param bool $enabled enabled
     * @param bool $isMaestro isMaestro
     * @param bool $isAdmin isAdmin
     */
    public function __construct(
        string $username = '',
        string $email = '',
        string $password = '',
        bool   $enabled = true,
        bool   $isMaestro = false,
        bool   $isAdmin = false
    ) {
        $this->id       = 0;
        $this->username = $username;
        $this->email    = $email;
        $this->setPassword($password);
        $this->enabled  = $enabled;
        $this->isMaestro = $isMaestro;
        $this->isAdmin  = $isAdmin;
        $this->cuestiones = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username username
     *
     * @return Usuario
     */
    public function setUsername(string $username): Usuario
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email email
     *
     * @return Usuario
     */
    public function setEmail(string $email): Usuario
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return boolean
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled enabled
     *
     * @return Usuario
     */
    public function setEnabled(bool $enabled): Usuario
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin isAdmin
     *
     * @return Usuario
     */
    public function setAdmin(bool $isAdmin): Usuario
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMaestro(): bool
    {
        return $this->isMaestro;
    }

    /**
     * @param bool $esMaestro
     * @return Usuario
     */
    public function setMaestro(bool $esMaestro): Usuario
    {
        $this->isMaestro = $esMaestro;
        return $this;
    }

    /**
     * Get password hash
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password password
     *
     * @return Usuario
     */
    public function setPassword(string $password): Usuario
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * Verifies that the given hash matches the user password.
     *
     * @param string $password password
     *
     * @return boolean
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * @return Collection
     */
    public function getCuestiones(): Collection
    {
        return $this->cuestiones;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString(): string
    {
        $id_cuestiones = $this->getCuestiones()->map(
            function (Cuestion $cuestion) {
                return $cuestion->getIdCuestion();
            }
        );
        $txt_cuestiones = $id_cuestiones
            ? '[' . implode(', ', $id_cuestiones->getValues()) . ']'
            : '[ ]';
        return '[ usuario ' .
            '(id=' . $this->getId() . ', ' .
            'username="' . $this->getUsername() . '", ' .
            'email="' . $this->getEmail() . '", ' .
            'enabled="' . ($this->isEnabled() ? '1' : '0') . '", ' .
            'isMaestro="' . ($this->isMaestro() ? '1' : '0') . '", ' .
            'isAdmin="' . ($this->isAdmin() ? '1' : '0') . '", ' .
            'cuestiones=' . $txt_cuestiones .
            ') ]';
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $id_cuestiones = $this->getCuestiones()->map(
            function (Cuestion $cuestion) {
                return $cuestion->getIdCuestion();
            }
        );
        return [
            'usuario' => [
                'id' => $this->getId(),
                'username' => $this->getUsername(),
                'email' => $this->getEmail(),
                'enabled' => $this->isEnabled(),
                'maestro' => $this->isMaestro(),
                'admin' => $this->isAdmin(),
                'cuestiones' => $id_cuestiones->getValues()
            ]
        ];
    }
}

/**
 * User definition
 *
 * @SWG\Definition(
 *     definition="User",
 *     required = { "id", "username", "email" },
 *     @SWG\Property(
 *          property    = "id",
 *          description = "User Id",
 *          type        = "integer",
 *          format      = "int32"
 *      ),
 *      @SWG\Property(
 *          property    = "username",
 *          description = "User name",
 *          type        = "string"
 *      ),
 *      @SWG\Property(
 *          property    = "email",
 *          description = "User email",
 *          type        = "string"
 *      ),
 *      @SWG\Property(
 *          property    = "enabled",
 *          description = "Denotes if user is enabled",
 *          type        = "boolean"
 *      ),
 *      @SWG\Property(
 *          property    = "isMaestro",
 *          description = "Denotes if user is Maestro",
 *          type        = "boolean"
 *      ),
 *      @SWG\Property(
 *          property    = "isAdmin",
 *          description = "Denotes if user has admin privileges",
 *          type        = "boolean"
 *      ),
 *      example = {
 *          "usuario" = {
 *              "id"       = 1508,
 *              "username" = "User name",
 *              "email"    = "User email",
 *              "enabled"  = true,
 *              "maestro"  = false,
 *              "admin"    = false
 *          }
 *     }
 * )
 *
 * @SWG\Parameter(
 *      name        = "userId",
 *      in          = "path",
 *      description = "ID of user",
 *      required    = true,
 *      type        = "integer",
 *      format      = "int32"
 * )
 */

/**
 * User data definition
 *
 * @SWG\Definition(
 *      definition = "UserData",
 *      @SWG\Property(
 *          property    = "username",
 *          description = "User name",
 *          type        = "string"
 *      ),
 *      @SWG\Property(
 *          property    = "email",
 *          description = "User email",
 *          type        = "string"
 *      ),
 *      @SWG\Property(
 *          property    = "password",
 *          description = "User password",
 *          type        = "string",
 *          format      = "password"
 *      ),
 *      @SWG\Property(
 *          property    = "enabled",
 *          description = "Denotes if user is enabled",
 *          type        = "boolean"
 *      ),
 *      @SWG\Property(
 *          property    = "isMaestro",
 *          description = "Denotes if user is Maestro",
 *          type        = "boolean"
 *      ),
 *      @SWG\Property(
 *          property    = "isAdmin",
 *          description = "Denotes if user has admin privileges",
 *          type        = "boolean"
 *      ),
 *      example = {
 *          "username"  = "User_name",
 *          "email"     = "User_email@example.com",
 *          "password"  = "User_password",
 *          "enabled"   = true,
 *          "isMaestro" = false,
 *          "isAdmin"   = false
 *      }
 * )
 */

/**
 * User array definition
 *
 * @SWG\Definition(
 *     definition = "UsersArray",
 *      @SWG\Property(
 *          property    = "usuarios",
 *          description = "Users array",
 *          type        = "array",
 *          items       = {
 *              "$ref": "#/definitions/User"
 *          }
 *      )
 * )
 */
