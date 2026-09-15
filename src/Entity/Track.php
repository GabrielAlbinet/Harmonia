<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
class Track
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column]
    private ?int $trackNumber = null;

    #[ORM\Column]
    private ?int $playCount = null;

    #[ORM\Column]
    private ?bool $explicit = null;

    #[ORM\ManyToOne(inversedBy: 'tracks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Album $album = null;

    /**
     * @var Collection<int, Genre>
     */
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'tracks')]
    private Collection $genres;

    /**
     * @var Collection<int, Playlist>
     */
    #[ORM\ManyToMany(targetEntity: Playlist::class, mappedBy: 'tracks')]
    private Collection $playlists;

    /**
     * @var Collection<int, Favorite>
     */
    #[ORM\OneToMany(targetEntity: Favorite::class, mappedBy: 'track')]
    private Collection $favoritedBy;

    /**
     * @var Collection<int, ListeningHistory>
     */
    #[ORM\OneToMany(targetEntity: ListeningHistory::class, mappedBy: 'track')]
    private Collection $listeningHistories;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->favoritedBy = new ArrayCollection();
        $this->listeningHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getTrackNumber(): ?int
    {
        return $this->trackNumber;
    }

    public function setTrackNumber(int $trackNumber): static
    {
        $this->trackNumber = $trackNumber;

        return $this;
    }

    public function getPlayCount(): ?int
    {
        return $this->playCount;
    }

    public function setPlayCount(int $playCount): static
    {
        $this->playCount = $playCount;

        return $this;
    }

    public function isExplicit(): ?bool
    {
        return $this->explicit;
    }

    public function setExplicit(bool $explicit): static
    {
        $this->explicit = $explicit;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): static
    {
        $this->album = $album;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): static
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
            $playlist->addTrack($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): static
    {
        if ($this->playlists->removeElement($playlist)) {
            $playlist->removeTrack($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavoritedBy(): Collection
    {
        return $this->favoritedBy;
    }

    public function addFavoritedBy(Favorite $favoritedBy): static
    {
        if (!$this->favoritedBy->contains($favoritedBy)) {
            $this->favoritedBy->add($favoritedBy);
            $favoritedBy->setTrack($this);
        }

        return $this;
    }

    public function removeFavoritedBy(Favorite $favoritedBy): static
    {
        if ($this->favoritedBy->removeElement($favoritedBy)) {
            // set the owning side to null (unless already changed)
            if ($favoritedBy->getTrack() === $this) {
                $favoritedBy->setTrack(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ListeningHistory>
     */
    public function getListeningHistories(): Collection
    {
        return $this->listeningHistories;
    }

    public function addListeningHistory(ListeningHistory $listeningHistory): static
    {
        if (!$this->listeningHistories->contains($listeningHistory)) {
            $this->listeningHistories->add($listeningHistory);
            $listeningHistory->setTrack($this);
        }

        return $this;
    }

    public function removeListeningHistory(ListeningHistory $listeningHistory): static
    {
        if ($this->listeningHistories->removeElement($listeningHistory)) {
            // set the owning side to null (unless already changed)
            if ($listeningHistory->getTrack() === $this) {
                $listeningHistory->setTrack(null);
            }
        }

        return $this;
    }
}
