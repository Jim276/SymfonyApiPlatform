<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ApiResource(
    normalizationContext:[
        'groups' => ['read_invoices']
    ],
    denormalizationContext:[
        'groups' => ['write_invoices']
    ],
    attributes: [
        "pagination_enabled" => true,
        "pagination_items_per_page" => 5
    ],
    collectionOperations:[
        'get_invoices' => [
            'method' => 'GET',
            'path' => '/liste-factures'
        ],
        'post_invoice' => [
            'method' => 'POST',
            'path' => '/liste-factures/add'
        ]
    ],
    itemOperations:[
        'get_invoice' => [
            'method' => 'GET',
            'path' => '/facture/{id}'
        ],
        'update_invoice' => [
            'method' => 'PUT',
            'path' => '/facture/{id}/update'
        ],
        'delete_invoice' => [
            'method' => 'DELETE',
            'path' => '/facture/{id}/delete'
        ]
        ],
        order:[
            "id" => "DESC"
        ]
)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'float')]
    #[Groups(['read_invoices'])]
    private $amount;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['read_invoices'])]
    private $sentAt;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read_invoices'])]
    private $status;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'invoices')]
    private $customer;

    #[ORM\Column(type: 'integer')]
    #[Groups(['read_invoices'])]
    private $chrono;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getChrono(): ?int
    {
        return $this->chrono;
    }

    public function setChrono(int $chrono): self
    {
        $this->chrono = $chrono;

        return $this;
    }
}
