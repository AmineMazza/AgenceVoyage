<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
#[ApiResource]
#[ApiFilter(SearchFilter::class, properties: ['id_user' => 'exact'])]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $date_depart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $date_retour = null;

    #[ORM\Column]
    private ?bool $baller_retour = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bhebergement = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bvisa = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bpetit_dejeuner = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bdemi_pension = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bpension_complete = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bvisite_medine = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_chambre = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_chambre_double = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_chambre_triple = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_chambre_quad = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_chambre_quint = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bcoup_coeur = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bpubier = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_publication = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin_publication = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bpassport = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bphotos = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bpass_vacinial = null;

    #[ORM\OneToMany(mappedBy: 'id_offre', targetEntity: Hotel::class, orphanRemoval: true)]
    private Collection $hotels;

    #[ORM\OneToMany(mappedBy: 'id_offre', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detail_voyage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detail_vols = null;

    #[ORM\ManyToOne(cascade: ["persist"], targetEntity:"App\Entity\User")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $id_user = null;

    #[ORM\ManyToOne(targetEntity:"App\Entity\Destination")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Destination $id_destination = null;

    #[ORM\OneToMany(mappedBy: 'id_offre', targetEntity: Reservation::class)]
    private Collection $reservations;

    public function __construct()
    {
        $this->hotels = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDateDepart(\DateTimeInterface $date_depart): self
    {
        $this->date_depart = $date_depart;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->date_retour;
    }

    public function setDateRetour(\DateTimeInterface $date_retour): self
    {
        $this->date_retour = $date_retour;

        return $this;
    }

    public function isBallerRetour(): ?bool
    {
        return $this->baller_retour;
    }

    public function setBallerRetour(bool $baller_retour): self
    {
        $this->baller_retour = $baller_retour;

        return $this;
    }

    public function isBhebergement(): ?bool
    {
        return $this->bhebergement;
    }

    public function setBhebergement(?bool $bhebergement): self
    {
        $this->bhebergement = $bhebergement;

        return $this;
    }

    public function isBvisa(): ?bool
    {
        return $this->bvisa;
    }

    public function setBvisa(?bool $bvisa): self
    {
        $this->bvisa = $bvisa;

        return $this;
    }

    public function isBpetitDejeuner(): ?bool
    {
        return $this->bpetit_dejeuner;
    }

    public function setBpetitDejeuner(?bool $bpetit_dejeuner): self
    {
        $this->bpetit_dejeuner = $bpetit_dejeuner;

        return $this;
    }

    public function isBdemiPension(): ?bool
    {
        return $this->bdemi_pension;
    }

    public function setBdemiPension(?bool $bdemi_pension): self
    {
        $this->bdemi_pension = $bdemi_pension;

        return $this;
    }

    public function isBpensionComplete(): ?bool
    {
        return $this->bpension_complete;
    }

    public function setBpensionComplete(?bool $bpension_complete): self
    {
        $this->bpension_complete = $bpension_complete;

        return $this;
    }

    public function isBvisiteMedine(): ?bool
    {
        return $this->bvisite_medine;
    }

    public function setBvisiteMedine(?bool $bvisite_medine): self
    {
        $this->bvisite_medine = $bvisite_medine;

        return $this;
    }

    public function getPrixChambre(): ?float
    {
        return $this->prix_chambre;
    }

    public function setPrixChambre(?float $prix_chambre): self
    {
        $this->prix_chambre = $prix_chambre;

        return $this;
    }

    public function getPrixChambreDouble(): ?float
    {
        return $this->prix_chambre_double;
    }

    public function setPrixChambreDouble(?float $prix_chambre_double): self
    {
        $this->prix_chambre_double = $prix_chambre_double;

        return $this;
    }

    public function getPrixChambreTriple(): ?float
    {
        return $this->prix_chambre_triple;
    }

    public function setPrixChambreTriple(?float $prix_chambre_triple): self
    {
        $this->prix_chambre_triple = $prix_chambre_triple;

        return $this;
    }

    public function getPrixChambreQuad(): ?float
    {
        return $this->prix_chambre_quad;
    }

    public function setPrixChambreQuad(?float $prix_chambre_quad): self
    {
        $this->prix_chambre_quad = $prix_chambre_quad;

        return $this;
    }

    public function getPrixChambreQuint(): ?float
    {
        return $this->prix_chambre_quint;
    }

    public function setPrixChambreQuint(?float $prix_chambre_quint): self
    {
        $this->prix_chambre_quint = $prix_chambre_quint;

        return $this;
    }

    public function isBcoupCoeur(): ?bool
    {
        return $this->bcoup_coeur;
    }

    public function setBcoupCoeur(?bool $bcoup_coeur): self
    {
        $this->bcoup_coeur = $bcoup_coeur;

        return $this;
    }

    public function isBpubier(): ?bool
    {
        return $this->bpubier;
    }

    public function setBpubier(?bool $bpubier): self
    {
        $this->bpubier = $bpubier;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(?\DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function getDateFinPublication(): ?\DateTimeInterface
    {
        return $this->date_fin_publication;
    }

    public function setDateFinPublication(?\DateTimeInterface $date_fin_publication): self
    {
        $this->date_fin_publication = $date_fin_publication;

        return $this;
    }

    public function isBpassport(): ?bool
    {
        return $this->bpassport;
    }

    public function setBpassport(?bool $bpassport): self
    {
        $this->bpassport = $bpassport;

        return $this;
    }

    public function isBphotos(): ?bool
    {
        return $this->bphotos;
    }

    public function setBphotos(?bool $bphotos): self
    {
        $this->bphotos = $bphotos;

        return $this;
    }

    public function isBpassVacinial(): ?bool
    {
        return $this->bpass_vacinial;
    }

    public function setBpassVacinial(?bool $bpass_vacinial): self
    {
        $this->bpass_vacinial = $bpass_vacinial;

        return $this;
    }

    /**
     * @return Collection<int, Hotel>
     */
    public function getHotels(): Collection
    {
        return $this->hotels;
    }

    public function addHotel(Hotel $hotel): self
    {
        if (!$this->hotels->contains($hotel)) {
            $this->hotels->add($hotel);
            $hotel->setIdOffre($this);
        }

        return $this;
    }

    public function removeHotel(Hotel $hotel): self
    {
        if ($this->hotels->removeElement($hotel)) {
            // set the owning side to null (unless already changed)
            if ($hotel->getIdOffre() === $this) {
                $hotel->setIdOffre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setIdOffre($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getIdOffre() === $this) {
                $message->setIdOffre(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDetailVoyage(): ?string
    {
        return $this->detail_voyage;
    }

    public function setDetailVoyage(?string $detail_voyage): self
    {
        $this->detail_voyage = $detail_voyage;

        return $this;
    }

    public function getDetailVols(): ?string
    {
        return $this->detail_vols;
    }

    public function setDetailVols(?string $detail_vols): self
    {
        $this->detail_vols = $detail_vols;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdDestination(): ?Destination
    {
        return $this->id_destination;
    }

    public function setIdDestination(?Destination $id_destination): self
    {
        $this->id_destination = $id_destination;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setIdOffre($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getIdOffre() === $this) {
                $reservation->setIdOffre(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->titre;
    }
}
