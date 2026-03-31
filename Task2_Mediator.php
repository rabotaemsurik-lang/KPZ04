<?php

/**
 * Інтерфейс Посередника
 */
interface Mediator {
    public function land(Aircraft $aircraft): void;
    public function takeOff(Aircraft $aircraft): void;
}

/**
 * Конкретний Посередник - Командний Центр
 */
class CommandCentre implements Mediator {
    private array $runways = [];
    private array $aircrafts = [];

    public function addRunway(Runway $runway) {
        $this->runways[] = $runway;
    }

    public function land(Aircraft $aircraft): void {
        echo "--- Запит на посадку для {$aircraft->getName()} ---\n";

        foreach ($this->runways as $runway) {
            if (!$runway->isBusy()) {
                $runway->setBusy(true);
                $runway->highLightRed();
                echo "Командний центр: Дозвіл надано. Смуга {$runway->getId()} зайнята літаком {$aircraft->getName()}.\n";
                $aircraft->setCurrentRunwayId($runway->getId());
                return;
            }
        }

        echo "Командний центр: Відмовлено! Всі смуги зайняті.\n";
    }

    public function takeOff(Aircraft $aircraft): void {
        $runwayId = $aircraft->getCurrentRunwayId();
        if ($runwayId === null) return;

        echo "--- Запит на зліт для {$aircraft->getName()} ---\n";

        foreach ($this->runways as $runway) {
            if ($runway->getId() === $runwayId) {
                $runway->setBusy(false);
                $runway->highLightGreen();
                $aircraft->setCurrentRunwayId(null);
                echo "Командний центр: Літак {$aircraft->getName()} злетів. Смуга {$runwayId} вільна.\n";
                return;
            }
        }
    }
}

/**
 * Клас Літака - тепер знає ТІЛЬКИ про Посередника
 */
class Aircraft {
    private string $name;
    private ?string $currentRunwayId = null;
    private Mediator $mediator;

    public function __construct(string $name, Mediator $mediator) {
        $this->name = $name;
        $this->mediator = $mediator;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCurrentRunwayId(): ?string {
        return $this->currentRunwayId;
    }

    public function setCurrentRunwayId(?string $id): void {
        $this->currentRunwayId = $id;
    }

    public function requestLanding(): void {
        $this->mediator->land($this);
    }

    public function requestTakeOff(): void {
        $this->mediator->takeOff($this);
    }
}

/**
 * Клас Злітної смуги - тепер знає ТІЛЬКИ про свої стани
 */
class Runway {
    private string $id;
    private bool $isBusy = false;

    public function __construct() {
        $this->id = uniqid("RWY-");
    }

    public function getId(): string {
        return $this->id;
    }

    public function isBusy(): bool {
        return $this->isBusy;
    }

    public function setBusy(bool $status): void {
        $this->isBusy = $status;
    }

    public function highLightRed(): void {
        echo "Смуга {$this->id}: Увімкнено ЧЕРВОНЕ світло (Зайнято).\n";
    }

    public function highLightGreen(): void {
        echo "Смуга {$this->id}: Увімкнено ЗЕЛЕНЕ світло (Вільно).\n";
    }
}

// --- Головний метод програми ---

$commandCentre = new CommandCentre();

// Створюємо смуги
$runway1 = new Runway();
$commandCentre->addRunway($runway1);

// Створюємо літаки
$boeing = new Aircraft("Boeing 747", $commandCentre);
$airbus = new Aircraft("Airbus A320", $commandCentre);

// Сценарій
$boeing->requestLanding();    // Успішно займає смугу
$airbus->requestLanding();    // Отримує відмову (смуга одна і вона зайнята)

echo "\n--- Час минає... ---\n\n";

$boeing->requestTakeOff();    // Звільняє смугу
$airbus->requestLanding();    // Тепер успішно приземляється