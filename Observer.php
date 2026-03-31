<?php

abstract class LightNode { abstract public function renderOuter(): string; }

class LightTextNode extends LightNode {
    private $text;
    public function __construct($t) { $this->text = $t; }
    public function renderOuter(): string { return $this->text; }
}

// ПАТЕРН СПОСТЕРІГАЧ
interface EventListener {
    public function update(string $eventType, string $tagName);
}

// Конкретний спостерігач
class HtmlEventListener implements EventListener {
    public function update(string $eventType, string $tagName) {
        echo "[Подія]: На елементі <$tagName> спрацював обробник '$eventType'.\n";
    }
}

class LightElementNode extends LightNode {
    private $tag, $display, $single, $classes = [], $children = [];

    private $eventListeners = [];

    public function __construct($t, $d, $s = false, $c = []) {
        $this->tag = $t; $this->display = $d; $this->single = $s; $this->classes = $c;
    }

    public function addEventListener(string $eventType, EventListener $listener) {
        $this->eventListeners[$eventType][] = $listener;
    }

    public function triggerEvent(string $eventType) {
        if (isset($this->eventListeners[$eventType])) {
            foreach ($this->eventListeners[$eventType] as $listener) {
                $listener->update($eventType, $this->tag);
            }
        }
    }

    public function addChild(LightNode $n) { $this->children[] = $n; }
    public function renderInner(): string {
        return implode('', array_map(fn($c) => $c->renderOuter(), $this->children));
    }

    public function renderOuter(): string {
        $cls = count($this->classes) ? ' class="'.implode(' ', $this->classes).'"' : '';
        if ($this->single) return "<{$this->tag}{$cls} />" . ($this->display == 'block' ? "\n" : "");
        $res = "<{$this->tag}{$cls}>" . $this->renderInner() . "</{$this->tag}>";
        return $this->display == 'block' ? $res . "\n" : $res;
    }
}

// Демонстрація
echo "--- Завдання 3: Спостерігач (Observer) ---\n";
$button = new LightElementNode("button", "inline", false, ["btn-submit"]);
$logger = new HtmlEventListener();


$button->addEventListener("click", $logger);
$button->addEventListener("mouseover", $logger);


$button->triggerEvent("click");
$button->triggerEvent("mouseover");