<?php


class TextDocument {
    private $content;
    public function __construct($content) { $this->content = $content; }
    public function getContent() { return $this->content; }
}

class Memento {
    private $document;
    public function __construct(TextDocument $doc) { $this->document = $doc; }
    public function getDocument() { return $this->document; }
}

class TextEditor {
    private $document;
    private $history = [];

    public function type($text) {
        $this->document = new TextDocument($text);
    }

    public function save(): Memento {
        echo "Збереження стану: " . $this->document->getContent() . "\n";
        return new Memento($this->document);
    }

    public function restore(Memento $memento) {
        $this->document = $memento->getDocument();
        echo "Стан відновлено: " . $this->document->getContent() . "\n";
    }

    public function getOutput() { return $this->document ? $this->document->getContent() : ""; }
}


$editor = new TextEditor();
$editor->type("Перша версія тексту");
$history = $editor->save();

$editor->type("Друга версія з помилкою");
echo "Поточний текст: " . $editor->getOutput() . "\n";

$editor->restore($history);
echo "Текст після відміни: " . $editor->getOutput() . "\n";