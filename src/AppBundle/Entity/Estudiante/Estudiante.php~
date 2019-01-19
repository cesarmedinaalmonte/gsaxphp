<?php

namespace AppBundle\Entity\Estudiante;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Estudiante
 *
 * @ORM\Table(name="estudiante")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Estudiante\EstudianteRepository")
 */
class Estudiante
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=255)
     */
    private $apellido;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sexo", type="string", length=255, nullable=true)
     */
    private $sexo;

    /**
     * @var \DateTime|null
     * @Serializer\Type("DateTime<'m/d/Y'>")
     * @Serializer\SerializedName("fechaNacimiento")
     * @ORM\Column(name="fechaNacimiento", type="datetime", nullable=true)
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @var string|null
     *
     * @ORM\Column(name="padre", type="string", length=255, nullable=true)
     */
    private $padre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="madre", type="string", length=255, nullable=true)
     */
    private $madre;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return Estudiante
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido.
     *
     * @param string $apellido
     *
     * @return Estudiante
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido.
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set sexo.
     *
     * @param string|null $sexo
     *
     * @return Estudiante
     */
    public function setSexo($sexo = null)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo.
     *
     * @return string|null
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set fechaNacimiento.
     *
     * @param \DateTime|null $fechaNacimiento
     *
     * @return Estudiante
     */
    public function setFechaNacimiento($fechaNacimiento = null)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento.
     *
     * @return \DateTime|null
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set direccion.
     *
     * @param string $direccion
     *
     * @return Estudiante
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion.
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono.
     *
     * @param string|null $telefono
     *
     * @return Estudiante
     */
    public function setTelefono($telefono = null)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono.
     *
     * @return string|null
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set padre.
     *
     * @param string|null $padre
     *
     * @return Estudiante
     */
    public function setPadre($padre = null)
    {
        $this->padre = $padre;

        return $this;
    }

    /**
     * Get padre.
     *
     * @return string|null
     */
    public function getPadre()
    {
        return $this->padre;
    }

    /**
     * Set madre.
     *
     * @param string|null $madre
     *
     * @return Estudiante
     */
    public function setMadre($madre = null)
    {
        $this->madre = $madre;

        return $this;
    }

    /**
     * Get madre.
     *
     * @return string|null
     */
    public function getMadre()
    {
        return $this->madre;
    }
}
