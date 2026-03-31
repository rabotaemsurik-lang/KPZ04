<?php


interface Mediator {
    public function requestLanding(Aircraft $aircraft): bool;
    public function notifyTakeoff(Aircraft $aircraft);
}

class CommandCentre implements Mediator {
    private $runwayFree = true;

    public function requestLanding(Aircraft $aircraft): bool {
        if ($this->runwayFree) {
            $this->runwayFree = false;
            echo "[Центр]: Смуга вільна для " . $aircraft->getName() . "\n";
            return true;
        }
        echo "[Центр]: Смуга зайнята! " . $aircraft->getName() . " зачекайте.\n";
        return false;
    }

    public function notifyTakeoff(Aircraft $aircraft) {
        $this->runwayFree = true;
        echo "[Центр]: Смуга звільнена літаком " . $aircraft->getName() . "\n";
    }
}

class Aircraft {
    private $name;
    private $mediator;

    public function __construct($name, Mediator $mediator) {
        $this->name = $name;
        $this->mediator = $mediator;
    }

    public function getName() { return $this->name; }

    public function land() {
        if ($this->mediator->requestLanding($this)) {
            echo "Літак " . $this->name . " успішно приземлився.\n";
        }
    }

    public function takeoff() {
        echo "Літак " . $this->name . " злітає.\n";
        $this->mediator->notifyTakeoff($this);
    }
}


$centre = new CommandCentre();
$plane1 = new Aircraft("Boeing 747", $centre);
$plane2 = new Aircraft("Airbus A320", $centre);

$plane1->land();
$plane2->land(); // Має отримати відмову
$plane1->takeoff();
$plane2->land(); // Тепер має вийти