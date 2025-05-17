<?php
class PasswordGenerator {
    private $length;
    private $uppercase;
    private $lowercase;
    private $numbers;
    private $special;

    public function __construct($length, $uppercase, $lowercase, $numbers, $special) {
        $this->length=$length;
        $this->uppercase=$uppercase;
        $this->lowercase=$lowercase;
        $this->numbers=$numbers;
        $this->special=$special;
    }

    public function generate() {
        $upper='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower='abcdefghijklmnopqrstuvwxyz';
        $nums='0123456789';
        $specs='!@#$%^&*()_+-={}[]<>?';

        $password='';

        $password .=$this->getRandomChars($upper, $this->uppercase);
        $password .=$this->getRandomChars($lower, $this->lowercase);
        $password .=$this->getRandomChars($nums, $this->numbers);
        $password .=$this->getRandomChars($specs, $this->special);

        $remaining =$this->length - strlen($password);
        if ($remaining > 0) {
            $all=$upper . $lower . $nums . $specs;
            $password .=$this->getRandomChars($all, $remaining);
        }

        return str_shuffle($password);
    }

    private function getRandomChars($chars, $count) {
        $result='';
        $max=strlen($chars) - 1;
        for ($i=0; $i < $count; $i++) {
            $result .=$chars[random_int(0, $max)];
        }
        return $result;
    }
}
?>
