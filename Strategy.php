<?php


interface ImageLoadingStrategy {
    public function fetchImage(string $href): string;
}

class LocalDiskStrategy implements ImageLoadingStrategy {
    public function fetchImage(string $href): string {
        return "Зображення '$href' завантажено з локального диска.";
    }
}

class NetworkStrategy implements ImageLoadingStrategy {
    public function fetchImage(string $href): string {
        return "Зображення '$href' завантажено з мережі інтернет.";
    }
}

// Твій базовий клас
abstract class LightNode { abstract public function renderOuter(): string; }

// Новий елемент Image, що використовує Стратегію
class LightImageNode extends LightNode {
    private $href;
    private $strategy;

    public function __construct(string $href) {
        $this->href = $href;
        // Автоматично обираємо стратегію
        if (str_starts_with($href, 'http')) {
            $this->strategy = new NetworkStrategy();
        } else {
            $this->strategy = new LocalDiskStrategy();
        }
    }

    public function renderOuter(): string {
        $loadingMessage = $this->strategy->fetchImage($this->href);
        return "<img src='{$this->href}' /> \n";
    }
}


echo "--- Завдання 4: Стратегія (Strategy) ---\n";

$localImg = new LightImageNode("assets/logo.png");
$remoteImg = new LightImageNode("https://google.com/logo.png");

echo $localImg->renderOuter();
echo $remoteImg->renderOuter();