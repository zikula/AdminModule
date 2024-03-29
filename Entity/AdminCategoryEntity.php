<?php

declare(strict_types=1);

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\AdminModule\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Bundle\CoreBundle\Doctrine\EntityAccess;

/**
 * AdminCategory entity class.
 *
 * @ORM\Entity(repositoryClass="Zikula\AdminModule\Entity\Repository\AdminCategoryRepository")
 * @ORM\Table(name="admin_category")
 */
class AdminCategoryEntity extends EntityAccess
{
    /**
     * The category id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $cid;

    /**
     * The category name
     *
     * @ORM\Column(type="string", length=32)
     * @Assert\Length(min="1", max="32")
     * @var string
     */
    private $name;

    /**
     * The category description
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\AtLeastOneOf(
     *     @Assert\Blank(),
     *     @Assert\Length(min="1", max="255")
     * )
     * @var string
     */
    private $description;

    /**
     * The category icon
     *
     * @ORM\Column(type="string", length=50)
     * @Assert\AtLeastOneOf(
     *     @Assert\Blank(),
     *     @Assert\Length(min="1", max="50")
     * )
     * @var string
     */
    private $icon;

    /**
     * The sort order of the category
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    private $sortorder;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->name = '';
        $this->description = '';
        $this->icon = '';
        $this->sortorder = 99;
    }

    public function getCid(): ?int
    {
        return $this->cid;
    }

    public function setCid(int $cid): self
    {
        $this->cid = $cid;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description ?? '';

        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon ?? '';

        return $this;
    }

    public function getSortorder(): int
    {
        return $this->sortorder;
    }

    public function setSortorder(int $sortorder): self
    {
        $this->sortorder = $sortorder;

        return $this;
    }
}
