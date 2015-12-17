<?php

class DueDateCalculator {

    private $availableFrom = 9;
    private $availableTo = 17;
    private $availableDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    
    public function __construct() {
        
    }

    public function calculateDueDate(DateTime $startDate, $turnaroundHours) {
        $seconds = $turnaroundHours * 60 * 60;

        $to = new DateTime($startDate->format('Y-m-d') . ' ' . $this->availableTo . ':00');
        $diff = $to->getTimestamp() - $startDate->getTimestamp();
        if ($diff > $seconds) {
            $dueDate = $startDate->getTimestamp() + $seconds;
            return date('Y-m-d H:i:s', $dueDate);
        } else {
            $seconds -= ($to->getTimestamp() - $startDate->getTimestamp());
            $nextDay = $this->getNextValidDay(strtotime('+1 day', $startDate->getTimestamp()));
            return $this->calculateDueDate(new DateTime($nextDay), $seconds / 60 / 60);
        }
    }

    private function getNextValidDay($timestamp) {
        $nextDay = date('Y-m-d ' . $this->availableFrom . ':00', $timestamp);
        if ($this->isDayAvailable(new DateTime($nextDay))) {
            return $nextDay;
        }
        return $this->getNextValidDay(strtotime('+1 day', strtotime($nextDay)));
    }

    private function isDayAvailable(DateTime $date) {
        $weekDay = $date->format('l');
        if ($this->isTimeAvailable($date) && in_array($weekDay, $this->availableDays)) {
            return true;
        }
        return false;
    }

    private function isTimeAvailable(DateTime $date) {
        $baseDate = $date->format('Y-m-d');
        $from = new DateTime($baseDate . ' ' . $this->availableFrom . ':00');
        $to = new DateTime($baseDate . ' ' . $this->availableTo . ':00');
        $startTimestamp = $date->getTimestamp();
        if ($from->getTimestamp() <= $startTimestamp && $to->getTimestamp() >= $startTimestamp) {
            return true;
        }
        return false;
    }

}
