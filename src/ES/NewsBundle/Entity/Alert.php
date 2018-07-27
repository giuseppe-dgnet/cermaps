<?php

namespace ES\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alert
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ES\NewsBundle\Entity\AlertRepository")
 */
class Alert
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_name", type="string", length=20)
     */
    private $customer_name;

    /**
     * @var string
     *
     * @ORM\Column(name="page_url", type="string", length=250)
     */
    private $page_url;

    /**
     * @var string
     *
     * @ORM\Column(name="page_title", type="string", length=250)
     */
    private $page_title;

    /**
     * @var string
     *
     * @ORM\Column(name="page_content", type="text")
     */
    private $page_content;

    /**
     * @var string
     *
     * @ORM\Column(name="page_addons", type="text")
     */
    private $page_addons;

    /**
     * @var integer
     *
     * @ORM\Column(name="page_rank", type="integer")
     */
    private $page_rank;

    /**
     * @var integer
     *
     * @ORM\Column(name="critical_value", type="integer")
     */
    private $critical_value;

    /**
     * @var string
     *
     * @ORM\Column(name="source_type", type="string", length=30)
     */
    private $source_type;

    /**
     * @var string
     *
     * @ORM\Column(name="source_name", type="string", length=30)
     */
    private $source_name;

    /**
     * @var string
     *
     * @ORM\Column(name="content_language", type="string", length=2)
     */
    private $content_language;

    /**
     * @var string
     *
     * @ORM\Column(name="topic", type="string", length=30)
     */
    private $topic;

    /**
     * @var string
     *
     * @ORM\Column(name="subtopic", type="string", length=30)
     */
    private $subtopic;

    /**
     * @var string
     *
     * @ORM\Column(name="content_preview", type="string", length=150)
     */
    private $content_preview;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pubblication_date", type="datetime")
     */
    private $pubblication_date;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set customer_name
     *
     * @param string $customerName
     * @return Alert
     */
    public function setCustomerName($customerName)
    {
        $this->customer_name = $customerName;
    
        return $this;
    }

    /**
     * Get customer_name
     *
     * @return string 
     */
    public function getCustomerName()
    {
        return $this->customer_name;
    }

    /**
     * Set page_url
     *
     * @param string $pageUrl
     * @return Alert
     */
    public function setPageUrl($pageUrl)
    {
        $this->page_url = $pageUrl;
    
        return $this;
    }

    /**
     * Get page_url
     *
     * @return string 
     */
    public function getPageUrl()
    {
        return $this->page_url;
    }

    /**
     * Set page_title
     *
     * @param string $pageTitle
     * @return Alert
     */
    public function setPageTitle($pageTitle)
    {
        $this->page_title = $pageTitle;
    
        return $this;
    }

    /**
     * Get page_title
     *
     * @return string 
     */
    public function getPageTitle()
    {
        return $this->page_title;
    }

    /**
     * Set page_content
     *
     * @param string $pageContent
     * @return Alert
     */
    public function setPageContent($pageContent)
    {
        $this->page_content = $pageContent;
    
        return $this;
    }

    /**
     * Get page_content
     *
     * @return string 
     */
    public function getPageContent()
    {
        return $this->page_content;
    }

    /**
     * Set page_addons
     *
     * @param string $pageAddons
     * @return Alert
     */
    public function setPageAddons($pageAddons)
    {
        $this->page_addons = $pageAddons;
    
        return $this;
    }

    /**
     * Get page_addons
     *
     * @return string 
     */
    public function getPageAddons()
    {
        return $this->page_addons;
    }

    /**
     * Set page_rank
     *
     * @param integer $pageRank
     * @return Alert
     */
    public function setPageRank($pageRank)
    {
        $this->page_rank = $pageRank;
    
        return $this;
    }

    /**
     * Get page_rank
     *
     * @return integer 
     */
    public function getPageRank()
    {
        return $this->page_rank;
    }

    /**
     * Set critical_value
     *
     * @param integer $criticalValue
     * @return Alert
     */
    public function setCriticalValue($criticalValue)
    {
        $this->critical_value = $criticalValue;
    
        return $this;
    }

    /**
     * Get critical_value
     *
     * @return integer 
     */
    public function getCriticalValue()
    {
        return $this->critical_value;
    }

    /**
     * Set source_type
     *
     * @param string $sourceType
     * @return Alert
     */
    public function setSourceType($sourceType)
    {
        $this->source_type = $sourceType;
    
        return $this;
    }

    /**
     * Get source_type
     *
     * @return string 
     */
    public function getSourceType()
    {
        return $this->source_type;
    }

    /**
     * Set source_name
     *
     * @param string $sourceName
     * @return Alert
     */
    public function setSourceName($sourceName)
    {
        $this->source_name = $sourceName;
    
        return $this;
    }

    /**
     * Get source_name
     *
     * @return string 
     */
    public function getSourceName()
    {
        return $this->source_name;
    }

    /**
     * Set content_language
     *
     * @param string $contentLanguage
     * @return Alert
     */
    public function setContentLanguage($contentLanguage)
    {
        $this->content_language = $contentLanguage;
    
        return $this;
    }

    /**
     * Get content_language
     *
     * @return string 
     */
    public function getContentLanguage()
    {
        return $this->content_language;
    }

    /**
     * Set topic
     *
     * @param string $topic
     * @return Alert
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    
        return $this;
    }

    /**
     * Get topic
     *
     * @return string 
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set subtopic
     *
     * @param string $subtopic
     * @return Alert
     */
    public function setSubtopic($subtopic)
    {
        $this->subtopic = $subtopic;
    
        return $this;
    }

    /**
     * Get subtopic
     *
     * @return string 
     */
    public function getSubtopic()
    {
        return $this->subtopic;
    }

    /**
     * Set content_preview
     *
     * @param string $contentPreview
     * @return Alert
     */
    public function setContentPreview($contentPreview)
    {
        $this->content_preview = $contentPreview;
    
        return $this;
    }

    /**
     * Get content_preview
     *
     * @return string 
     */
    public function getContentPreview()
    {
        return $this->content_preview;
    }

    /**
     * Set pubblication_date
     *
     * @param \DateTime $pubblicationDate
     * @return Alert
     */
    public function setPubblicationDate($pubblicationDate)
    {
        $this->pubblication_date = $pubblicationDate;
    
        return $this;
    }

    /**
     * Get pubblication_date
     *
     * @return \DateTime 
     */
    public function getPubblicationDate()
    {
        return $this->pubblication_date;
    }
}
