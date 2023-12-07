<?php

namespace NW\WebService\References\Roles;

class CounterAgent
{
    public const TYPE_CUSTOMER = 0;

    /** @var int */
    protected $id;

    /** @var int */
    protected $type;

    /** @var string|null */
    protected $name;

    /** @var string|null */
    protected $email;

    /** @var string|null */
    protected $mobile;

    /** @var Seller */
    protected $seller;

    /**
     * @param int $resellerId
     * @return self|null
     */
    public static function getById(int $resellerId): self|null
    {
        return new self($resellerId); // fakes the getById method
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->getName() . ' ' . $this->getId();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return void
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return void
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    /**
     * @param string|null $mobile
     * @return void
     */
    public function setMobile(?string $mobile): void
    {
        $this->mobile = $mobile;
    }

    /**
     * @return Seller
     */
    public function getSeller(): Seller
    {
        return $this->seller;
    }

    /**
     * @param Seller $seller
     * @return void
     */
    public function setSeller(Seller $seller): void
    {
        $this->seller = $seller;
    }

}