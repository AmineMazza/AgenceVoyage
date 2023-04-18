<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ApiResource]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Offre $id_offre = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Commercial $id_commercial = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $id_user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_reservation = null;

    #[ORM\Column]
    private ?int $num_voyageurs = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $remarque = null;

    #[ORM\Column(nullable: true)]
    private ?float $Mnt_commission = null;

    #[ORM\Column(nullable: true)]
    private ?float $avance_commission = null;

    #[ORM\Column(nullable: true)]
    private ?float $date_avance_commission = null;

    #[ORM\OneToMany(mappedBy: 'id_reservation', targetEntity: Avance::class)]
    private Collection $avances;

    #[ORM\OneToMany(mappedBy: 'id_reservation', targetEntity: Voyageur::class)]
    private Collection $voyageurs;

    public function __construct()
    {
        $this->avances = new ArrayCollection();
        $this->voyageurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdOffre(): ?Offre
    {
        return $this->id_offre;
    }

    public function setIdOffre(?Offre $id_offre): self
    {
        $this->id_offre = $id_offre;

        return $this;
    }

    public function getIdCommercial(): ?Commercial
    {
        return $this->id_commercial;
    }

    public function setIdCommercial(?Commercial $id_commercial): self
    {
        $this->id_commercial = $id_commercial;

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

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->date_reservation;
    }

    public function setDateReservation(\DateTimeInterface $date_reservation): self
    {
        $this->date_reservation = $date_reservation;

        return $this;
    }

    public function getNumVoyageurs(): ?int
    {
        return $this->num_voyageurs;
    }

    public function setNumVoyageurs(int $num_voyageurs): self
    {
        $this->num_voyageurs = $num_voyageurs;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): self
    {
        $this->remarque = $remarque;

        return $this;
    }

    public function getMntCommission(): ?float
    {
        return $this->Mnt_commission;
    }

    public function setMntCommission(?float $Mnt_commission): self
    {
        $this->Mnt_commission = $Mnt_commission;

        return $this;
    }

    public function getAvanceCommission(): ?float
    {
        return $this->avance_commission;
    }

    public function setAvanceCommission(?float $avance_commission): self
    {
        $this->avance_commission = $avance_commission;

        return $this;
    }

    public function getDateAvanceCommission(): ?float
    {
        return $this->date_avance_commission;
    }

    public function setDateAvanceCommission(?float $date_avance_commission): self
    {
        $this->date_avance_commission = $date_avance_commission;

        return $this;
    }

    /**
     * @return Collection<int, Avance>
     */
    public function getAvances(): Collection
    {
        return $this->avances;
    }

    public function addAvance(Avance $avance): self
    {
        if (!$this->avances->contains($avance)) {
            $this->avances->add($avance);
            $avance->setIdReservation($this);
        }

        return $this;
    }

    public function removeAvance(Avance $avance): self
    {
        if ($this->avances->removeElement($avance)) {
            // set the owning side to null (unless already changed)
            if ($avance->getIdReservation() === $this) {
                $avance->setIdReservation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Voyageur>
     */
    public function getVoyageurs(): Collection
    {
        return $this->voyageurs;
    }

    public function addVoyageur(Voyageur $voyageur): self
    {
        if (!$this->voyageurs->contains($voyageur)) {
            $this->voyageurs->add($voyageur);
            $voyageur->setIdReservation($this);
        }

        return $this;
    }

    public function removeVoyageur(Voyageur $voyageur): self
    {
        if ($this->voyageurs->removeElement($voyageur)) {
            // set the owning side to null (unless already changed)
            if ($voyageur->getIdReservation() === $this) {
                $voyageur->setIdReservation(null);
            }
        }

        return $this;
    }
}
