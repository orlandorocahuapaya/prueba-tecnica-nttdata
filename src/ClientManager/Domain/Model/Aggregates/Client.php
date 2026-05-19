<?php

namespace Src\ClientManager\Domain\Model\Aggregates;

class Client implements \JsonSerializable
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $gender,
        public readonly array $location,
        public readonly ?string $email,
        public readonly ?string $birthDate,
        public readonly ?string $photo,
    ) {
    }

    public static function fromRandomUser(array $raw): self
    {
        return new self(
            name: trim(($raw['name']['first'] ?? '') . ' ' . ($raw['name']['last'] ?? '')),
            gender: $raw['gender'] ?? null,
            location: [
                'street' => $raw['location']['street']['name'] ?? null,
                'city' => $raw['location']['city'] ?? null,
                'state' => $raw['location']['state'] ?? null,
                'country' => $raw['location']['country'] ?? null,
            ],
            email: $raw['email'] ?? null,
            birthDate: isset($raw['dob']['date']) ? substr($raw['dob']['date'], 0, 10) : null,
            photo: $raw['picture']['large'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'gender' => $this->gender,
            'location' => $this->location,
            'email' => $this->email,
            'birth_date' => $this->birthDate,
            'photo' => $this->photo,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
