<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * The `Order` class represents an order entity in a PHP application.
 * It contains properties and methods related to an order, such as the order ID, user, creation date,
 * carrier information, delivery details, order details, payment status, and reference number.
 */
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id  = null;
    
    #[ORM\ManyToOne(inversedBy: 'myOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $myUser ;
    #[ORM\Column]
    private ?\DateTimeImmutable $createAt;

    #[ORM\Column(length: 255)]
    private ?string $carrierName ;

    #[ORM\Column]
    private ?float $carrierPrice ;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $delivery ;
    
    #[ORM\OneToMany(mappedBy: 'myOrder', targetEntity: OrderDetails::class)]
    private Collection $orderDetails;
    

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $reference  = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripeSessionId ;

    #[ORM\Column (length: 255, nullable: true)]
    private ?int $state = null;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }

    /**
     * Calculates and returns the total amount of the order based on the order details.
     *
     * @return float|null The total amount of the order.
     */
    public function getTotal(): ?float
    {
        $total = null;
        foreach ($this->getOrderDetails() as $orderDetail) {
            $total += $orderDetail->getPrice() * $orderDetail->getQuantity();
        }
        return $total;
    }

    /**
     * Returns the ID of the order.
     *
     * @return int The ID of the order.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns the user associated with the order.
     *
     * @return User The user associated with the order.
     */
    public function getMyUser(): ?User
    {
        return $this->myUser;
    }

    /**
     * Sets the user associated with the order.
     *
     * @param User| $myUser The user associated with the order.
     * @return $this
     */
    public function setMyUser(?User $myUser): self
    {
        $this->myUser = $myUser;
        return $this;
    }

    /**
     * Returns the creation date of the order.
     *
     * @return \DateTimeImmutable The creation date of the order.
     */
    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    /**
     * Sets the creation date of the order.
     *
     * @param \DateTimeImmutable $createAt The creation date of the order.
     * @return $this
     */
    public function setCreateAt(\DateTimeImmutable $createAt): self
    {
        $this->createAt = $createAt;
        return $this;
    }

    /**
     * Returns the carrier name for shipping.
     *
     * @return string The carrier name for shipping.
     */
    public function getCarrierName(): ?string
    {
        return $this->carrierName;
    }

    /**
     * Sets the carrier name for shipping.
     *
     * @param string $carrierName The carrier name for shipping.
     * @return $this
     */
    public function setCarrierName(string $carrierName): self
    {
        $this->carrierName = $carrierName;
        return $this;
    }

    /**
     * Returns the carrier price for shipping.
     *
     * @return float The carrier price for shipping.
     */
    public function getCarrierPrice(): ?float
    {
        return $this->carrierPrice;
    }

    /**
     * Sets the carrier price for shipping.
     *
     * @param float $carrierPrice The carrier price for shipping.
     * @return $this
     */
    public function setCarrierPrice(float $carrierPrice): self
    {
        $this->carrierPrice = $carrierPrice;
        return $this;
    }

    /**
     * Returns the delivery details of the order.
     *
     * @return string The delivery details of the order.
     */
    public function getDelivery(): ?string
    {
        return $this->delivery;
    }

    /**
     * Sets the delivery details of the order.
     *
     * @param string $delivery The delivery details of the order.
     * @return $this
     */
    public function setDelivery(string $delivery): self
    {
        $this->delivery = $delivery;
        return $this;
    }

    /**
     * Returns the collection of order details associated with the order.
     *
     * @return Collection The collection of order details associated with the order.
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    /**
     * Adds an order detail to the collection of order details.
     *
     * @param OrderDetails $orderDetail The order detail to add.
     * @return $this
     */
    public function addOrderDetail(OrderDetails $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->add($orderDetail);
            $orderDetail->setMyOrder($this);
        }
        return $this;
    }

    /**
     * Removes an order detail from the collection of order details.
     *
     * @param OrderDetails $orderDetail The order detail to remove.
     * @return $this
     */
    public function removeOrderDetail(OrderDetails $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            if ($orderDetail->getMyOrder() === $this) {
                $orderDetail->setMyOrder(null);
            }
        }
        return $this;
    }

    

   
    

    /**
     * Returns the reference number of the order.
     *
     * @return string The reference number of the order.
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * Sets the reference number of the order.
     *
     * @param string $reference The reference number of the order.
     * @return $this
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    public function getStripeSessionId(): ?string
    {
        return $this->stripeSessionId;
    }

    public function setStripeSessionId(?string $stripeSessionId): static
    {
        $this->stripeSessionId = $stripeSessionId;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): static
    {
        $this->state = $state;

        return $this;
    }
}
