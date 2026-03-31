<?php

abstract class SupportHandler {
    protected $nextHandler;

    public function setNext(SupportHandler $handler): SupportHandler {
        $this->nextHandler = $handler;
        return $handler;
    }

    abstract public function handle(int $choice);
}

class TechSupport extends SupportHandler {
    public function handle(int $choice) {
        if ($choice === 1) {
            echo "--- Підтримка: Технічний відділ допоможе вам з налаштуванням інтернету. ---\n";
            return true;
        }
        return $this->nextHandler ? $this->nextHandler->handle($choice) : false;
    }
}

class BillingSupport extends SupportHandler {
    public function handle(int $choice) {
        if ($choice === 2) {
            echo "--- Підтримка: Фінансовий відділ перевіряє ваш баланс. ---\n";
            return true;
        }
        return $this->nextHandler ? $this->nextHandler->handle($choice) : false;
    }
}

class SalesSupport extends SupportHandler {
    public function handle(int $choice) {
        if ($choice === 3) {
            echo "--- Підтримка: Менеджер з продажів підбере вам новий тариф. ---\n";
            return true;
        }
        return $this->nextHandler ? $this->nextHandler->handle($choice) : false;
    }
}

class OperatorSupport extends SupportHandler {
    public function handle(int $choice) {
        if ($choice === 0) {
            echo "--- Підтримка: З'єднуємо з живим оператором... ---\n";
            return true;
        }
        return $this->nextHandler ? $this->nextHandler->handle($choice) : false;
    }
}

echo "Вітаємо у системі підтримки!\n";
$chain = new TechSupport();
$chain->setNext(new BillingSupport())
    ->setNext(new SalesSupport())
    ->setNext(new OperatorSupport());

do {
    echo "\nОберіть питання:\n";
    echo "1. Проблеми з інтернетом\n";
    echo "2. Питання по оплаті\n";
    echo "3. Бажаєте стати клієнтом\n";
    echo "0. З'єднати з оператором\n";
    echo "Ваш вибір: ";

    $input = trim(fgets(STDIN));
    $found = $chain->handle((int)$input);

    if (!$found) {
        echo "Невірний вибір. Спробуйте ще раз.\n";
    }
} while (!$found);