<?php
class Calculator
{
    // konstruktor pliku
    private $file;
    public function __construct(string $file)
    {
        $this->file = $file;
    }

    // sprawdzenie poprawności pliku
    private function isFileValid() : bool
    {
        try{
            $ext = pathinfo($this->file, PATHINFO_EXTENSION);
            // dopuszczone rozszerzenia
            $allowedExtensions = ['txt', 'csv'];
            if (!in_array($ext, $allowedExtensions)) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //czyszczenie historii 
    public function clearHistory() : bool
    {
        try {
            if ($this->isFileValid()) {
                if (file_exists($this->file)) {
                    try {
                        file_put_contents($this->file, "");
                        return true;
                    } catch (Exception $e) {
                        return $e;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e){
            return false;
        }
    }

    // odczyt historii
    public function history() : string
    {
        try {
            $error = "[ Nieprawidłowy plik ]";
            if ($this->isFileValid() != false) {
                $file = $this->file;
                    try {
                        if (file_exists($file)) {
                            return  file_get_contents($file);
                        }
                    } catch (Exception $e) {
                    return $e->getMessage();
                    }
            } else {
                return $error ;
            }
        } catch (Exception $e) {
            return $error ;
        }
    }
    
    //sprawdzenie poprawności wartości i operatora
    private function isDataValid(float $a, float $b, string $operator) : bool
    {
        if (is_numeric($a) && is_numeric($b) && !empty($operator)) {
            return true;
        } else {
            return false;
        }
    }

    // wykonywanie obliczeń
    public function getResultOfOperation(float $a, float $b, string $operator)
    {
        if ($this->isDataValid($a, $b, $operator) == true) {
            switch ($operator) {
                case '+':
                    $this->addItemToHistory('+', $a, $b, $a + $b);
                    return $a + $b;
                    break;

                case '-':
                    $this->addItemToHistory('-', $a, $b, $a - $b);
                    return $a - $b;
                    break;

                case '*':
                    $this->addItemToHistory('*', $a, $b, $a * $b);
                    return $a * $b;
                    break;

                case '/':
                    if ($b > 0) {
                        $this->addItemToHistory('/', $a, $b, $a / $b);
                        return $a / $b;
                    } else {
                        return 'Dzielenie przez 0!';
                    }

                case 'mod':
                    if ($b > 0) {
                        $this->addItemToHistory('mod', $a, $b, fmod($a, $b));
                        return fmod($a, $b);
                    } else {
                        return 'Dzielenie przez 0!';
                    }

                case '%':
                    $this->addItemToHistory('%', $a, $b, ($a * $b) / 100);
                    return ($a * $b) / 100;
                    break;

                default:
                return "Nieznany operator";
            }
        } else {
            return "Nieprawidłowa wartość";
        }
    }

    // dodawanie działania do historii
    private function addItemToHistory(string $operator, float $a, float $b, float $result) : void
    {
        try{
            if ($this->isFileValid()) {
                $history = "$a $operator $b = $result\n";
                try {
                    file_put_contents($this->file, $history, FILE_APPEND);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        } catch (Exception $e) {
        }
    }
}

// /////////////////// // //////////////
// Test wykoanania działań
/////// /////////// // /////////////////

$a = 22; $b = 5;
$calculator = new Calculator('history.txt');

$result = "a = {$a}, b = {$b}<br />";
$result .= "Dodawanie:<br />";
$result .= $calculator->getResultOfOperation($a, $b, '?>');
$result .= "<br />Odejmowanie:<br />";
$result .= $calculator->getResultOfOperation($a, $b, '-');
$result .= "<br />Mnożenie:<br />";
$result .= $calculator->getResultOfOperation($a, $b, '*');
$result .= "<br />Dzielenie:<br />";
$result .= $calculator->getResultOfOperation($a, $b, '/');
$result .= "<br />Modulo:<br />";
$result .= $calculator->getResultOfOperation($a, $b, 'mod');
$result .= "<br />Procent:<br />";
$result .= $calculator->getResultOfOperation($a, $b, '%');
$result .= "<br />Wczytywanie historii<br />";
$result .= $calculator->history();

echo $result;
$calculator->clearHistory();