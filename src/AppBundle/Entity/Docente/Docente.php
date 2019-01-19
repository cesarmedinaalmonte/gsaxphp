<?php

namespace AppBundle\Entity\Docente;

use Doctrine\ORM\Mapping as ORM;

/**
 * Docente
 *
 * @ORM\Table(name="docente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Docente\DocenteRepository")
 */
class Docente
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
     * @var string|null
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
     * @return Docente
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
     * @return Docente
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
     * @return Docente
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
     * Set direccion.
     *
     * @param string|null $direccion
     *
     * @return Docente
     */
    public function setDireccion($direccion = null)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion.
     *
     * @return string|null
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
     * @return Docente
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
}
