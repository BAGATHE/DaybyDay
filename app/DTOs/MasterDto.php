<?php

namespace App\DTOs;

class MasterDto
{
    private  $client_name;
    private  $lead_title;
    private  $type;
    private  $produit;
    private  $prix;
    private  $quantite;

    private const OFFERS = 'offers';
    private const INVOICES = 'invoices';


    public function __construct(
         $client_name,
         $lead_title,
         $type,
         $produit,
         $prix,
         $quantite
    ) {
        $this->setClientName($client_name);
        $this->setLeadTitle($lead_title);
        $this->setType($type);
        $this->setProduit($produit);
        $this->setPrix($prix);
        $this->setQuantite($quantite);
    }

    // --- Setters avec validation ---

    public function setClientName($client_name): void
    {
        if (empty($client_name)) {
            throw new \InvalidArgumentException("Le nom du client ne peut pas être vide.");
        }
        $this->client_name = $client_name;
    }

    public function setLeadTitle($lead_title): void
    {
        if (empty($lead_title)) {
            throw new \InvalidArgumentException("Le titre du lead ne peut pas être vide.");
        }
        $this->lead_title = $lead_title;
    }

    public function setType($type): void
    {
        if (empty($type)) {
            throw new \InvalidArgumentException("Le type ne peut pas être vide.");
        }

        if (!in_array($type, ['offers', 'invoice'], true)) {
            throw new \InvalidArgumentException("Ce type n'est pas valide : " . $type);
        }

        $this->type = $type;
    }

    public function setProduit($produit): void
    {
        if (empty($produit)) {
            throw new \InvalidArgumentException("Le produit ne peut pas être vide.");
        }
        $this->produit = $produit;
    }

    public function setPrix($prix): void
    {
        if (!preg_match('/^\d+(\.\d+)?$/', $prix) || floatval($prix) <= 0) {
            throw new \InvalidArgumentException("Le prix doit être un nombre positif et valide. => ".$prix);
        }
        $this->prix = floatval($prix);
    }


    public function setQuantite($quantite): void
    {
        $this->quantite = $quantite;
        if (!preg_match('/^\d+(\.\d+)?$/', $quantite) || floatval($quantite) <= 0) {
            throw new \InvalidArgumentException("La quantité doit être un nombre entier positif et valide. => ".$quantite);
        }
        $this->quantite = floatval($quantite);
    }

    // --- Getters ---

    public function getClientName(): string
    {
        return $this->client_name;
    }

    public function getLeadTitle(): string
    {
        return $this->lead_title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getProduit(): string
    {
        return $this->produit;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }
}
