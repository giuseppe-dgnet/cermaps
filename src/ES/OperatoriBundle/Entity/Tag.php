<?php

namespace ES\OperatoriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ephp\TagBundle\Model\BaseRelation;

/**
 * Tag
 *
 * @ORM\Table(name="op_showroom_tags")
 * @ORM\Entity(repositoryClass="ES\OperatoriBundle\Entity\TagRepository")
 */
class Tag extends BaseRelation
{

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Showroom", inversedBy="tags",cascade={"persist","merge","detach"})
     * @ORM\JoinColumn(name="showroom_id", referencedColumnName="id") 
     */
    private $showroom;

    

    /**
     * Set profilo
     *
     * @param \ES\OperatoriBundle\Entity\Showroom $showroom
     * @return Tag
     */
    public function setShowroom(\ES\OperatoriBundle\Entity\Showroom $showroom = null)
    {
        $this->showroom = $showroom;
    
        return $this;
    }

    /**
     * Get profilo
     *
     * @return \ES\OperatoriBundle\Entity\Showroom 
     */
    public function getShowroom()
    {
        return $this->showroom;
    }
}